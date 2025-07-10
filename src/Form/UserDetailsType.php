<?php

namespace App\Form;

    use App\Entity\UserDetails;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\IntegerType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;
    use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

    class UserDetailsType extends AbstractType
     {
        public function buildForm(FormBuilderInterface $builder, array $options)
        {
            $builder
                ->add('varsta', IntegerType::class, [
                    'label' => 'Age',
                    'constraints' => [
                        new GreaterThanOrEqual([
                            'value' => 16,
                            'message' => 'You must be at least 16 years old.',
                        ]),
                    ],
                    'attr' => [
                        'min' => 16,
                        'autocomplete' => 'off',
                    ],
                ]);
        }

        public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' => UserDetails::class,
            ]);
        }
     }
