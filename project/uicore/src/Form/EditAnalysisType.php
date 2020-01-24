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
        $choices = [
            'planned' => Analysis::SCHEDULE_STATE_PLANNED,
            'cancelled' => Analysis::SCHEDULE_STATE_CANCELLED
        ];
        if (
            $options['data'] &&
            $options['data']->getId() &&
            $options['data']->getScheduleState()===Analysis::SCHEDULE_STATE_COMPLETE
        ) {
            $choices = [
                'complete' => Analysis::SCHEDULE_STATE_COMPLETE,
                'planned' => Analysis::SCHEDULE_STATE_PLANNED,
                'cancelled' => Analysis::SCHEDULE_STATE_CANCELLED
            ];
        }
        $builder
            ->add('name', TextType::class, [
                'required' => true
            ])
            ->add('scheduleState', ChoiceType::class, [
                'choices' => $choices
            ])
        ;
    }
}
