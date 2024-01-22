<?php

namespace App\Controller;

use App\Entity\Feedback;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $lastFeedbacks = $em->getRepository(Feedback::class)->findLastFeedbacks();
        $bestCourses = $em->getRepository(Feedback::class)->findBestCourseOverall();
        $courses = [];
        return $this->render('home/index.html.twig', [
            'lastFeedbacks' => $lastFeedbacks,
            'bestCourses' => $bestCourses,
            'courses' => $courses
            ]);
    }
}
