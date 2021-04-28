<?php

namespace App\Controller;

use App\Entity\Conges;
use App\Repository\CongesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class CongesController extends AbstractController
{
	/**
	 * @var CongesRepository
	 */
	//private $repository;

	public function __construct(CongesRepository $repository) {
		$this->repository = $repository;
	}

    // Affichage de tous les conges
    /**
     * @Route("/" , name="accueil_show")
     */
    public function index(): Response {

        $conges = $this->repository->findAll();
        return $this->render('personnel/accueil.html.twig', [
            'tab' => 'bord',
            'conges' => $conges
        ]);

    }



	// Route vers gestion de congés
    /**
     * @Route("/gest_conges", name="conges_show")
     */
    public function conges(): Response
    {
        $conges = $this->repository->findAll();
    	return $this->render('personnel/gest_conges.htmL.twig', [
    		'conge' => 'conges',
            'conges' => $conges
    	]);
    }

    // Suppression d'un demande de congé
    /**
     * @Route("/gest_conges/delete/{id}" , name="conge_delete")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id) {
    	$conge = $this->getDoctrine()->getRepository(Conges::class)->find($id);

    	$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->remove($conge);
    	$entityManager->flush();

    	$response = new Response();
    	$response->send();

        return $this->redirectToRoute('conges_show');
    }

    // Action pour la première validation
    /**
     * @Route("/gest_conges/validerOne/{id}", name="valid_one")
     * @Method({"GET","POST"})
     */
    public function change(Request $request, $id) {
        $conge = new Conges();

        $conge = $this->getDoctrine()->getRepository(Conges::class)->find($id);

        $change = $this->createFormBuilder($conge)
                     ->add('etat', TextType::class, array(
                        'required' => true,
                        'attr' => array('class' => 'form-control' , 'hidden' => true ,
                        'value' => 'A valider')
                     ))

                     ->add('save', SubmitType::class, [
                        
                        'label' => 'Soumettre'
                     ])
                     ->getForm();

        $change->handleRequest($request);

        if($change->isSubmitted() && $change->isValid()) {
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('conges_show');
        }
        return $this->render('conges/valider_conge.html.twig', array(
            'change' => $change->createView()
        ));
    }

    // Action pour la deuxième validation
    /**
     * @Route("/gest_conges/validerTwo/{id}", name="valid_two")
     */
    public function changer(Request $request, $id) {
        $conge = new Conges();

        $conge = $this->getDoctrine()->getRepository(Conges::class)->find($id);

        $changer = $this->createFormBuilder($conge)
                     ->add('etat', TextType::class, array(
                        'required' => true,
                        'attr' => array('class' => 'form-control' , 'hidden' => true ,
                        'value' => 'Validé')
                     ))

                     ->add('save', SubmitType::class, [
                        
                        'label' => 'Soumettre'
                     ])
                     ->getForm();

        $changer->handleRequest($request);

        if($changer->isSubmitted() && $changer->isValid()) {
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('conges_show');
        }
        return $this->render('conges/conge_valid_final.html.twig', array(
            'changer' => $changer->createView()
        ));
    }


    // Affichange conge à valider
    /**
     * @Route("/conges/final/{etat}", name="con_show")
     * @Method({"POST"})
     */
    public function affichFinal($etat) {
        $conge = $this->getDoctrine()->getRepository(Conges::class)->afficherCongesAvalider($etat);

        return $this->render('conges/conge_valid_final.html.twig', [
            'conge' => $conge
        ]);
    }


}
