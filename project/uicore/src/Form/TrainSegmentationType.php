<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class TrainSegmentationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('iterationNum', NumberType::class, [
                'required' => true,
                'label' => 'Number of Iterations',
                'html5' => true
            ])
            ->add('submit', SubmitType::class);
    }
}