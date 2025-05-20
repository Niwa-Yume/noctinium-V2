<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class NewEventForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('date', DateTimeType::class, ['widget' => 'single_text'])
            ->add('description')
            ->add('image', UrlType::class, ['required' => false])
            ->add('lieu')
            ->add('price')
            ->add('categories', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'name',
                'multiple'     => true,
                'expanded'     => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
