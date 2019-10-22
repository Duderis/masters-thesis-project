<?php


namespace App\Form;


use App\Entity\Analysis;
use PlumTreeSystems\FileBundle\Form\Type\PTSFileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditAnalysisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('scheduleState', ChoiceType::class, [
                'choices' => [
                    'planned' => Analysis::SCHEDULE_STATE_PLANNED,
                    'cancelled' => Analysis::SCHEDULE_STATE_CANCELLED
                ]
            ])
        ;
    }
}