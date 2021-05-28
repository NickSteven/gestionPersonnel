<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Entity\Conges;
use App\Entity\Permission;
use App\Entity\Soldes;
use App\Repository\EmployeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\EmployeType;
use App\Form\EditCongeType;
use App\Form\EditPermissionType;
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
        $conges = $this->getDoctrine()->getRepository(Conges::class)->findByUsers($user);
        $soldes = $this->getDoctrine()->getRepository(Soldes::class)->findByUser($user);

        return $this->render('user/dash_user.html.twig', [
            'controller_name' => 'UserController',
            'permission' => $permission,
            'conges' => $conges,
            'solde' => $soldes
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

            $this->addFlash('conge_success', 'Votre demande de congé a bien été envoyée.');

            return $this->redirectToRoute('user_dashboard');
        }


        return $this->render('personnel/nouveau_conge.html.twig', [
            'formConge' => $form->createView()
        ]);
    }

    // **************** ACTIONS A FAIRE SUR LES CONGES POUR LES UTILISATEURS ****************

    /**
     * Editer un congé
     * @Route("/conge/editer/{id}", name="conge_edit")
     */
     public function editerConge(Conges $conges, Request $request) {
        $formConge = $this->createForm(EditCongeType::class, $conges);
        $formConge->handleRequest($request);

        if($formConge->isSubmitted() && $formConge->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($conges);
            $em->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('user_dashboard');
        }
        return $this->render('user/edit_conge.html.twig', [
            'congeForm' => $formConge->createView()
        ]);
     }

    /**
     * Supprimer un congé
     * @Route("/conge/supprimer/{id}", name="conge_supp")
     * @Method({"DELETE"})
     */
    public function retirerDemandeConge($id) {
        $em = $this->getDoctrine()->getManager();

        $req = "DELETE from `conges` WHERE `conges`.`id` = $id;";
        $stmt = $em->getConnection()->prepare($req);
        $stmt->execute();
        
    }




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

    		$this->addFlash('permission_success', 'Votre demande de permission a bien été envoyée');

    		return $this->redirectToRoute('user_dashboard');
    	}

    	return $this->render('user/nouvelle_permission.html.twig', [
    		'formPermission' => $formPermission->createView()]);
    }

    /**
     * Editer une permission
     * @Route("/permission/editer/{id}", name="permission_edit")
     */
    public function editerPermission(Permission $permission, Request $request) {
        $formPermission = $this->createForm(EditPermissionType::class, $permission);
        $formPermission->handleRequest($request);

        if($formPermission->isSubmitted() && $formPermission->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($permission);
            $em->flush();

            $this->addFlash('message', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('user_dashboard');
        }
        return $this->render('user/edit_permission.html.twig', [
            'permissionForm' => $formPermission->createView()
        ]);
    }

    /**
     * Supprimer une permission
     * @Route("/permission/supprimer/{id}", name="permission_delete")
     */
    public function supprimerPermission($id) {
        $em = $this->getDoctrine()->getManager();

        $req = "DELETE from `permission` WHERE `permission`.`id` = $id;";
        $stmt = $em->getConnection()->prepare($req);
        $stmt->execute();

        return $this->redirectToRoute('user_dashboard');
    }



}
