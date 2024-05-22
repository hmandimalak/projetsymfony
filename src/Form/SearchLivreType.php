<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchLivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomLivre', TextType::class, [
                'label' => 'Nom du livre', // Optional label for the field
                'required' => false, // Allow the field to be empty
            ]);
    }
}
