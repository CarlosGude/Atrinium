<?php

namespace App\Form;

use App\Entity\Exchange;
use PhpParser\Node\Expr\BinaryOp\GreaterOrEqual;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;

class ExchangeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('originCurrency',CurrencyType::class,['label' => 'exchange.originCurrency'])
            ->add('destinyCurrency',CurrencyType::class,['label' => 'exchange.destinyCurrency'])
            ->add('originValue',NumberType::class,[
                'label' => 'exchange.originValue',
                'attr' => ['min' => 0],
                'constraints' => [new GreaterThanOrEqual(['value' => 0])]
            ])
            ->add('date',DateType::class,[
                'label' => 'exchange.date',
                'widget' => 'single_text',
                'constraints' => [new LessThanOrEqual(['value' => new \DateTime()])]
            ])
            ->add('submit',SubmitType::class,['label' => 'save'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Exchange::class,
        ]);
    }
}
