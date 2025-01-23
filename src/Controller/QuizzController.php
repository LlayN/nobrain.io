<?php

namespace App\Controller;

use App\Document\Stats;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class QuizzController extends AbstractController

{
    public $requestStack;
    public $session;
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack->getCurrentRequest();
        $this->session = $this->requestStack->getSession();
    }

    #[Route('/quizz', name: 'app_quizz')]
    public function index(): Response
    {


        $id = $this->incrementation();
        $this->getData($id);




        return $this->render('quizz/index.html.twig', [
            'quizzes' => $this->session->get("quizzes")->quizzes,
            'id' => $this->session->get('id'),
            'request' => $this->requestStack->request->all(),

        ]);
    }
    public function incrementation(): int
    {
        $this->session->set("id", $this->session->get("id") + 1);
        return $this->session->get("id");;
    }
    public function getData($id)
    {
        if (!$id) {


            $category = $this->requestStack->request->get('category');
            $this->session->set('category', $category);
            $difficulty = $this->requestStack->request->get('difficulty');
            $this->session->set('difficulty', $difficulty);

            $response = file_get_contents("https://quizzapi.jomoreschi.fr/api/v1/quiz?limit=10&category={$category}&difficulty={$difficulty}");



            if ($response == false) {
                return $this->render('home/index.html.twig');
            } else {
                $this->newQuizz($response);
            }
        }
    }

    public function newQuizz($response)
    {


        $quizzes = json_decode($response);
        $this->session->set("quizzes", $quizzes);
        $this->session->set('choices', []);
    }

    public function initScore()
    {
        $goodAnswer = [];
        foreach ($this->session->get('quizzes')->quizzes as $answer) {
            $goodAnswer[] = $answer->answer;
        }
        $result = array_intersect($this->session->get('choices'), $goodAnswer);
        return count($result);
    }

    #[Route('/quizz/resultat', name: 'app_result')]
    public function addResult(DocumentManager $dm): Response
    {
        $score = $this->initScore();


        $stats = new Stats();

        $stats->setName($this->session->get('category'));
        $stats->setScore($score);
        $stats->setAnswers(count($this->session->get("quizzes")->quizzes));
        $dm->persist($stats);
        $dm->flush();

        return $this->render('quizz/result.html.twig', [
            'score' => $score
        ]);
    }
}
