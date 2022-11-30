<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $options['user'];

        $builder

            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '100',
                ],
                'label' => 'Last Name',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '100',
                ],
                'label' => 'First Name',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]])
            ->add('number', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '9',
                    'maxlenght' => '9',
                ],
                'label' => 'Number',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]])

            ->add('zipcode', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '100',
                ],
                'label' => 'Zip Code',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]])
            ->add('pays', ChoiceType::class, [
                'choices' => [
                    'Tunis'=>'Tunis',
                    'Sfax'=>'Sfax',
                    'Sousse'=>'Sousse',
                    'Mahdia'=>'Mahdia',
                    'Bizerte'=>'Bizerte',
                    'Mednine'=>'Mednine',
                    'Hammamet'=>'Hammamet',

                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Pays',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlenght' => '2',
                    'maxlenght' => '100',
                ],
                'label' => 'Address',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]])

            ->add('modelivraison', ChoiceType::class,array(

                'choices' => array(
                    'LA LIVRAISON EN EXPRESS : Les acteurs du e-commerce ont bouleversé le paysage de la livraison en France. À tel point que la livraison express est aujourd hui un incontournable, notamment pour les destinataires professionnels.'=>"LA LIVRAISON EN EXPRESS POUR SUIVRE LE MARCHÉ",
                    'LA LIVRAISON À DOMICILE  : C’est le mode de livraison préféré des Français, choisi par 85 % des acheteurs en ligne (1). Avec le relais, le domicile est l option de livraison la plus souvent proposée aux internautes.'=>"LA LIVRAISON À DOMICILE POUR CONTENTER LE PLUS GRAND NOMBRE",
                    'LA LIVRAISON EN RELAIS : La livraison en relais permet une grande flexibilité pour le destinataire, qui peut récupérer son colis quand il le souhaite. Elle est choisie par 68 % des acheteurs.'=>"LA LIVRAISON EN RELAIS POUR LA FLEXIBILITÉ",
                    ),
                'expanded'=>true,
                'multiple'=>false
            ))

            ->add('submit', SubmitType::class, [
                'label' => 'Place Order',
                'attr' => [
                    'class' => 'btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}