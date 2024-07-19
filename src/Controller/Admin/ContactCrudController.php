<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class ContactCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Demande de contact')
            ->setEntityLabelInPlural('Demandes de contact')
            ->setPageTitle('index', 'MasterCook - Administration des demandes de contact')
            ->setPaginatorPageSize(10)
            ->addFormTheme('@FOSCKEditor/Form/ckeditor_widget.html.twig')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        $textField = new TextField();
        return [
            IdField::new('id')
                ->hideOnForm(),
            $textField::new('fullName'),
            $textField::new('email')
                ->setFormTypeOption('disabled', 'disabled'),
            $textField::new('subject'),
            TextareaField::new('message')
                ->setFormType(CKEditorType::class),
            DateTimeField::new('createdAt')
                ->hideOnForm()
                ->setFormTypeOption('disabled', 'disabled'),
        ];
    }
}
