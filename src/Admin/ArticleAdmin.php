<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

final class ArticleAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('issue')
            ->add('headline', null, [
            ])
            ->add('alternativeHeadline', null, [
                'label' => 'Subheadline / Alternative Headline',
                'required' => false,
                'empty_data' => ''
            ])
            ->add('author_byline', null, [
                'label' => 'Author Byline',
                'empty_data' => ''
            ])
            ->add('contributor_byline', null, [
                'label' => 'Co-author / Contributor Byline',
                'empty_data' => ''
            ])
            ->add('datePublished', DateType::class, [
                'label' => 'Date Published',
                'years' => range(1928, date('Y') + 5)
            ])
            ->add('articleBody', null, [
                'label' => 'Article Body',
                'attr' => [
                    'rows' => 20
                ]
            ])
            ->add('keywords', null, [
                'help' => 'Comma-separated list of relevant terms.'
            ])
            ->add('legacyId', null, [
                'label' => 'Legacy CMS ID',
                'help' => 'Used in redirects to find new ID'
            ])
            ->add('slug', null, [
                'help' => 'Auto-generated string used in the article URL.'
            ])
            ->add('printSection', null, [
                'label' => 'Print Section',
                'help' => 'Section heading of the newspaper. Use "Cover" for Page 1, "News" if unspecified.'
            ])
            ->add('printPage', null, [
                'label' => 'Print Page Number(s)',
                'help' => 'Use comma to separate non-consecutive pages.'
            ])
            ->add('printColumn', null, [
                'label' => 'Print Column',
                'help' => 'From left, count the number of the apparent column the article begins.'
            ]);
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('issue')
            ->add('headline')
            ->add('alternativeHeadline')
            ->add('author_byline')
            ->add('contributor_byline')
            ->add('datePublished', 'doctrine_orm_date_range', [], null, [
                'field_options' => [
                    'years' => range(1928, date('Y') + 5)
                ]
            ])
            ->add('articleBody')
            ->add('keywords')
            ->add('legacyId')
            ->add('slug')
            ->add('printSection')
            ->add('printPage')
            ->add('printColumn');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('headline')
            ->add('issue')
            ->add('author_byline')
            ->add('datePublished', 'date')
            ->add('printSection')
            ->add('_action', 'actions', [
                'actions' => [
                    'edit' => [],
                ]
            ]);
    }
}
