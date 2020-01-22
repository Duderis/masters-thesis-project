<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class SegmentationLearningData
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\SegmentationLearningDataRepository")
 */
class SegmentationLearningData extends LearningData
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var File
     * @ORM\OneToOne(targetEntity="File", cascade={"persist"})
     * @ORM\JoinColumn(name="masked_image_file_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $maskedImage;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return SegmentationLearningData
     */
    public function setId(int $id): SegmentationLearningData
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return File
     */
    public function getMaskedImage(): ?File
    {
        return $this->maskedImage;
    }

    /**
     * @param File $maskedImage
     * @return SegmentationLearningData
     */
    public function setMaskedImage(?File $maskedImage): SegmentationLearningData
    {
        $this->maskedImage = $maskedImage;
        return $this;
    }
}
