<?php
/**
 * Created by PhpStorm.
 * User: daniel.zuwala
 * Date: 18/12/2018
 * Time: 14:06
 */

namespace App\Form;

use App\Entity\Account;
use App\Entity\Job;
use App\Form\GenderType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Country;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'label.firstName',
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 128])
                ]
            ])
            ->add('lastName', TextType::class, [
                'label' => 'label.lastName',
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 128])
                ]
            ])
            ->add('gender', GenderType::class, [
                'label' => 'label.gender',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'required' => true,
                'constraints' => [
                    new Email(),
                    new Length(['max' => 128])
                ]
            ])
            ->add('country', CountryType::class, [
                'label' => 'label.country',
                'constraints' => [
                    new NotBlank(),
                    new Country()
                ]
            ])
            ->add('birthday', BirthdayType::class, [
                'label' => 'label.birthday',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Date()
                ]
            ])
            ->add('job', EntityType::class, [
                'label' => 'label.job',
                'class' => Job::class,
                'choice_label' => 'name',
                'choice_value' => 'code',
                'constraints' => [
                    new NotBlank(),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
            'constraints' => array(
                new UniqueEntity(array('fields' => array('email')))),
        ]);
    }
}
