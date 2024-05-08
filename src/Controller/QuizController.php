<?php

namespace App\Controller;
use App\Entity\QuizUtilisateur;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Twig\Environment;
use Twig\TwigFunction;
use Dompdf\Dompdf;
use Dompdf\Options;









use App\Entity\Quiz;
use App\Entity\WalletQuiz;

use Twig\Extension\AbstractExtension;


use App\Form\QuizType;
use App\Repository\QuizRepository;

use App\Repository\EtablissementRepository;
use App\Repository\QuizUtilisateurRepository;

use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;


use App\Repository\WalletQuizRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\WalletRepository;

use Symfony\Component\HttpFoundation\JsonResponse;








class QuizController extends AbstractController
{
   
    private $twig;

  

   
    #[Route('/quiz', name: 'app_quiz_index', methods: ['GET'])]
    public function index(QuizRepository $quizRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $queryBuilder = $quizRepository->createQueryBuilder('q');
        
        // Récupérer la difficulté sélectionnée dans la liste déroulante
        $selectedDifficulty = $request->query->get('difficulty');
        
        // Récupérer la liste des quiz filtrés ou non
        if ($selectedDifficulty) {
            $queryBuilder->andWhere('q.description LIKE :keyword')
                         ->setParameter('keyword', '%'.$selectedDifficulty.'%');
        }
        
        // Exécuter la requête avec les filtres
        $allQuizzes = $queryBuilder->getQuery()->getResult();
        
        // Paginer les résultats
        $quizzes = $paginator->paginate(
            $allQuizzes, // Les données à paginer
            $request->query->getInt('page', 1), // Le numéro de la page, par défaut 1
            4// Le nombre d'éléments par page
        );
        
        return $this->render('quiz/index.html.twig', [
            'quizzes' => $quizzes,
            'selectedDifficulty' => $selectedDifficulty, // Passer la variable selectedDifficulty au template
        ]);
    }

   
    #[Route('/quizAchete', name: 'app_quiz_achete')]
    public function quizAchete(Request $request, WalletQuizRepository $walletQuizRepository, QuizRepository $quizRepository, QuizUtilisateurRepository $quizUtilisateurRepository, EtablissementRepository $etablissementRepository, WalletRepository $walletRepository, SessionInterface $session): Response
    {
        // Récupérer le code de l'établissement saisi par l'utilisateur depuis la requête HTTP
        $codeEtablissement = $request->query->get('code_etablissement');

        // Si le code de l'établissement est fourni, récupérer l'ID de l'établissement associé
        if ($codeEtablissement) {
            $idEtablissement = $etablissementRepository->getIDetablissementByCodeEtablissement($codeEtablissement);

            // Si l'ID de l'établissement est trouvé, récupérer l'ID du portefeuille associé
            if ($idEtablissement) {
                $walletId = $walletRepository->getIDwalletbyIDEtablissement($idEtablissement);

                // Récupérer les quiz associés au portefeuille
                $walletQuizzes = $walletQuizRepository->findBy(['id_wallet' => $walletId]);

                $quizzes = [];
                foreach ($walletQuizzes as $walletQuiz) {
                    // Récupérer l'objet Quiz associé à chaque WalletQuiz
                    $quiz = $walletQuiz->getQuiz($quizRepository);
                    if ($quiz) {
                        $quizzes[] = $quiz;
                    }
                }
            } else {
                // Si aucun établissement correspondant n'est trouvé, afficher un message d'erreur
                $message = "Aucun établissement trouvé pour le code saisi.";
                $session->set('message', $message);
                return $this->redirectToRoute('app_quiz_achete');
            }
        } 
       

        // Récupérer le message de la variable de session
        $message = $session->get('message');
        // Effacer le message de la variable de session pour qu'il ne s'affiche pas à nouveau lors du rechargement de la page
        $session->remove('message');
        
        


        return $this->render('quiz/quizAchete.html.twig', [
            'quizzes' => $quizzes ?? [],
            'walletQuizzes' => $walletQuizzes ?? [],
            'quizRepository' => $quizRepository,
            'quizUtilisateurRepository' => $quizUtilisateurRepository,
            'message' => $message, // Passer le message à la vue
        ]);
    }


    private $session;
    public $idUtilisateurConnecte;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        $this->idUtilisateurConnecte = $this->session->get('id_utilisateur');
        $this->idWalletConnecte = $this->session->get('id_wallet');
    }


#[Route('/quiz/passer/{id}', name: 'app_passer_quiz', methods: ['GET', 'POST'])]
public function passerQuiz(Quiz $quiz, QuestionRepository $questionRepository, Request $request, EntityManagerInterface $entityManager, QuizUtilisateurRepository $quizUtilisateurRepository, SessionInterface $session): Response
{
    $utilisateurId = $this->idUtilisateurConnecte;
    $tempsRestant = 20;

    // Vérifier si le formulaire a été soumis
    if ($request->isMethod('POST')) {
        // Vérifier si le temps est écoulé
        if ($request->request->get('tempsRestant') <= 0) {
            // Vérifier si l'utilisateur a déjà passé le quiz
            $quizUtilisateur = $quizUtilisateurRepository->findOneBy([
                'utilisateurId' => $utilisateurId,
                'id_quiz' => $quiz->getId(),
            ]);
            // Si l'utilisateur a déjà passé le quiz, rediriger vers la page d'accueil
            if ($quizUtilisateur) {
                // Définir le message de la variable de session pour indiquer que le temps est écoulé
                $session->set('message', 'Temps écoulé.');
                return $this->redirectToRoute('app_quiz_achete');
            } else {
                // Enregistrer le score comme 0 dans la base de données
                $quizUtilisateur = new QuizUtilisateur();
                $quizUtilisateur->setUtilisateurId($utilisateurId);
                $quizUtilisateur->setid_quiz($quiz->getId());
                $quizUtilisateur->setScore(0);
    
                $entityManager->persist($quizUtilisateur);
                $entityManager->flush();
    
                // Définir le message de la variable de session pour indiquer que le temps est écoulé
                $session->set('message', 'Temps écoulé.');
    
                // Rediriger vers la page app_quiz_achete après un court délai
                return $this->redirectToRoute('app_quiz_achete'); // Redirection après 5 secondes
            }
        }
        $questions = $questionRepository->findBy(['id_quiz' => $quiz->getId()]);

    // Vérifier si l'utilisateur a déjà passé le quiz
    $quizUtilisateur = $quizUtilisateurRepository->findOneBy([
        'utilisateurId' => $utilisateurId,
        'id_quiz' => $quiz->getId(),
    ]);

        // Calculer le score
        $score = $this->calculerScore($questions, $request->request->all());

        // Enregistrer le score dans la base de données
        $quizUtilisateur = new QuizUtilisateur();
        $quizUtilisateur->setUtilisateurId($utilisateurId);
        $quizUtilisateur->setid_quiz($quiz->getId());
        $quizUtilisateur->setScore($score);

        $entityManager->persist($quizUtilisateur);
        $entityManager->flush();

        if ($tempsRestant === 10) {
            $this->notificationService->sendNotification($utilisateurId, 'Dépêchez-vous, il vous reste 10 secondes.');
        }

        // Générer le PDF avec le bilan du quiz
        $appreciation = $this->determinerAppreciation($score);
        $pdfContent = $this->genererPdf($quiz, $questions, $score, $appreciation);

        // Enregistrer le PDF sur le bureau
        $outputFilePath = 'C:/Users/digoh/InnoHire-symfony/public/pdf-quiz/quiz_bilan.pdf';
        
        file_put_contents($outputFilePath, $pdfContent);

        // Définir le message de la variable de session pour indiquer que le quiz a été passé avec succès
        $session->set('message', 'Quiz passé avec succès.');

        return $this->redirectToRoute('app_quiz_achete');
    }

    $questions = $questionRepository->findBy(['id_quiz' => $quiz->getId()]);

    // Vérifier si l'utilisateur a déjà passé le quiz
    $quizUtilisateur = $quizUtilisateurRepository->findOneBy([
        'utilisateurId' => $utilisateurId,
        'id_quiz' => $quiz->getId(),
    ]);

    return $this->render('quiz/passerQuiz.html.twig', [
        'quiz' => $quiz,
        'questions' => $questions,
        'utilisateurId' => $utilisateurId,
        'tempsRestant' => $tempsRestant,
        'quizUtilisateur' => $quizUtilisateur,
    ]);
}
private function genererPdf(Quiz $quiz, array $questions, int $score, string $appreciation): string
{
    // Créer le contenu HTML du PDF avec les styles CSS
    $htmlContent = '<style>';
    $htmlContent .= 'h1 { color: #333; }';
    $htmlContent .= 'p { font-size: 16px; }';
    $htmlContent .= 'ul { list-style-type: none; }';
    $htmlContent .= 'li { margin-bottom: 5px; }';
    $htmlContent .= '</style>';

    $htmlContent .= '<h1>Récapitulatif du Quiz</h1>';
    $htmlContent .= '<p>Score : ' . $score . '</p>';
    $htmlContent .= '<p>Appréciation : ' . $appreciation . '</p>';
    $htmlContent .= '<h2>Questions et Réponses Correctes</h2>';
    foreach ($questions as $question) {
        // Afficher la question
        $htmlContent .= "<li>{$question->getQuestion()}</li>";

        // Afficher les choix (qui est une chaîne de caractères)
        $htmlContent .= "<ul>";
        // Séparer les choix par une virgule
        $choixArray = explode(',', $question->getChoix());
        foreach ($choixArray as $choix) {
            $htmlContent .= "<li>{$choix}</li>";
        }
        $htmlContent .= "</ul>";

        // Afficher la réponse correcte
        $htmlContent .= "<p>Réponse correcte : {$question->getReponseCorrecte()}</p>";
    }

    // Créer une instance de Dompdf
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $dompdf = new Dompdf($options);

    // Charger le contenu HTML dans Dompdf
    $dompdf->loadHtml($htmlContent);

    // Rendre le PDF
    $dompdf->render();

    // Retourner le contenu du PDF en tant que chaîne de caractères
    return $dompdf->output();
}
    private function determinerAppreciation(int $score): string
    {
        if ($score >= 0 && $score <= 3) {
            return 'Mal';
        } elseif ($score >= 4 && $score <= 6) {
            return 'Pas Mal';
        } elseif ($score >= 7 && $score <= 9) {
            return 'Bien';
        } elseif ($score === 10) {
            return 'Excellent';
        } else {
            return 'Appréciation indéterminée';
        }
    }
private function calculerScore(array $questions, array $reponses): int
{
    $score = 0;
    foreach ($questions as $question) {
        $questionId = $question->getId();
        if (isset($reponses["reponse_$questionId"]) && strtolower($reponses["reponse_$questionId"]) === strtolower($question->getReponseCorrecte())) {
            $score++;
        }
    }

    return $score;
}
   
    
    #[Route('/quizDispo', name: 'quiz_dispo')]
    public function quizDispo(QuizRepository $quizRepository , Environment $twig , WalletQuizRepository $walletQuizRepository): Response
    {
        
        $quizDisponibles = $quizRepository->findAll();
        $twig->addFunction(new TwigFunction('quizDejaAchete', [$this, 'quizDejaAchete']));


        
        return $this->render('quiz/quizDispo.html.twig', [
            'quizzes' => $quizDisponibles,
            'walletQuizRepository' => $walletQuizRepository, // Passer le repository au modèle Twig

        ]);
    }
    public function quizDejaAchete(Request $request, $quizId, $walletQuizRepository)
    {
        // Récupérer l'id du portefeuille associé à l'utilisateur (à remplacer par la méthode appropriée pour récupérer l'ID du portefeuille de l'utilisateur connecté)
        $walletId = $this->idWalletConnecte;

        // Vérifier si l'association quiz-wallet existe déjà dans la table wallet_quiz
        $existingAssociation = $walletQuizRepository->findOneBy(['id_quiz' => $quizId, 'id_wallet' => $walletId]);

        // Si l'association existe déjà, retourner true, sinon retourner false
        return $existingAssociation ? true : false;
    }

    #[Route('/quiz/achat/{id}', name: 'app_quiz_achat', methods: ['GET'])]
public function acheterQuiz(
    Request $request,
    Quiz $quiz,
    WalletRepository $walletRepository,
    EntityManagerInterface $entityManager,
    WalletQuizRepository $walletQuizRepository
): Response {
    // Récupérer l'id du portefeuille associé à l'utilisateur (à remplacer par la méthode appropriée pour récupérer l'ID du portefeuille de l'utilisateur connecté)
    $walletId = $this->idWalletConnecte;
    
    // Récupérer le portefeuille associé à l'utilisateur
    $wallet = $walletRepository->find($walletId);

    // Vérifier si le portefeuille existe
    if (!$wallet) {
        $this->addFlash('error', 'Portefeuille non trouvé');
        return $this->redirectToRoute('quiz_dispo');
    }

    // Vérifier si le solde est suffisant pour l'achat
    if ($wallet->getBalance() < $quiz->getPrixQuiz()) {
        $this->addFlash('error_' . $quiz->getId(), 'solde insuffisant');

        return $this->redirectToRoute('quiz_dispo');
    }

    // Vérifier si l'association quiz-wallet existe déjà dans la table wallet_quiz
    $existingAssociation = $walletQuizRepository->findOneBy(['id_quiz' => $quiz->getId(), 'id_wallet' => $walletId]);

    // Si l'association existe déjà, retourner un message d'erreur
    if ($existingAssociation) {
        $this->addFlash('error', 'Quiz déjà acheté');
        return $this->redirectToRoute('quiz_dispo');
    }

    // Déduire le prix du quiz du solde du portefeuille
    $newBalance = $wallet->getBalance() - $quiz->getPrixQuiz();
    $wallet->setBalance($newBalance);
    $entityManager->flush();

    // Créer une nouvelle entrée dans la table wallet_quiz pour enregistrer l'association quiz-wallet
    $walletQuiz = new WalletQuiz();
    $walletQuiz->setIdQuiz($quiz->getId());
    $walletQuiz->setIdWallet($walletId);
    $entityManager->persist($walletQuiz);
    $entityManager->flush();

    $this->addFlash('success', 'Quiz acheté avec succès');
    // Rediriger vers la même page pour afficher les messages
    return $this->redirectToRoute('quiz_dispo');
}
    #[Route('/quiz/new', name: 'app_quiz_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quiz);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/quiz/{id}', name: 'app_quiz_show', methods: ['GET'])]
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', [
            'quiz' => $quiz,
        ]);
    }

    #[Route('/quiz/{id}/edit', name: 'app_quiz_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Le quiz a été mis à jour avec succès.');
            return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
        }

        // Si le formulaire est soumis mais invalide, il affiche des erreurs de validation
        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('error', 'Veuillez corriger les erreurs dans le formulaire.');
        }

        return $this->render('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }
   
    #[Route('/quiz/{id}', name: 'app_quiz_delete', methods: ['POST'])]
    public function delete(Request $request, Quiz $quiz, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {
            $entityManager->remove($quiz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quiz_index', [], Response::HTTP_SEE_OTHER);
    }
}
