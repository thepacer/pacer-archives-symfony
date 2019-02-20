<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

final class ImageAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('Basics')
            ->add('caption')
            ->add('credit')
            ->add('path')
            ->add('article');
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('caption')
            ->add('credit')
            ->add('path')
            ->add('article');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('caption')
            ->add('credit')
            ->add('path')
            ->add('article')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                    'show' => []
                ]
            ]);
    }
}
