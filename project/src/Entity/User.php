<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PlumTreeSystems\UserBundle\Entity\TokenUser;

/**
 * Class User
 * @package App\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends TokenUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Analysis[]
     * @ORM\OneToMany(targetEntity="Analysis", mappedBy="user")
     */
    private $analyses;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->analyses = new ArrayCollection();
    }


    /**
     * Return unique user Id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set unique user Id
     *
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return Analysis[]
     */
    public function getAnalyses(): array
    {
        return $this->analyses;
    }

    /**
     * @param Analysis[] $analyses
     * @return User
     */
    public function setAnalyses(array $analyses): User
    {
        $this->analyses = $analyses;
        return $this;
    }
}
