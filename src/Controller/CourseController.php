<?php

namespace App\Controller;

use App\Entity\Course;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CourseController extends AbstractController
{
    #[Route('/courses', name: 'app_courses')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('course/index.html.twig', [
            'courses' => $entityManager->getRepository(Course::class)->findAllAlphabetically()
        ]);
    }

    #[Route('/course/{id}', name: 'app_course')]
    public function course(EntityManagerInterface $entityManager, int $id): Response
    {
        return $this->render('course/course.html.twig', [
            'course' => $entityManager->getRepository(Course::class)->find($id)
        ]);
    }

    #[Route('/test')]
    public function test(): RedirectResponse
    {
        return $this->redirectToRoute('app_course', ['id' => 2]);
    }
}
