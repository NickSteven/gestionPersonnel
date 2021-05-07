<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Conges;
use App\Entity\Permission;
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
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class UserController extends AbstractController
{



	// Tableau de bord pour chaque utilisateur
    /**
     * @Route("/user/dashboard", name="user_dashboard")
     * @Method({"POST"})
     */
    public function TableauDeBord()
    {
        $user = $this->getUser(); //Prend l'id de chaque utilisateur connecté
        $permission = $this->getDoctrine()->getRepository(Permission::class)->findByUsers($user);

        return $this->render('user/dash_user.html.twig', [
            'controller_name' => 'UserController',
            'permission' => $permission,
        ]);
    }

    // Fonction demander un congé
    /**
     * @Route("/user/nouveau_conge", name="conge_new")
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
            $conge->setUsers($this->getUser());
            $conge->setDateDemande(new \DateTime());
            $conge->setEtat('En attente');



            $manager->persist($conge);
            $manager->flush();

            $this->addFlash('message', 'Votre demande a bien été envoyée.');

            return $this->redirectToRoute('user_dashboard');
        }


        return $this->render('personnel/nouveau_conge.html.twig', [
            'formConge' => $form->createView()
        ]);
    }




    //Editer congé
    // ********************* A FAIRE ******************************
    //Supprimer congé




    // Fonction demander une permission
    /**
    * @Route("/user/nouvelle_permission", name="permission_new")
    */
    public function new_permission(Request $request, ObjectManager $manager) {
    	$permission  = new Permission();

    	$formPermission = $this->createFormBuilder($permission)
    						->add('date_permission', DateType::class, [
    								'label' => 'Date de permission',
    								'required' => true,
    								'widget' => 'single_text',
    							])

    						->add('heure_depart', TimeType::class, [
    								'label' => 'Date et heure de retour',
    								'required' => true,
    								'widget' => 'single_text',
    							])

    						->add('heure_retour', TimeType::class, [
    								'label' => 'Date et heure de retour',
    								'required' => true,
    								'widget' => 'single_text',
    							])

    						->add('sujet', TextareaType::class)
    						->add('save', SubmitType::class, [
    							  'label' => 'Envoyer',
    							])
    						->getForm();

    	$formPermission->handleRequest($request);

    	if($formPermission->isSubmitted() && $formPermission->isValid()) {
    		$permission->setUsers($this->getUser());
    		$permission->setDateDemande(new \Datetime());
    		$permission->setState('En attente');

    		$manager->persist($permission);
    		$manager->flush();

    		$this->addFlash('message', 'Demande bien envoyée');

    		$this->redirectToRoute('user_dashboard');
    	}

    	return $this->render('user/nouvelle_permission.html.twig', [
    		'formPermission' => $formPermission->createView()]);
    }

    // Lister les permissions pour l'utilisateurs connecté
    /**
    * @Route("/user/mes_permission", name="permission_list")
    */
    public function permissionList(){

    }



}
