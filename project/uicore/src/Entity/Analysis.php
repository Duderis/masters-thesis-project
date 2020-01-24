<?php


namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Analysis
 * @package App\Entity
 * @ORM\Entity(repositoryClass="App\Repository\AnalysisRepository")
 */
class Analysis
{
    const SCHEDULE_STATE_PLANNED = 'PLANNED';
    const SCHEDULE_STATE_FAILED = 'FAILED';
    const SCHEDULE_STATE_COMPLETE = 'COMPLETE';
    const SCHEDULE_STATE_CANCELLED = 'CANCELLED';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $categoryResult;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $elementResult;

    /**
     * @var File
     * @ORM\OneToOne(targetEntity="File", cascade={"persist"})
     * @ORM\JoinColumn(name="target_file_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $analysisTarget;

    /**
     * @var File
     * @ORM\OneToOne(targetEntity="File", cascade={"persist"})
     * @ORM\JoinColumn(name="result_file_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $segmentationResult;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $scheduleState;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $schedulePosition;

    /**
     * @var DateTime
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="User", inversedBy="analyses")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * Analysis constructor.
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->categoryResult = '';
        $this->elementResult = '';
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
     * @return Analysis
     */
    public function setId(int $id): Analysis
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Analysis
     */
    public function setName(?string $name): Analysis
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategoryResult(): ?string
    {
        return $this->categoryResult;
    }

    /**
     * @param string $categoryResult
     * @return Analysis
     */
    public function setCategoryResult(?string $categoryResult): Analysis
    {
        $this->categoryResult = $categoryResult;
        return $this;
    }

    /**
     * @return string
     */
    public function getElementResult(): ?string
    {
        return $this->elementResult;
    }

    /**
     * @param string $elementResult
     * @return Analysis
     */
    public function setElementResult(?string $elementResult): Analysis
    {
        $this->elementResult = $elementResult;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAnalysisTarget()
    {
        return $this->analysisTarget;
    }

    /**
     * @param mixed $analysisTarget
     * @return Analysis
     */
    public function setAnalysisTarget($analysisTarget): Analysis
    {
        $this->analysisTarget = $analysisTarget;
        return $this;
    }

    /**
     * @return File
     */
    public function getSegmentationResult(): File
    {
        return $this->segmentationResult;
    }

    /**
     * @param File $segmentationResult
     * @return Analysis
     */
    public function setSegmentationResult(File $segmentationResult): Analysis
    {
        $this->segmentationResult = $segmentationResult;
        return $this;
    }

    /**
     * @return string
     */
    public function getScheduleState(): ?string
    {
        return $this->scheduleState;
    }

    /**
     * @param string $scheduleState
     * @return Analysis
     */
    public function setScheduleState(?string $scheduleState): Analysis
    {
        $this->scheduleState = $scheduleState;
        return $this;
    }

    /**
     * @return int
     */
    public function getSchedulePosition(): ?int
    {
        return $this->schedulePosition;
    }

    /**
     * @param int $schedulePosition
     * @return Analysis
     */
    public function setSchedulePosition(?int $schedulePosition): Analysis
    {
        $this->schedulePosition = $schedulePosition;
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
     * @return Analysis
     */
    public function setCreatedAt(DateTime $createdAt): Analysis
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return User
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Analysis
     */
    public function setUser(?User $user): Analysis
    {
        $this->user = $user;
        return $this;
    }
}
