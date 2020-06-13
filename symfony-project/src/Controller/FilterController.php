<?php

namespace App\Controller;

use App\Entity\Filter;
use App\Form\FilterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class FilterController.
 *
 * @Route("management/filter", name="filter")
 */
class FilterController extends AbstractController
{
    /**
     * @Route("/", name="_company")
     */
    public function filter(Request $request, EntityManagerInterface $em): RedirectResponse
    {
        $filter = new Filter();
        $form = $this->createForm(FilterType::class, $filter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($filter);
            $em->flush();

            return $this->redirectToRoute('management_list', [
                'entity' => 'company',
                'filter' => $filter->getId(),
            ]);
        }

        return $this->redirectToRoute('management_list', ['entity' => 'company']);
    }
}
