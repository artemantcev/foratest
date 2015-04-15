<?php

namespace Forasoft\ForatestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Forasoft\ForatestBundle\Entity\Test;
use Forasoft\ForatestBundle\Entity\Answer;
use Forasoft\ForatestBundle\Entity\Result;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\FormError;

class TestController extends Controller
{
    public function indexAction()
    {
        /* getting the list of available tests */
        $test = $this->getDoctrine()
            ->getRepository('ForasoftForatestBundle:Test')
            ->getTestNames();

        return $this->render('ForasoftForatestBundle:Test:testlist.html.twig', array(
            'tests' => $test
        ));
    }

    public function showAction(Request $request, $testid)
    {
        $em = $this->getDoctrine();

        $test_one = $em->getRepository('ForasoftForatestBundle:Test')
            ->findOneBy(array('id' => $testid)); //getting the test name from DB

        if (empty($test_one)) {
            throw $this->createNotFoundException("This test doesn't yet exist"); //if testid is incorrect
        }

        $test_name = $test_one->getName();

        $questions = $em->getRepository('ForasoftForatestBundle:Test')
            ->findOneBy(array('id' => $testid))->getQuestions(); //getting the question list from DB

        $data = null;
        $i = 0;

        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('username', 'text', array( /* adding the username field */
            'label' => 'Your name:',
            'constraints' => array(
                new NotBlank(),
                new Length(array('min' => 5, 'max' => 200)),
                new Regex(array('pattern' => '/^[^;]*$/', 'match' => 'false',
                    'message' => 'Symbol ; is not allowed!'))
            )
        ));
        foreach ($questions as $question) { /* adding the username field */
            $question_id = $question->getId();
            $question_description = $question->getDescription();
            if ($question->getType() == 'radio') { //adding the radio questions to form
                $formBuilder->add('radio' . $i, 'entity', array(
                    'class' => 'ForasoftForatestBundle:Answer',
                    'property' => 'description',
                    'multiple' => false,
                    'label' => $question_description,
                    'expanded' => true,
                    'query_builder' => function (EntityRepository $er) use ($question_id) {
                        return $er->createQueryBuilder('u')
                            ->where('u.idQuestions = :id')
                            ->setParameter('id', $question_id);
                    }
                ));
            } elseif ($question->getType() == 'check') { //adding the checkbox questions to form
                $formBuilder->add('check' . $i, 'entity', array(
                    'class' => 'ForasoftForatestBundle:Answer',
                    'property' => 'description',
                    'multiple' => true,
                    'label' => $question_description,
                    'expanded' => true,
                    'query_builder' => function (EntityRepository $er) use ($question_id) {
                        return $er->createQueryBuilder('u')
                            ->where('u.idQuestions = :id')
                            ->setParameter('id', $question_id);
                    }
                ));
            } elseif ($question->getType() == 'field') { //adding the field questions to form
                $formBuilder->add('field' . $i, 'text', array(
                    'label' => $question_description,
                    'constraints' => array(
                        new NotBlank(),
                        new Length(array('min' => 5, 'max' => 200)),
                        new Regex(array('pattern' => '/^[^;]*$/', 'match' => 'false',
                            'message' => 'Symbol ; is not allowed!'))
                    )
                ));
            }
            $i++;
        }
        $formBuilder->add('save', 'submit', array(
            'label' => 'Finish test'
        ));

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        /* checking validity */
        if ($form->isValid()) {

            /* sending user data to application via POST */
            if ($request->isMethod('POST')) {

                $result = new Result();

                $em = $this->getDoctrine()->getManager();
                $result->setIdTest($testid);
                $result->setUser($form['username']->getData());

                $checkbox_err = 0;

                /* getting the answers string */
                $answer_set = $this->convertResults($request, $i, $checkbox_err);

                /* reporting about non-checked checkbox questions,
                i had to do this because of impossibility to use the validator constraints
                with unmapped things */
                if ($checkbox_err == 1) {
                    $form->addError(new FormError('You should check at least one checkbox in multiple questions!'));
                }

                /* if there's no problem with checkbox questions selection
                saving Result object and flushing it into database */
                if ($checkbox_err == 0) {

                    $result->setAnswers($answer_set);
                    $em->persist($result);
                    $em->flush();

                    /* notification if everything is successful */
                    $this->get('session')->getFlashBag()->add(
                        'notice',
                        'Your result has been saved!'
                    );

                    return $this->redirect($this->generateUrl('ForasoftForatestBundle_testlist'));

                }

            } else {
                die('invalid post data');
            }
        }

        return $this->render('ForasoftForatestBundle:Test:showtest.html.twig', array(
            'form' => $form->createView(),
            'test_name' => $test_name
        ));
    }

    private function convertResults(Request $request, $i, &$checkbox_err)     /* method used to convert results into string */
    {
        $answer_set = '';

        $checkbox_num = 1;

        for ($t = 0; $t < $i; $t++) {
            if (isset($request->request->get('form')['field' . $t])) {             /* if it's a field */
                $answer_set .= $request->request->get('form')['field' . $t]. ';'; /* adding the field values to answers set */

            } elseif (isset($request->request->get('form')['radio' . $t]['0'])) { /* if it's a radio */
                $answer_set .= $request->request->get('form')['radio' . $t]. ';'; /* adding the radio values to answers set */

            } elseif (isset($request->request->get('form')['check' . $t]['0'])) { /* if it's a check */
                $answer_set .= $request->request->get('form')['check' . $t]['0']. ';'; /* adding the check values to answers set */

                while (!empty($request->request->get('form')['check' . $t][$checkbox_num])) {
                    $answer_set .= $request->request->get('form')['check' . $t][$checkbox_num] . ';';
                    $checkbox_num++;
                }

                $checkbox_num = 1;

            } else {
                $checkbox_err = 1;
                }
            }
        return $answer_set;
    }
}