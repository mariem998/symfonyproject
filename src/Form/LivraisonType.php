<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Repository\LivraisonRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
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
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Envoyer'
            ]);

    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' =>Commande::class,
        ]);
    }
}