<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Feedback;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
class HomeController extends AbstractController
{
    #[Route('/home', name: 'home_home')]
    public function index(EntityManagerInterface $em): Response
    {
        $lastFeedbacks = $em->getRepository(Feedback::class)->findLastFeedbacks();
        $bestCourses = $em->getRepository(Feedback::class)->findBestCourseOverall();
        $courses = $em->getRepository(Course::class)->findAllAlphabetically();
        return $this->render('home/index.html.twig', [
            'lastFeedbacks' => $lastFeedbacks,
            'bestCourses' => $bestCourses,
            'courses' => $courses
            ]);
    }
}
