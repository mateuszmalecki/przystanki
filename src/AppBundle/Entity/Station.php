<?php
namespace AppBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="station")
 * @ORM\Entity()
 * @Vich\Uploadable
 */
class Station
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $title = "Zaproponuj przystanek";

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Musisz uzupełnić opis przystanku.")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    private $address;

    /**
     * @ORM\Column(type="boolean", options={"default":"0"})
     */
    protected $isRead = false;

    /**
     * @ORM\Column(type="text")
     */
    private $user_id;

    /**
     * @ORM\OneToMany(targetEntity="Files", cascade={"persist"}, mappedBy="station")
     * @Assert\Valid
     * @Assert\Count(
     *      max = 3,
     *      maxMessage = "Możesz dodać maksymalnie {{ limit }} pliki"
     * )
     */
    private $files;

    public function getId()
    {
        return $this->id;
    }

    public function setTitle($title)
    {
        if($title == null){
            $title = "Zaproponuj przystanek";
        }
        $this->title = $title;
        return $this;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    public function getRead()
    {
        return $this->read;
    }

    public function setUserId($userId)
    {
        if($userId){
            $this->user_id = $userId;
        }else{
            $this->user_id = 1;
        }



        return $this;
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
        return $this;
    }

    public function getIsRead()
    {
        return $this->isRead;
    }

    public function __construct()
    {
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function addFile(\AppBundle\Entity\Files $file)
    {
        $file->setStation($this);
        $this->files[] = $file;

        return $this;
    }

    public function removeFile(\AppBundle\Entity\Files $file)
    {
        $this->files->removeElement($file);
    }

    public function getFiles()
    {
        return $this->files;
    }
}
