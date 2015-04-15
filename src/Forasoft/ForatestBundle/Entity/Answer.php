<?php

namespace Forasoft\ForatestBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Answer
 */
class Answer
{

    protected $id; //answer id

    protected $idQuestions; //id of the question which answer belongs to

    protected $description; //answer description

    protected $isTrue; //correct or incorrect (boolean)

    protected $question; //Many-To-One reference to question object

    public function getId()
    {
        return $this->id;
    }

    public function setIdQuestions($idQuestions)
    {
        $this->idQuestions = $idQuestions;

        return $this;
    }

    public function getIdQuestions()
    {
        return $this->idQuestions;
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

    public function setIsTrue($isTrue)
    {
        $this->isTrue = $isTrue;

        return $this;
    }

    public function getIsTrue()
    {
        return $this->isTrue;
    }
}
