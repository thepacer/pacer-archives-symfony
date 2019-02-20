<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

final class IssueAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Basics')
            ->add('volume')
            ->add('issueDate', DateType::class, [
                'years' => range(1928, date('Y') + 5)
            ])
            ->add('issueNumber', null, [
                'label' => 'Displayed Issue Number'
            ])
            ->add('pageCount', null, [
                'label' => 'Page Count'
            ])
            ->add('archiveKey', null, [
                'label' => 'Archive.org Identifier'
            ])
            ->add('archiveNotes', null, [
                'label' => 'Archive Notes'
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('issueDate', 'doctrine_orm_date_range', [], null, [
                'field_options' => [
                    'years' => range(1928, date('Y') + 5)
                ]
            ])
            ->add('issueNumber', null, [
                'label' => 'Displayed Issue Number'
            ])
            ->add('pageCount', null, [
                'label' => 'Page Count'
            ])
            ->add('volume')
            ->add('archiveKey', null, [
                'label' => 'Archive.org Identifier'
            ])
            ->add('archiveNotes', null, [
                'label' => 'Archive Notes'
            ]);
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('issueDate', 'date')
            ->add('issueNumber', null, [
                'label' => 'Displayed Issue Number'
            ])
            ->add('pageCount', null, [
                'label' => 'Page Count'
            ])
            ->add('volume')
            ->add('archiveKey', null, [
                'label' => 'Archive.org Identifier'
            ])
            ->add('archiveNotes', null, [
                'label' => 'Archive Notes'
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'show' => []
                ]
            ]);
    }
}
