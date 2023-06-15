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
            ->add('livraison', ChoiceType::class, [
                'choices' => [
                    'Express delivery'=>'Express delivery',
                    'Delivery to a relay point'=>'Delivery to a relay point',
                    ' home delivery'=>' home delivery'
                ],
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => 'Delivery method',
                'label_attr' => [
                    'class' => 'form-label  mt-4'
                ]])



            ->add('submit', SubmitType::class, [
                'label' => 'Place Order',
                'attr' => [
                    'class' => 'btn btn-lg btn-block btn-primary font-weight-bold my-3 py-3',
                    "action"=>"/checkout",
                    "Method"=>"POST",
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
