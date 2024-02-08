<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Feedback;
use App\Entity\User;
use App\Form\FeedbackType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
        $feedbacks = $entityManager->getRepository(Feedback::class)->findAllValidOf($course);
        $scores = $entityManager->getRepository(Course::class)->getScores($id);
        return $this->render('course/course.html.twig', [
            'course' => $course,
            'feedbacks' => $feedbacks,
            'scores' => $scores
        ]);
    }

    #[Route('/feedback/{id}', name: 'course_feedback')]
    public function comment(Request $request, int $id, ManagerRegistry $managerRegistry, EntityManagerInterface $entityManager)
    {
        $course = $entityManager->getRepository(Course::class)->find($id);
        $user = $this->getUser();
        $feedback = new Feedback();
        $form = $this->createForm(FeedbackType::class, $feedback);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $feedback->setDate(new \DateTime())
                ->setValid(false)
                ->setAuthor($user)
                ->setCourse($course);

            $manager = $managerRegistry->getManager();
            $manager->persist($feedback);
            $manager->flush();
            return $this->redirectToRoute('course_course', ["id" => $id]);
        }

        return $this->render('course/feedback.html.twig', [
            'form' => $form,
            'course' => $course,
            ]);
    }
}
