<?php

namespace App\Entity;

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
}
