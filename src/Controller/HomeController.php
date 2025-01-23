<?php

namespace App\Controller;

use App\Document\Stats;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{



    protected $dm;
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();

        $stats = $this->getdb();

        if ($session->has('id')) {
            $session->set('id', -1);
        } else if ($session->has('choices')) {
            $session->set('choices', []);
        } else if ($session->has('quizzes')) {
            $session->set('quizzes', []);
        }



        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'session' => $session,
            'stats' => $stats

        ]);
    }

    public function getdb()
    {
        $repository = $this->dm->getRepository(Stats::class);
        $stats = $repository->findAll();
        return $stats;
    }
}
