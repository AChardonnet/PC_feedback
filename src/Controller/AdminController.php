<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Feedback;
use App\Entity\User;
use App\Form\AdminPromoteType;
use App\Form\CourseType;
use App\Form\ValidatorType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function PHPUnit\Framework\isEmpty;

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
        $invalidFeedbacks = $entityManager->getRepository(Feedback::class)->invalidFeedbacks();

        return $this->render('admin/dashboard.html.twig', [
            'nbCourses' => $nbCourses[0][1],
            'nbUsers' => $nbUsers[0][1],
            'nbFeedbacks' => $nbFeedbacks[0][1],
            'courses' => $courses,
            'invalidFeedbacks' => $invalidFeedbacks,
        ]);
    }

    #[Route('/addCourse', name: 'admin_addCourse')]
    public function addCourse(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $managerRegistry)
    {
        $courses = $entityManager->getRepository(Course::class)->findAllAlphabetically();
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $managerRegistry->getManager();
            $manager->persist($course);
            $manager->flush();
            return $this->redirectToRoute('admin_addCourse');
        }
        return $this->render('admin/addCourse.html.twig', [
            'form' => $form,
            'courses' => $courses,
        ]);
    }

    #[Route('/promotion', name: 'admin_promote')]
    public function promote(Request $request, EntityManagerInterface $entityManager)
    {
        $promote = new \stdClass();
        $promote->user = null;
        $promote->submit = null;

        $form = $this->createForm(AdminPromoteType::class, $promote);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $promote->user;
            $user->setRoles(['ROLE_ADMIN']);

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('admin_list');
        }

        return $this->render('admin/promote.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/demote/{id}', name: 'admin_demote')]
    public function demote(int $id, EntityManagerInterface $entityManager)
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $roles = $user->getRoles();
        $newRoles = array_diff($roles, ['ROLE_ADMIN']);
        if (isEmpty($newRoles)) {
            $newRoles = ['ROLE_USER'];
        }
        $user->setRoles($newRoles);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_list');
    }

    #[Route('/demoteM/{id}', name: 'admin_demoteM')]
    public function demoteM(int $id, EntityManagerInterface $entityManager)
    {
        $user = $entityManager->getRepository(User::class)->find($id);
        $roles = $user->getRoles();
        $newRoles = array_diff($roles, ['ROLE_MODERATOR']);
        if (isEmpty($newRoles)) {
            $newRoles = ['ROLE_USER'];
        }
        $user->setRoles($newRoles);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('admin_listM');
    }

    #[Route('/list', name: 'admin_list')]
    public function adminList(EntityManagerInterface $entityManager)
    {
        return $this->render('admin/list.html.twig', [
            'admins' => $entityManager->getRepository(User::class)->findByRole('%"ROLE_ADMIN"%'),
        ]);
    }

    #[Route('/listM', name: 'admin_listM')]
    public function adminListM(EntityManagerInterface $entityManager)
    {
        return $this->render('admin/listM.html.twig', [
            'moderators' => $entityManager->getRepository(User::class)->findByRole('%"ROLE_MODERATOR"%'),
        ]);
    }

    #[Route('/moderator-promotion', name: 'admin_newModerator')]
    public function newModerator(Request $request, EntityManagerInterface $entityManager)
    {
        $promote = new \stdClass();
        $promote->user = null;
        $promote->submit = null;

        $form = $this->createForm(AdminPromoteType::class, $promote);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $promote->user;
            $user->setRoles(['ROLE_MODERATOR']);

            $entityManager->persist($user);
            $entityManager->flush();
            return $this->redirectToRoute('admin_listM');
        }

        return $this->render('admin/promoteM.html.twig', [
            'form' => $form,
        ]);
    }
}
