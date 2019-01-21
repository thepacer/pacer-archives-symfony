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
            ->add('printColumn')
            ->add('printPage')
            ->add('printSection')
            ->add('articleBody')
            ->add('headline')
            ->add('alternativeHeadline')
            ->add('author_byline')
            ->add('contributor_byline')
            ->add('datePublished', DateType::class, [
                'years' => range(1928, date('Y') + 5)
            ])
            ->add('keywords')
            ->add('legacyId')
            ->add('slug')
            ->add('issue')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
