<?php

namespace TT\memberBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TT\memberBundle\Entity\Schools;
use TT\memberBundle\Entity\Members;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

class DefaultController extends Controller
{
    /*
	* this function displays the homepage
	*/
	public function indexAction()
    {
        return $this->render('TTmemberBundle:Default:index.html.twig');
    }
	
	 /*
	* this function displays the form to add a new school
	*/
	public function addSchoolFormAction()
	{
		return $this->render('TTmemberBundle:Default:addSchool.html.twig');
	}
	
	 /*
	* this function displays the form to add a new member to an existing school
	*/
	public function addMemberFormAction()
	{
		$existingSchool = $this->selectAllSchools();
		return $this->render('TTmemberBundle:Default:addMember.html.twig',array('schools' => $existingSchool));
	}
	
	 /*
	* this function displays the form to choose a school to view its members
	*/
	public function searchMembersFormAction()
	{
		$existingSchool = $this->selectAllSchools();
		return $this->render('TTmemberBundle:Default:searchMembersForm.html.twig',array('schools' => $existingSchool));
	}

	
	 /*
	* this function adds a new school into the database
	*/
	public function addSchoolAction(){
		$schoolName = $_POST["schoolName"];
		$DBerror = "The school can't be created.";
		$message = "The school has been created.";
		$existingSchool = "This school already exists.";
		if(!empty($schoolName)){
			// retrieve the school entered by the user
			$schoolResult = $this->selectSchool($schoolName);

			/*create the school if this school is not in the database, error message if the school is already
			 in the database*/
			if ((count($schoolResult)) == 0) {
				$school = new Schools();
				$school->setName($schoolName);
				
				$em = $this->getDoctrine()->getManager();
				$em->persist($school);
				$em->flush();
				
				//retrieve the school freshly created by the system
				$schoolResult = $this->selectSchool($schoolName);
				// check if the school has been successfully created
				if ((count($schoolResult)) == 0) {
					return $this->render('TTmemberBundle:Default:message.html.twig',array('message' => $DBerror));
				}else{
					return $this->render('TTmemberBundle:Default:message.html.twig',array('message' => $message));
				}
			}
			return $this->render('TTmemberBundle:Default:message.html.twig',array('message' => $existingSchool));

		}
	}
	
	/*
	* this function add a new member into the database
	*/
	public function addMemberAction(){
		$memberName = $_POST["memberName"];
		$emailAddress = $_POST["email"];
		$school = $_POST["school"];
		$DBerror = "The member can't be created.";
		$message = "The member has been created.";
		$existingMemberSchool = "This member already exists in this school.";
		
		// if the fields are not empty
		if(!empty($memberName) and !empty($emailAddress) and !empty($school)){
			
			// retrieve the school entered by the user
			$schoolObject = $this->selectSchool($school);

			// check that the member doesn't exist for this school in the database
			if (!$this->checkMember($memberName,$school)) {
				// create the new member
				$em = $this->getDoctrine()->getManager();
				$schoolObject = $em->getRepository('TTmemberBundle:Schools')->findOneBy(array('name' => $school));
				$member = new Members();
				$member->setName($memberName);
				$member->setMailaddress($emailAddress);
				$member->addSchool($schoolObject);
				$schoolObject -> addMember($member);
				$em->merge($schoolObject);
				$em->flush();
				$em->persist($member);
				$em->flush();
				
				// check if the member has been successfully created
				if (!$this->checkMember($memberName,$school)) {
					return $this->render('TTmemberBundle:Default:message.html.twig',array('message' => $DBerror));
				}else{
					return $this->render('TTmemberBundle:Default:message.html.twig',array('message' => $message));
				}
			}
			return $this->render('TTmemberBundle:Default:message.html.twig',array('message' => $existingMemberSchool));

		}
	}
	
	/*
	* this function displays the members of a specific school
	*/
	public function searchMembersAction(){
		$schoolName = $_POST["school"];
		// retrieve the members of a specific school
		$members = $this->searchMembers($schoolName);
		$message = "No member for this school;";
		if(empty($members)){
			return $this->render('TTmemberBundle:Default:message.html.twig',array('message' => $message));
		}else{
			return $this->render('TTmemberBundle:Default:viewMembers.html.twig',array('members' => $members));
		}
	}
	
	/*
	* This function retrieves a specific school
	* it takes a string as input
	* returns a school object
	*/
	private function selectSchool($name){
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery('SELECT s FROM TTmemberBundle:Schools s
		WHERE s.name = :name')->setParameter('name', $name);
		$school = $query->getResult();
		return $school;
	}

	
	/*
	* This function checks that a member is unique for a specific school
	* it takes 2 strings as input
	* returns true if the member is already existing, false otherwise
	*/
	private function checkMember($member,$school){
		$em = $this->getDoctrine()->getManager();
		$schools = $this->selectSchool($school);
		$membersArray = $schools[0]->getMembers();
		for($i=0; $i<sizeof($membersArray); $i++){
			if($membersArray[$i]->getName() == $member){
				return true;
			}
		}	
		return false;
	}
	
	/*
	* This function retrieves all schools
	* returns school objects
	*/
	private function selectAllSchools(){
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery('SELECT s FROM TTmemberBundle:Schools s
		');
		$schools = $query->getResult();
		return $schools;	
	}
	
	
	/*
	* This function retrieves the members of a specific school
	* it takes a string as input
	* returns members object
	*/
	private function searchMembers($schoolName){
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery('SELECT s FROM TTmemberBundle:Schools s
		WHERE s.name = :name')->setParameter('name', $schoolName);
		$schools = $query->getResult();
		$members = $schools[0]->getMembers();
		return $members;	
	}
		
}

		
