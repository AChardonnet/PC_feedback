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
class CourseController extends AbstractController
{
    #[Route('/courses', name: 'course_courses')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('course/index.html.twig', [
            'courses' => $entityManager->getRepository(Course::class)->findAllAlphabetically()
        ]);
    }

    #[Route('/course/{id}', name: 'course_course')]
    public function course(EntityManagerInterface $entityManager, int $id): Response
    {
        $course = $entityManager->getRepository(Course::class)->find($id);
        $feedbacks = $entityManager->getRepository(Feedback::class)->findAllOf($course);
        $scores = $entityManager->getRepository(Course::class)->getScores($id);
        return $this->render('course/course.html.twig', [
            'course' => $course,
            'feedbacks' => $feedbacks,
            'scores' => $scores
        ]);
    }
}
