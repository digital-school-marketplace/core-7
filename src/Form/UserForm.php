<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $isEdit = $options['is_edit'];

        $builder->add('email', EmailType::class, [
            'constraints' => [
                new Assert\NotBlank(message: 'Email is required.'),
                new Assert\Email(message: 'Please enter a valid email address.'),
                new Assert\Length(max: 255),
            ],
        ]);

        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'mapped' => false,
            'required' => !$isEdit,
            'first_options' => ['label' => 'Password'],
            'second_options' => ['label' => 'Confirm Password'],
            'invalid_message' => 'Passwords must match.',
            'constraints' => $isEdit ? [] : [
                new Assert\NotBlank(message: 'Password is required.'),
                new Assert\Length(
                    min: 8,
                    minMessage: 'Password must be at least {{ limit }} characters.',
                ),
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,
        ]);
    }
}
