<?php


namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class LearningData
 * @package App\Entity
 * @ORM\MappedSuperclass
 */
abstract class LearningData
{
    /**
     * @var File
     * @ORM\OneToOne(targetEntity="File", cascade={"persist"})
     * @ORM\JoinColumn(name="image_file_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $image;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * LearningData constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }

    /**
     * @return File
     */
    public function getImage(): ?File
    {
        return $this->image;
    }

    /**
     * @param File $image
     * @return LearningData
     */
    public function setImage(?File $image): LearningData
    {
        $this->image = $image;
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
     * @return LearningData
     */
    public function setCreatedAt(DateTime $createdAt): LearningData
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     * @return LearningData
     */
    public function setEnabled(?bool $enabled): LearningData
    {
        $this->enabled = $enabled;
        return $this;
    }
}
