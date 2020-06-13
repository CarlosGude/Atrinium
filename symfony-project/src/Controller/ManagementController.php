<?php

namespace App\Controller;

use App\Entity\Company;
use App\Entity\Filter;
use App\Security\Voter\AbstractVoter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Knp\Component\Pager\PaginatorInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ManagementController.
 *
 * @Route("management",name="management_")
 */
class ManagementController extends AbstractController
{
    public const ENTITY_NAMESPACE = 'App\Entity\\';
    public const FORM_NAMESPACE = 'App\Form\\';

    /**
     * @Route("/dashboard/", name="dashboard")
     */
    public function index(): Response
    {
        return $this->render('management/dashboard.html.twig');
    }

    /**
     * @Route("/list/{entity}/{filter}", name="list")
     * @param string $entity
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @param Filter|null $filter
     * @return Response
     */
    public function list(
        string $entity,
        EntityManagerInterface $em,
        PaginatorInterface $paginator,
        Request $request,
        Filter $filter = null
    ): Response
    {
        $class = self::ENTITY_NAMESPACE.ucfirst($entity);

        $this->denyAccessUnlessGranted(AbstractVoter::READ, new $class());

        $data = ($class === Company::class)
            ? $em->getRepository(Company::class)->findByFilter($this->getUser(),$filter)
            : $em->getRepository($class)->findAll();


        $pagination = $paginator->paginate($data, $request->query->getInt('page', 1), 10);

        return $this->render('management/list/'.$entity.'.html.twig', [
            'pagination' => $pagination,
            'entity' => $entity,
            'filter' => $filter
        ]);
    }

    /**
     * @Route("/delete-{entity}/{id}", name="delete")
     * @param string $entity
     * @param string $id
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function delete(string $entity, string $id, Request $request, EntityManagerInterface $em): Response
    {
        $class = self::ENTITY_NAMESPACE.ucfirst($entity);

        if (!class_exists($class)) {
            throw new NotFoundHttpException('Page not found.');
        }

        $this->denyAccessUnlessGranted(AbstractVoter::DELETE, new $class());

        $element = $em->getRepository($class)->find($id);

        if (!$element) {
            throw new RuntimeException('Page not found.');
        }

        $em->remove($element);
        $em->flush();

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/create/{entity}/{filter}", name="create")
     * @param string $entity
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @param Filter|null $filter
     * @return Response
     */
    public function create(
        string $entity,
        Request $request,
        EntityManagerInterface $em,
        TranslatorInterface $translator,
        Filter $filter = null
    ): Response {

        $class = self::ENTITY_NAMESPACE.ucfirst($entity);
        $formClass = self::FORM_NAMESPACE.ucfirst($entity).'Type';

        $this->denyAccessUnlessGranted(AbstractVoter::CREATE, new $class());
        if (!class_exists($class)) {
            throw new NotFoundHttpException('Page not found.');
        }

        if (!class_exists($formClass)) {
            throw new NotFoundHttpException('The form not exists.');
        }

        $element = ($filter && $class === Filter::class) ? $filter : new $class() ;

        $form = $this->createForm($formClass, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($element);
            $em->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->json($element);
            }

            $this->addFlash('success', $translator->trans($entity.'.created', ['{{element}}' => $element]));

            return $this->redirectToRoute('management_list', ['entity' => $entity,]);
        }

        return $this->render('management/create/'.$entity.'.html.twig', [
            'entity' => $entity,
            'form' => $form->createView(),
            'element' => $element
        ]);
    }

    /**
     * @Route("/edit/{entity}/{id}", name="edit")
     * @param string $entity
     * @param string $id
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param TranslatorInterface $translator
     * @return Response
     */
    public function edit(
        string $entity,
        string $id,
        Request $request,
        EntityManagerInterface $em,
        TranslatorInterface $translator
    ): Response {
        $class = self::ENTITY_NAMESPACE.ucfirst($entity);
        $formClass = self::FORM_NAMESPACE.ucfirst($entity).'Type';

        if (!class_exists($class)) {
            throw new NotFoundHttpException('Page not found.');
        }

        if (!class_exists($formClass)) {
            throw new NotFoundHttpException('The form not exists.');
        }

        $element = $em->getRepository($class)->find($id);

        if (!$element) {
            throw new RuntimeException('Page not found.');
        }

        $this->denyAccessUnlessGranted(AbstractVoter::UPDATE, $element);

        $form = $this->createForm($formClass, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', $translator->trans($entity.'.edited', ['{{element}}' => $element]));
            return $this->redirectToRoute('management_list', ['entity' => $entity]);
        }

        return $this->render('management/edit/'.$entity.'.html.twig', [
            'element' => $element,
            'entity' => $entity,
            'form' => $form->createView(),
        ]);
    }
}
