<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Listing;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ListingForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(message: 'Listing name is required.'),
                    new Assert\Length(
                        min: 3,
                        max: 255,
                        minMessage: 'Name must be at least {{ limit }} characters.',
                        maxMessage: 'Name cannot exceed {{ limit }} characters.',
                    ),
                ],
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new Assert\NotBlank(message: 'Description is required.'),
                    new Assert\Length(
                        min: 10,
                        max: 255,
                        minMessage: 'Description must be at least {{ limit }} characters.',
                        maxMessage: 'Description cannot exceed {{ limit }} characters.',
                    ),
                ],
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'USD',
                'constraints' => [
                    new Assert\NotBlank(message: 'Price is required.'),
                    new Assert\Positive(message: 'Price must be a positive number.'),
                    new Assert\LessThan(
                        value: 1_000_000,
                        message: 'Price must be less than {{ compared_value }}.',
                    ),
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a category',
                'required' => false,
                'constraints' => [
                    new Assert\NotNull(message: 'Please select a category.'),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Listing::class,
        ]);
    }
}
