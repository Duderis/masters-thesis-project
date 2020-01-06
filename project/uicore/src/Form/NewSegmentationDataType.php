<?php


namespace App\Form;

use PlumTreeSystems\FileBundle\Form\Type\PTSFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NewSegmentationDataType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $presetName = md5(time());
        $builder
            ->add('image', PTSFileType::class, [
                'multiple' => false,
                'path' => 'learning/segmentation/label/',
                'saveExt' => true,
                'presetName' => $presetName
            ])
            ->add('maskedImage', PTSFileType::class, [
                'multiple' => false,
                'path' => 'learning/segmentation/class/',
                'saveExt' => true,
                'presetName' => $presetName
            ])
            ->add('enabled', CheckboxType::class)
        ;
    }
}