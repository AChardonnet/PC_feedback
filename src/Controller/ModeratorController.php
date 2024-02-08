<?php

namespace App\Controller;

use App\Entity\Feedback;
use App\Form\ValidatorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_MODERATOR')]
#[Route('/moderator')]
class ModeratorController extends AbstractController
{
    #[Route('/feedback/{id}', name: 'moderator_feedback')]
    public function validateFeedback(int $id, EntityManagerInterface $entityManager, Request $request)
    {
        $feedback = $entityManager->getRepository(Feedback::class)->find($id);
        $validator = new \stdClass();
        $validator->validate = false;
        $validator->delete = false;

        $form = $this->createForm(ValidatorType::class, $validator);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dump('form check');
            if ($form->get('validate')->isClicked()) {
                dump('validate');
                $feedback->setValid(true);
            } else {
                $entityManager->remove($feedback);
            }
            dump('flush');
            $entityManager->flush();

            return $this->redirectToRoute('moderator_feedback');
        }

        return $this->render('moderator/validateFeedback.html.twig', [
            'feedback' => $feedback,
            'form' => $form,
        ]);
    }
}
