<?php
namespace App\Form\Type;

use App\Entity\Book;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => $options['require_name'],
            ])
            ->add('year', TextType::class, [
                'required' => $options['require_year'],
            ])
            ->add('author', TextType::class, [
                'required' => $options['require_author'],
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
            'require_name' => true,
            'require_year' => true,
            'require_author' => true,
        ]);

        $resolver->setAllowedTypes('require_name', 'bool');
        $resolver->setAllowedTypes('require_year', 'bool');
        $resolver->setAllowedTypes('require_author', 'bool');
    }
}