<?php

namespace Forasoft\ForatestBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query as Query;

/**
 * QuestionRepository
 *
 */
class QuestionRepository extends EntityRepository
{

    /* getting the test questions by test id */
    public function getTestQuestionsByTestId($id_test)
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT q.description, t.name FROM Forasoft\ForatestBundle\Entity\Question q
        JOIN Forasoft\ForatestBundle\Entity\Test t WHERE q.idTests=t.id AND t.id=:test_id")
            ->setParameter('test_id',$id_test)
            ->getResult(Query::HYDRATE_ARRAY);

        return $query;
    }

}
