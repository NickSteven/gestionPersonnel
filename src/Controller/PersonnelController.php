<?php

namespace App\Controller;


use App\Entity\Employe;
use App\Entity\Conges;
use App\Repository\EmployeRepository;
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

    
    /**
     * @Route("/gest_conges", name="conges_show")
     */
    /*public function conges() {

        $conges = $this->repository->findAll();
    	return $this->render('personnel/gest_conges.html.twig', [
    		'conge' => 'conges',
            'conges' => $conges
    	]);
    }*/

    // Route vers gestion permission
    /**
     * @Route ("/gest_permission", name="permission_show")
     */
    public function permission() {
    	return $this->render('personnel/gest_permission.html.twig', [
    		'permission' => 'permissions'
    	]);
    }

    // Création d'un nouveau employé
    /**
     * @Route("/gest_personnel/new", name="new_employe")
     * Method({"GET", "POST"})
     */
    public function new(Request $request) {
    	$employe = new Employe();

    	$form = $this->createFormBuilder($employe)
    				 ->add('nom', TextType::class, array(
    				 	'required' => true,
    				 	'attr' => array('class' => 'form-control')
    				 ))
    				 ->add('prenom', TextType::class, array(
    				 	'required' => true,
    				 	'attr' => array('class' => 'form-control')
    				 ))
    				 ->add('adresse', TextType::class, array(
    				 	'required' => true,
    				 	'attr' => array('class' => 'form-control')
    				 ))
    				 ->add('fonction', TextType::class, array(
    				 	'required' => true,
    				 	'attr' => array('class' => 'form-control')
    				 ))
    				 ->add('save', SubmitType::class, array(
    				 	'label' => 'Ajouter',
    				 	'attr' => array('class' => 'btn btn-primary margin-top-3')
    				 ))
    				 ->getForm();

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()) {
    		$employe = $form->getData();

    		$entityManager = $this->getDoctrine()->getManager();
    		$entityManager->persist($employe);
    		$entityManager->flush();

    		return $this->redirectToRoute('personnel_show');
    	}

    	return $this->render('personnel/nouveau.html.twig', array(
    		'form' => $form->createView()
    	));
    }

    // Editer un employé
    /**
     * @Route("/gest_personnel/edit/{id}", name="edit_employe")
     * Method({"GET", "POST"})
     */
    public function edit(Request $request, $id) {
    	$employe = new Employe();

    	$employe = $this->getDoctrine()->getRepository(Employe::class)->find($id);

    	$form = $this->createFormBuilder($employe)
    				 ->add('nom', TextType::class, array(
    				 	'required' => true,
    				 	'attr' => array('class' => 'form-control')
    				 ))
    				 ->add('prenom', TextType::class, array(
    				 	'required' => true,
    				 	'attr' => array('class' => 'form-control')
    				 ))
    				 ->add('adresse', TextType::class, array(
    				 	'required' => true,
    				 	'attr' => array('class' => 'form-control')
    				 ))
    				 ->add('fonction', TextType::class, array(
    				 	'required' => true,
    				 	'attr' => array('class' => 'form-control')
    				 ))
    				 ->add('save', SubmitType::class, array(
    				 	'label' => 'Mettre à jour',
    				 	'attr' => array('class' => 'btn btn-primary margin-top-3')
    				 ))
    				 ->getForm();

    	$form->handleRequest($request);

    	if($form->isSubmitted() && $form->isValid()) {
    		

    		$entityManager = $this->getDoctrine()->getManager();
    		$entityManager->flush();

    		return $this->redirectToRoute('personnel_show');
    	}

    	return $this->render('personnel/edit.html.twig', array(
    		'form' => $form->createView()
    	));
    }


    // Suppression d'un employé
    /**
     * @Route("/gest_conges/delete/{id}")
     * @Method({"DELETE"})
    */
    public function delete(Request $request, $id) {
    	$employe = $this->getDoctrine()->getRepository(Employe::class)->find($id);

    	$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->remove($employe);
    	$entityManager->flush();

    	$response = new Response();
    	$response->send();
    }

    // Voir détail enployé
    /**
     * @Route ("/gest_personnel/{id}", name="employe_show")
    */
    public function show($id) {
    	$employe = $this->getDoctrine()->getRepository(Employe::class)->find($id);

    	return $this->render('personnel/affich_perso.html.twig', 
    		array('employe' => $employe));
    }

    // Demande congé
    /**
     * @Route("/nouveau_conge", name="conge_new")
     */
    public function new_conge(Request $request, ObjectManager $manager) {
        $conge = new Conges();

        $form = $this->createFormBuilder($conge)
                     ->add('date_depart', DateType::class, [
                        
                        'label' => 'Date de départ:',
                        'required' => TRUE,
                        'widget' => 'single_text',
                        
                    ])
                     ->add('date_retour', DateType::class, [
                        
                        'label' => 'Date de retour:',
                        'required' => TRUE,
                        'widget' => 'single_text',
                     ])
                     ->add('motif', TextareaType::class, [
                        
                     ])
                     ->add('save', SubmitType::class, [
                        
                        'label' => 'Soumettre'
                     ])
                     ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $conge->setDateDemande(new \DateTime());
            $conge->setEtat(1);

            $manager->persist($conge);
            $manager->flush();

            return $this->redirectToRoute('conge_new');
        }


        return $this->render('personnel/nouveau_conge.html.twig', [
            'formConge' => $form->createView()
        ]);
    }



    

}
