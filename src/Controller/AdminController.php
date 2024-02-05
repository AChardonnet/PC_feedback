<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Feedback;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/dashboard', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager)
    {
        $nbCourses = $entityManager->getRepository(Course::class)->nbCourse();
        $nbFeedbacks = $entityManager->getRepository(Feedback::class)->nbFeedback();
        $nbUsers = $entityManager->getRepository(User::class)->nbUser();
        $courses = $entityManager->getRepository(Course::class)->findAllAlphabetically();
        return $this->render('admin/dashboard.html.twig', [
            'nbCourses' => $nbCourses[0][1],
            'nbUsers' => $nbUsers[0][1],
            'nbFeedbacks' => $nbFeedbacks[0][1],
            'courses' => $courses,
        ]);
    }

    #[Route('/addCourse', name: 'admin_addCourse')]
    public function addCourse()
    {
        return $this->render('admin/addCourse.html.twig');
    }
}
