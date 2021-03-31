<?php

namespace App\Controller;


use App\Entity\Employe;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonnelController extends AbstractController
{	


	/**
	 * @var EmployeRepository
	 */
	private $repository;

	public function __construct(EmployeRepository $repository) {
		$this->repository = $repository;
	}

	// Route vers accueil
    /**
     * @Route("/", name="accueil_show")
     */
    public function index(): Response
    {
        return $this->render('personnel/accueil.html.twig', [
            'controller_name' => 'PersonnelController',
            'tab' => 'bord'

        ]);
    }

    // Route vers gestion personnel
    /**
     * @Route("/gest_personnel", name="personnel_show")
     */
    public function personnel() {

    	/*$employe = new Employe();
    	$employe->setNom("Test")
    			->setPrenom("Teste")
    			->setAdresse("Tana")
    			->setFonction("DG");
    	$em = $this->getDoctrine()->getManager();
    	$em->persist($employe);
    	$em->flush();

    	$repository = $this->getDoctrine()->getRepository(Employe::class);
    	dump($repository);*/

    	$employes = $this->repository->findall();
    	
    	return $this->render('personnel/gest_personnels.html.twig',[
    		'personnel' => 'personnels',
    		'employes' => $employes
    	]);
    }

    // Route vers gestion de congés
    /**
     * @Route("/gest_conges", name="conges_show")
     */
    public function conges() {
    	return $this->render('personnel/gest_conges.html.twig', [
    		'conge' => 'conges'
    	]);
    }

    // Route vers gestion permission
    /**
     * @Route ("/gest_permission", name="permission_show")
     */
    public function permission() {
    	return $this->render('personnel/gest_permission.html.twig', [
    		'permission' => 'permissions'
    	]);
    }
}
