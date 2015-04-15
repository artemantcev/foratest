<?php

namespace Forasoft\ForatestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Question
 */
class Result
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $idTest;

    /**
     * @var string
     */
    private $answers;

    /**
     * @var string
     */
    private $user;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getAnswers()
    {
        return $this->answers;
    }

    public function setAnswers($answers)
    {
        $this->answers = $answers;
        return $this;
    }

    public function getIdTest()
    {
        return $this->idTest;
    }

    public function setIdTest($testid)
    {
        $this->idTest = $testid;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }
}