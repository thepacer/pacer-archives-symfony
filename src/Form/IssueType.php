<?php

namespace App\Form;

use App\Entity\Volume;
use App\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('issueDate')
            ->add('issueNumber')
            ->add('pageCount')
            ->add('archiveKey')
            ->add('archiveNotes')
            ->add('volume')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Issue::class,
        ]);
    }
}
