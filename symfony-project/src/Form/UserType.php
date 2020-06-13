<?php

namespace App\Form;

use App\Entity\Sector;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var User $user */
        $user = $options['data'];

        $builder
            ->add('email', EmailType::class, ['label' => 'user.email'])
            ->add('roles', ChoiceType::class, [
                'label' => 'user.roles',
                'multiple' => true,
                'choices' => [User::ROLE_ADMIN => User::ROLE_ADMIN, User::ROLE_CLIENT => User::ROLE_CLIENT],
            ])
            ->add('authorizedSectors', EntityType::class, [
                'class' => Sector::class,
                'label' => 'user.authorizedSectors',
                'multiple' => true,
            ])
        ;

        if (!$user->getId()) {
            $builder->add('password', PasswordType::class, ['label' => 'user.password']);
        }

        $builder->add('submit', SubmitType::class, ['label' => 'save']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
