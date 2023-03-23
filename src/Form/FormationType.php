<?php

namespace App\Form;

use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;
use Symfony\Component\Validator\Constraints\Date;


class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt',DateType::class,[
                'required' => true,
                'widget'=>'single_text',
                'data' => isset($options['data']) &&
                    $options['data']->getPublishedAt() != null ? $options['data']->getPublishedAt() : new DateTime('now'),
                'label'=>'Date',
                'constraints' => [
            new LessThanOrEqual([
                'value' => new DateTime('today'),
                'message' => 'La date ne peut pas être postérieure à aujourd\'hui.',
            ]),
                    ],
                ])
            ->add('title',null,[
                'label'=>'formation'])
            ->add('description')
            ->add('videoId',null,[
                'required' => true
            ])
            ->add('playlist',null,[
                'required' => true
            ])
                
            ->add('categories',null,[
                'required' => true
            ])
            ->add('submit', SubmitType::class,[
                'label'=>'Enregistrer'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
