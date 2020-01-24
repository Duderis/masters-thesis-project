<?php


namespace App\Form;

use App\Entity\ClassificationLearningData;
use App\Entity\File;
use PlumTreeSystems\FileBundle\Form\Type\PTSFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class NewClassificationDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image', PTSFileType::class, [
                'multiple' => false,
                'path' => 'learning/classification/original',
                'saveExt' => true
            ])
            ->add('classification', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'Sidebar' => 'sidebar',
                    'Tower' => 'tower'
                ]
            ])
            ->add('enabled', CheckboxType::class)
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {

                /** @var ClassificationLearningData $data */
                $data = $event->getData();
                $image = $data->getImage();
                $path = $image->getContextValue('path');
                $path .= '/' . $data->getClassification() . '/';
                $image->addContext('path', $path);
            })
        ;
    }
}
