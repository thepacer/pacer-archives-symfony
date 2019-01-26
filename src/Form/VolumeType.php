<?php

namespace App\Form;

use App\Entity\Volume;
use App\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolumeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('volumeNumber', null, [
                'label' => 'Volume Number'
            ])
            ->add('volumeStartDate', null, [
                'label' => 'Start Date',
                'years' => range(1928, date('Y') + 5),
                'months' => [8],
                'days' => [1]
            ])
            ->add('volumeEndDate', null, [
                'label' => 'End Date',
                'years' => range(1928, date('Y') + 5),
                'months' => [7],
                'days' => [31]
            ])
            ->add('nameplateKey', ChoiceType::class, [
                'label' => 'Nameplate',
                'choices' => [
                    'The Pacer' => 'pacer',
                    'The Volette' => 'volette'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Volume::class,
        ]);
    }
}
