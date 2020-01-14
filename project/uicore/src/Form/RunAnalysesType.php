<?php


namespace App\Form;

use App\Entity\TaughtModel;
use App\Service\TeachingManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RunAnalysesType extends AbstractType
{

    /**
     * @var TeachingManager
     */
    private $teachingManager;

    /**
     * RunAnalysesType constructor.
     * @param TeachingManager $teachingManager
     */
    public function __construct(TeachingManager $teachingManager)
    {
        $this->teachingManager = $teachingManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $asd = 5;
        $segmentationModels = $this->teachingManager->getTaughtModels(TaughtModel::SEGMENTATION_TYPE);
        $classificationModels = $this->teachingManager->getTaughtModels(TaughtModel::CLASSIFICATION_TYPE);

        $segmentationModels = array_reduce($segmentationModels, function ($aggr, $item) {
            /** @var $item TaughtModel */
            $aggr[$item->getModelFile()->getName()] = $item->getId();

            return $aggr;
        }, []);
        $classificationModels = array_reduce($classificationModels, function ($aggr, $item) {
            /** @var $item TaughtModel */
            $aggr[$item->getModelFile()->getName()] = $item->getId();

            return $aggr;
        }, []);

        $builder
            ->add('segmentationModel', ChoiceType::class, [
                'required' => true,
                'choices' => $segmentationModels
            ])
            ->add('classificationModel', ChoiceType::class, [
                'required' => true,
                'choices' => $classificationModels
            ])
            ->add('submit', SubmitType::class);
    }
}