<?php

namespace App\Controller;


use App\Entity\Employe;
use App\Entity\Conges;
use App\Entity\User;
use App\Entity\Permission;
use App\Entity\UserRepository;
use App\Repository\EmployeRepository;
use App\Repository\PermissionRepository;
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

    // Route vers gestion personnel
    /**
     * @Route("/admin/gest_personnel", name="personnel_show")
     */
    public function personnel() {

    	$employes = $this->getDoctrine()->getRepository(User::class)->findAll();
    	
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
     * @Route ("/admin/gest_permission", name="permission_show")
     */
    public function permissionList() {
        $permission = $this->getDoctrine()->getRepository(Permission::class)->findAll();

        $em = $this->getDoctrine()->getManager();

        $req = "SELECT permission.id, date_permission, heure_depart, heure_retour, sujet, username from permission, user where permission.users_id = user.id;";

        $statement = $em->getConnection()->prepare($req);
        $statement->execute();
        $permission = $statement->fetchAll();

    	return $this->render('personnel/gest_permission.html.twig', [
    		'permission' => 'permissions',
            'permis' => $permission
    	]);
    }

    /**
     * Validation permission (premier validation)
     * @Route("/gest_permission/valider/{id}", name="permission_vaider")
     * @Method({"POST"})
     */
    public function validerPermission(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        // Requête de validation
        $req = "UPDATE `permission` SET `state` = 'A valider' WHERE `permission`.`id` = $id ;";
        $stmt = $em->getConnection()->prepare($req);
        $stmt->execute();

        return true;
    }

    /**
     * Refus d'une permission
     * @Route("/gest_permission/refuser/{id}", name="permission_refuser")
     * @Method({"POST"})
     */
    public function refuserPermission($id) {
        $em = $this->getDoctrine()->getManager();

        // Requête pour le refus
        $req = "UPDATE `permission` SET `state` = 'Refusé' WHERE `permission`.`id` = $id;";
        $stmt = $em->getConnection()->prepare($req);
        $stmt->execute();

        return true;
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
    

    

}
