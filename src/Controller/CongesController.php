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
use App\Form\EmployeType;
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
	private $repository;

	public function __construct(CongesRepository $repository) {
		$this->repository = $repository;
	}




	// Route vers gestion de congés
    /**
     * @Route("/gest_conges", name="conges_show")
     */
    public function conges(): Response
    {
        $conges = $this->repository->findAll();
    	return $this->render('personnel/gest_conges.html.twig', [
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

    // Changer l'etat du demande
    /**
     * @Route("/gest_conges/editer/{id}", name="valid_conges")
     * @Method({"GET","POST"})
     */
    public function change(Request $request, $id) {
        $conge = new Conges();

        $conge = $this->getDoctrine()->getRepository(Conges::class)->find($id);

        $change = $this->createFormBuilder($conge)
                     ->add('etat', TextType::class, array(
                        'required' => true,
                        'attr' => array('class' => 'form-control')
                     ))

                     ->add('save', SubmitType::class, [
                        
                        'label' => 'Soumettre'
                     ])
                     ->getForm();

        $change->handleRequest($request);

        if($change->isSubmitted() && $change->isValid()) {
            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
        }
        return $this->render('conges/valider_conge.html.twig', array(
            'change' => $change->createView()
        ));
    }
}
