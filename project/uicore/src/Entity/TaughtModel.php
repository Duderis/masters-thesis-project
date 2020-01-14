<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaughtModelRepository")
 */
class TaughtModel
{
    const SEGMENTATION_TYPE = 'segmentation';
    const CLASSIFICATION_TYPE = 'classification';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var File
     * @ORM\OneToOne(targetEntity="File", cascade={"persist"})
     * @ORM\JoinColumn(name="model_file", referencedColumnName="id", onDelete="SET NULL")
     */
    private $modelFile;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $type;

    /**
     * TaughtModel constructor.
     */
    public function __construct()
    {
        $this->enabled = true;
        $this->creationDate = new DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return File
     */
    public function getModelFile(): ?File
    {
        return $this->modelFile;
    }

    /**
     * @param File $modelFile
     * @return TaughtModel
     */
    public function setModelFile(?File $modelFile): TaughtModel
    {
        $this->modelFile = $modelFile;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate(): ?DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param DateTime $creationDate
     * @return TaughtModel
     */
    public function setCreationDate(?DateTime $creationDate): TaughtModel
    {
        $this->creationDate = $creationDate;
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
     * @return TaughtModel
     */
    public function setEnabled(bool $enabled): TaughtModel
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return TaughtModel
     */
    public function setType(?string $type): TaughtModel
    {
        $this->type = $type;
        return $this;
    }
}
