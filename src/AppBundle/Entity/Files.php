<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Files
 *
 * @ORM\Table(name="files")
 * @ORM\Entity()
 */
class Files
{
    private static $URL = "http://przystanki.vot.pl/uploads/images/";

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="file", type="string", length=255, unique=true)
     * @Assert\Image()
     */
    private $file;

    /**
     * @ORM\ManyToOne(targetEntity="Station", inversedBy="files")
     * @ORM\JoinColumn(name="station_id", referencedColumnName="id")
     */
    private $station;

    function getFileUrl(){
        return self::$URL.$this->file;
    }

    function getUser() {
        return $this->user;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setStation(\AppBundle\Entity\Station $station = null)
    {
        $this->station = $station;
        return $this;
    }

    public function getStation()
    {
        return $this->station;
    }
}
