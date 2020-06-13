<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Sector;
use App\Repository\SectorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CompanyType extends AbstractType
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();
        $builder
            ->add('name', TextType::class, ['label' => 'company.name'])
            ->add('phone', TelType::class, ['label' => 'company.phone', 'required' => false])
            ->add('email', EmailType::class, ['label' => 'company.email', 'required' => false])
            ->add('sector', EntityType::class, [
                'label' => 'company.sector',
                'class' => Sector::class,
                'query_builder' => static function (SectorRepository $repository) use ($user) {
                    return $repository->findByUserAuthorized($user);
                },
            ])
            ->add('submit', SubmitType::class, ['label' => 'save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
