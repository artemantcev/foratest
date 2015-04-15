<?php

namespace Forasoft\ForatestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Test
 */
class Test
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    protected $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function __toString()
    {
        $this->questions = new ArrayCollection();
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
     * @return Test
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
     * Add questions
     *
     * @param \Forasoft\ForatestBundle\Entity\Question $questions
     * @return Test
     */
    public function addQuestion(\Forasoft\ForatestBundle\Entity\Question $questions)
    {
        $this->questions[] = $questions;

        return $this;
    }

    /**
     * Remove questions
     *
     * @param \Forasoft\ForatestBundle\Entity\Question $questions
     */
    public function removeQuestion(\Forasoft\ForatestBundle\Entity\Question $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }
}
