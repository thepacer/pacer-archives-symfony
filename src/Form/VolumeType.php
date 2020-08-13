<?php

namespace App\Form;

use App\Entity\Volume;
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
                'widget' => 'single_text'
            ])
            ->add('volumeEndDate', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('nameplateKey')
            // ->add('coverIssue')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Volume::class,
        ]);
    }
}
