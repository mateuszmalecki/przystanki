<?php
namespace AppBundle\Form\Type;
use AppBundle\Form\Type\FilesType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class StationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $questionId = $builder->getData()->getId();

        $builder
            ->add('title', TextType::class, array(
                'label' => false,
                'data' => 'Zaproponuj przystanek',
                'attr' => array(
                    'readonly' => true,
                )
            ))

            ->add('address', TextType::class, array(
                'label' => false,
                'required' => true,
                'attr' => array(
                    'placeholder' => 'Adres przystanku (np. Racibórz, ul.Katowicka 5)'
                )
            ))

            ->add('description', TextareaType::class, array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'placeholder' => 'Dodatkowy opis (np. Przystanek znajduje się przy sklepie Biedronka)'
                )
            ))

            ->add('image', FileType::class, array(
                'label' => "Dodaj plik",
                'required' => false
            ))

            ->add('save', SubmitType::class, array(
                'label' => 'Dodaj przystanek',
                'attr' => array('class' => 'btn btn-block btn-primary'),
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Station'
        ));
    }

    public function getBlockPrefix()
    {
        return 'appbundle_station';
    }

}
