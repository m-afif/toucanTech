<?php

namespace TT\memberBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Schools
 *
 * @ORM\Table(name="schools")
 * @ORM\Entity(repositoryClass="TT\memberBundle\Repository\SchoolsRepository")
   */
class Schools
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;
/**
	* @ORM\ManyToMany(targetEntity="TT\memberBundle\Entity\Members",inversedBy="schools", cascade={"persist"})
	  @ORM\JoinColumn(name="members", referencedColumnName="id")
	*/
	
	private $members;
    
	public function __construct() {
        $this->members = new ArrayCollection();
    }
	
	public function addMember(Members $member)
  {
    //$this->schools[] = $school;
	if(!$this->members->contains($member)){
			$this->members->add($member);
	}
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
     * @return Schools
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
	
	public function getMembers(){
		
		return $this->members;
	}
}
