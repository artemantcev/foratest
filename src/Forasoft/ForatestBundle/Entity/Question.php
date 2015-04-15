<?php
namespace Forasoft\ForatestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Question
 */
class Question
{

    private $id; //question id

    private $idTests; //id of the test which question belongs to

    private $description; //question description

    private $type; //type of question (check, radio, field)

    protected $test; //Many-To-One reference to test entity

    protected $answers; //One-To-Many reference to answer entities

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    public function __toString()
    {
        return (string) $this->getType();
    }

    public function getId()
    {
        return $this->id;
    }


    public function getTest()
    {
        return $this->test;
    }

    public function setIdTests($idTests)
    {
        $this->idTests = $idTests;
        return $this;
    }

    public function getIdTests()
    {
        return $this->idTests;
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

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getAnswers()
    {
        return $this->answers;
    }
}