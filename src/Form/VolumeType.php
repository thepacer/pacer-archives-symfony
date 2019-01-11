<?php

namespace App\Form;

use App\Entity\Volume;
use App\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolumeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('volumeNumber')
            ->add('volumeStartDate', DateType::class, [
                'years' => range(1928, date('Y') + 5),
                'months' => [8],
                'days' => [1]
            ])
            ->add('volumeEndDate', DateType::class, [
                'years' => range(1928, date('Y') + 5),
                'months' => [7],
                'days' => [31]
            ])
            ->add('nameplateKey')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Volume::class,
        ]);
    }
}
