<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class VolumeAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Basics')
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
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('volumeNumber', null, [
                'label' => 'Volume Number'
            ])
            ->add('nameplateKey');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('volumeNumber', null, [
                'label' => 'Volume Number'
            ])
            ->add('volumeStartDate', 'date')
            ->add('volumeEndDate', 'date')
            ->add('nameplateKey', ChoiceType::class, [
                'label' => 'Nameplate',
                'choices' => [
                    'The Pacer' => 'pacer',
                    'The Volette' => 'volette'
                ]
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'show' => []
                ]
            ]);
    }
}
