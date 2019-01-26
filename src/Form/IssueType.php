<?php

namespace App\Form;

use App\Entity\Volume;
use App\Entity\Issue;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IssueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Issue::class,
        ]);
    }
}
