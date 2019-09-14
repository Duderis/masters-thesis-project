<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ClassificationLearningData
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\ClassificationLearningDataRepository")
 */
class ClassificationLearningData extends LearningData
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $classification;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ClassificationLearningData
     */
    public function setId(int $id): ClassificationLearningData
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getClassification(): ?string
    {
        return $this->classification;
    }

    /**
     * @param string $classification
     * @return ClassificationLearningData
     */
    public function setClassification(?string $classification): ClassificationLearningData
    {
        $this->classification = $classification;
        return $this;
    }
}
