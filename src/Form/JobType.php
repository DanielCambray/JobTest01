<?php
/**
 * Created by PhpStorm.
 * User: daniel.zuwala
 * Date: 18/12/2018
 * Time: 14:06
 */

namespace App\Form;

use App\Entity\Job;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'label.name',
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 128])
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'label.description',
                'required' => false,
                'constraints' => [
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
