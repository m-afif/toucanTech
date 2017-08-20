<?php

namespace TT\memberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Members
 *
 * @ORM\Table(name="members")
 * @ORM\Entity(repositoryClass="TT\memberBundle\Repository\MembersRepository")
 *
   */
class Members
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="mailaddress", type="string", length=255)
     */
    private $mailaddress;

	/**
	* @ORM\ManyToMany(targetEntity="TT\memberBundle\Entity\Schools",inversedBy="members", cascade={"persist"})
	  @ORM\JoinColumn(name="schools", referencedColumnName="id")
	*/
	protected $schools;
	
	public function __construct() {
        $this->schools = new ArrayCollection();
    }
	
	public function addSchool(Schools $school)
  {
    //$this->schools[] = $school;
	if(!$this->schools->contains($school)){
			$this->schools->add($school);
	}
  }

  public function removeSchool(Schools $school)
  {
    $this->schools->removeElement($school);
  }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Members
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set mailaddress
     *
     * @param string $mailaddress
     * @return Members
     */
    public function setMailaddress($mailaddress)
    {
        $this->mailaddress = $mailaddress;

        return $this;
    }

    /**
     * Get mailaddress
     *
     * @return string 
     */
    public function getMailaddress()
    {
        return $this->mailaddress;
    }
	
	public function getSchools(){
		return $this->schools;
	}
}

