<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
