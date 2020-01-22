<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use PlumTreeSystems\FileBundle\Entity\File as PTSFile;

/**
 * File
 *
 * @ORM\Table(name="uicore_file")
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File extends PTSFile
{

    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return File
     */
    public function setId(int $id): File
    {
        $this->id = $id;
        return $this;
    }
}
