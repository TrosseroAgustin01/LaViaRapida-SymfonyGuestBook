<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('conference Comment') // boton que nos redireccionara a la creacion de nuevos comentarios
            ->setEntityLabelInPlural('conference Comment')  // titulo que contiene la vista
            ->setSearchFields(['author','text','email']) // la searchbar buscara compatibilidades con los siguientes campos
            ->setDefaultSort(['createdAt'=>'DESC']) //sort
            ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('conference')); // filtro para buscar entre las conferencias que tenemos marcadas
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('conference');
        yield TextField::new('author');
        yield EmailField::new('email');
        yield TextareaField::new('text')
            ->hideOnIndex() // ocultamos el texto del comentario en esa confererncia
    ;
yield TextField::new('photoFilename')
     ->onlyOnIndex() //solo mostramos este campo en el index pero no en la creacion
    ;

$createdAt = DateTimeField::new('createdAt')->setFormTypeOptions([
    'years' => range(date('Y'), date('Y') + 5),
    'widget' => 'single_text',
    ]);// Configuracion para que dentro del crud podamos poner fecha con un datetime Piker
if (Crud::PAGE_EDIT === $pageName) {
        yield $createdAt->setFormTypeOption('disabled', true);
        } else {
        yield $createdAt;
        }
    }

}
/*
*   Para personalizar la Commentsección, enumerar los campos explícitamente en el configureFields()método que nos permite ordenarlos de la forma que queramos.
*   Algunos campos se configuran aún más, como ocultar el campo de texto en la página de índice.
*   Los configureFilters()son métodos que definen qué filtros exponer en la parte superior del campo de búsqueda.
*/
