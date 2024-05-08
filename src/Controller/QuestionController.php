<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/question')]
class QuestionController extends AbstractController
{
    #[Route('/', name: 'app_question_index', methods: ['GET'])]
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_question_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $question = new Question();
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question/new.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }
    #[Route('/questions/sort/{sort}', name: 'question_sort', methods: ['GET'])]
    public function sort(string $sort, QuestionRepository $questionRepository): Response
    {
        // Vérifiez si le tri est par ordre ascendant ou descendant
        if ($sort === 'asc') {
            $sortOrder = 'ASC';
        } elseif ($sort === 'desc') {
            $sortOrder = 'DESC';
        } else {
            // Valeur par défaut pour le tri
            $sortOrder = 'ASC';
        }
    
        // Trier les questions par code quiz
        $questions = $questionRepository->findBy([], ['id_quiz' => $sortOrder]);
    
        return $this->render('question/index.html.twig', [
            'questions' => $questions,
        ]);
    }

    #[Route('/search', name: 'question_search', methods: ['GET'])]
public function search(Request $request, QuestionRepository $questionRepository): Response
{
    // Récupérer le code quiz à rechercher
    $codeQuiz = $request->query->get('code_quiz');

    // Rechercher les questions par code quiz
    $questions = $questionRepository->findByCodeQuiz($codeQuiz);

    return $this->render('question/index.html.twig', [
        'questions' => $questions,
    ]);
}
    #[Route('/{id}', name: 'app_question_show', methods: ['GET'])]
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_question_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('question/edit.html.twig', [
            'question' => $question,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_question_delete', methods: ['POST'])]
    public function delete(Request $request, Question $question, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_question_index', [], Response::HTTP_SEE_OTHER);
    }
}
