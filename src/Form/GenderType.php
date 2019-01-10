<?php
/**
 * Created by PhpStorm.
 * User: daniel.zuwala
 * Date: 18/12/2018
 * Time: 14:06
 */

namespace App\Form;

use App\Entity\Gender;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class GenderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [

                'label' =>  false,
                'required' => true,
                'expanded' => true,
                'choices' => [
                    Gender::FEMALE => Gender::FEMALE,
                    Gender::MALE => Gender::MALE
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Gender::class,
        ]);
    }
}
