<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ISBN', null, ['label' => 'ISBN'] )
            ->add('title', null, ['label' => 'Title'])
            ->add('publisher', null, ['label' => 'Publisher'])
            ->add('issueNumber', null, ['label' => 'Issue Number'])
            ->add('pages', null, ['label' => 'No. of Pages'])
            ->add('releaseDate', null, ['label' => 'Release Date'])
            ->add('genre', null, ['label' => 'Genre'])
            ->add('language', null, ['label' => 'Language'])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => function ($author)
                {
                    return $author->getFirstName() . ' ' . $author->getLastName(); 
                },
            ])
            ->add('branch')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
