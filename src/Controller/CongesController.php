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

    // Delete form
    /**
     * @Route("/gest_conges/suppr/{}" name="supp_conges")
     * @Method({"GET", "DELETE"})
     */
    /*public function supprimer(Request $request, $id) {
        $conge = $this->getDoctrine()->getRepository(Conges::class)->find($id);

        $form = $this->createDeleteForm($conge);
        if ($request->getMethod() == 'DELETE') {
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($conge);
                $em->flush();


            }
            $response = new Response();
                $response->send();

                return $this->redirectToRoute('conges_show');
        }
    }*/
}
