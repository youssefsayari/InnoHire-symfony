<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Utilisateur;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCodeBundle\Response\QrCodeResponse;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Endroid\QrCode\Factory\QrCodeFactoryInterface;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Color\Color;


#[Route('/reclamation')]
class ReclamationController extends AbstractController
{
    
    #[Route('/', name: 'app_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
        ]);
    }

    ///////////////////////////////////////// Front //////////////////////////////////////
    #[Route('/front', name: 'app_reclamation_indexfront', methods: ['GET'])]
    public function indexfront(ReclamationRepository $reclamationRepository): Response
    {
        return $this->render('reclamation/indexfront.html.twig', [
            'reclamations' => $reclamationRepository->findByUserId(3),
        ]);
    }
    ////////////////////////////////////////////////////////////////////////////////////

    #[Route('/new', name: 'app_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        
        // Set the default value for the date field as a DateTime object
        $reclamation->setDate(new \DateTime());
    
        // Set the id_utilisateur to 1
        $reclamation->setUtilisateur($this->getDoctrine()->getRepository(Utilisateur::class)->find(3));
        
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    /////////////////////////////////////////New Front //////////////////////////////////////    
    #[Route('/newfront', name: 'app_reclamation_newfront', methods: ['GET', 'POST'])]
    public function newfront(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        
        // Set the default value for the date field as a DateTime object
        $reclamation->setDate(new \DateTime());
    
        // Set the id_utilisateur to 1
        $reclamation->setUtilisateur($this->getDoctrine()->getRepository(Utilisateur::class)->find(3));
        
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reclamation);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_reclamation_indexfront', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->renderForm('reclamation/newfront.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
        ////////////////////////////////////////////////////////////////////////////////////
        #[Route('/{id}', name: 'app_reclamation_show', methods: ['GET'])]
        public function show(Reclamation $reclamation, BuilderInterface $qrCodeBuilder, ParameterBagInterface $parameterBag): Response
        {
            // Format the data string to include claim details
            $claimData = sprintf(
                "Claim ID: %d\nType: %s\nTitre: %s\nDescription: %s\nDate: %s\nStatus: %s",
                $reclamation->getId(),
                $reclamation->getType(),
                $reclamation->getTitre(),
                $reclamation->getDescription(),
                $reclamation->getDate()->format('Y-m-d H:i:s'),
                $reclamation->getStatus() == 0 ? 'untreated' : 'treated'
            );

           // dd( $claimData);
        
            // Generate QR code with claim data
            $qrCode = $qrCodeBuilder->create($claimData)
                ->backgroundColor(new Color(0, 0, 0, 127)) // Transparent background
                ->size(200) // Adjust the size as needed
                ->build();
        
            // Get the directory where the QR codes will be stored
            $qrCodeDirectory = $parameterBag->get('kernel.project_dir') . '/public/qrcodes/';
        
            // Ensure the directory exists
            if (!is_dir($qrCodeDirectory)) {
                mkdir($qrCodeDirectory, 0777, true);
            }
        
            // Generate a unique filename for the QR code image
            $qrCodeFilename = 'qrcode_' . uniqid() . '.png';
        
            // Save the QR code image to the directory
            $qrCode->saveToFile($qrCodeDirectory . $qrCodeFilename);
        
            // Get the relative path to the QR code image
            $qrCodePath = '/qrcodes/' . $qrCodeFilename;
        
            return $this->render('reclamation/show.html.twig', [
                'reclamation' => $reclamation,
                'qrCodePath' => $qrCodePath,
            ]);
        }
        
        

    #[Route('/{id}/edit', name: 'app_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }
//////////////////////////////////////        Edit Front       //////////////////////////////////////////////

#[Route('/{id}/editfront', name: 'app_reclamation_editfront', methods: ['GET', 'POST'])]
public function editfront(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(ReclamationType::class, $reclamation);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_reclamation_indexfront', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('reclamation/editfront.html.twig', [
        'reclamation' => $reclamation,
        'form' => $form,
    ]);
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////

    #[Route('/{id}', name: 'app_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }

    ////////////////////////////////////////   FRONT   /////////////////////////////////////////////////////////////////////////////////////////
    #[Route('/{id}/delete', name: 'app_reclamation_deletefront', methods: ['POST'])]
    public function deletefront(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('deletefront' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reclamation_indexfront', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/{id}/download-pdf', name: 'download_reclamation_pdf', methods: ['GET'])]
public function downloadPdf(Reclamation $reclamation): Response
{
    // Render the Twig template to HTML
    $html = $this->renderView('reclamation/pdf_template.html.twig', [
        'reclamation' => $reclamation,
    ]);

    // Configure Dompdf options
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);

    // Instantiate Dompdf
    $dompdf = new Dompdf($options);

    // Load HTML content
    $dompdf->loadHtml($html);

    // Set paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF (inline or as attachment)
    return new Response($dompdf->output(), Response::HTTP_OK, [
        'Content-Type' => 'application/pdf',
    ]);
}


#[Route('/{id}/generate-qrcode', name: 'generate_qrcode', methods: ['GET'])]
public function generateQRCode(Reclamation $reclamation): Response
{
    // Generate the QR code content (you can customize this according to your needs)
    $qrCodeContent = $reclamation->getId(); // For example, you can use the ID of the reclamation
    //dd( $qrCodeContent);

    // Create the QR code instance
    $qrCode = Builder::create()
        ->writer(new PngWriter())
        ->data($qrCodeContent)
        ->build();

    // Get the data URI of the QR code image
    $dataUri = $qrCode->getDataUri();

    // Return the QR code image as a response
    return new Response($dataUri, Response::HTTP_OK, [
        'Content-Type' => 'image/png',
    ]);
}
}
