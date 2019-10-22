<?php


namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class SegmentationLearningData
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\SegmentationLearningDataRepository")
 */
class NeuralNetworkData
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
    private $tensorFlowModel;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $model;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * NeuralNetworkData constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return NeuralNetworkData
     */
    public function setId(int $id): NeuralNetworkData
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return File
     */
    public function getTensorFlowModel(): File
    {
        return $this->tensorFlowModel;
    }

    /**
     * @param File $tensorFlowModel
     * @return NeuralNetworkData
     */
    public function setTensorFlowModel(File $tensorFlowModel): NeuralNetworkData
    {
        $this->tensorFlowModel = $tensorFlowModel;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param DateTime $createdAt
     * @return NeuralNetworkData
     */
    public function setCreatedAt(DateTime $createdAt): NeuralNetworkData
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return NeuralNetworkData
     */
    public function setEnabled(bool $enabled): NeuralNetworkData
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * @param string $model
     * @return NeuralNetworkData
     */
    public function setModel(string $model): NeuralNetworkData
    {
        $this->model = $model;
        return $this;
    }
}
