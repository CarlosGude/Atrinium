<?php

namespace App\Form;

use App\Entity\Filter;
use App\Entity\Sector;
use App\Entity\User;
use App\Repository\SectorRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class FilterType extends AbstractType
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
        /** @var User $user */
        $user = $this->security->getUser();

        $builder
            ->add('name', TextType::class, ['label' => 'company.name', 'required' => false])
            ->add('sector', EntityType::class, [
                'label' => 'company.sector',
                'query_builder' => static function (SectorRepository $repository) use ($user) {
                    return $repository->findByUserAuthorized($user);
                },
                'class' => Sector::class,
                'required' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'filter'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Filter::class,
        ]);
    }
}
