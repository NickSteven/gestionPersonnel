<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Soldes;
use App\Entity\User;
use App\Form\RegistrationType;

class SecurityController extends AbstractController
{	
	// Inscription
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ManagerRegistry $manager, UserPasswordEncoderInterface $encoder)
    {
       $user = new User();
       $soldes = new Soldes();

       $form = $this->createForm(RegistrationType::class, $user);

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) {
            //Hashage du mot de passe
       		$hash = $encoder->encodePassword($user, $user->getPassword());
       		$user->setPassword($hash);
            $manager = $this->getDoctrine()->getManager();
       		$manager->persist($user);
            $manager->flush();




            //Initialisation des soldes
            $userRegistrated = $this->getDoctrine()->getRepository(User::class)->findBy(array('username' => $user->getUsername() , 'password' => $hash));
            $user_id = $userRegistrated->getId();
            dd($user_id);;



            $soldes->setUser($user_id);
            $soldes->setInitial(0);
            $soldes->setConsomme(0);
            $soldes->setRestant(0);


       		
            $manager->persist($soldes);
       		$manager->flush();

       		return $this->redirectToRoute('security_login');

       }

       return $this->render('security/registration.html.twig', [
       		'form' => $form->createView()
       ]);
    }

    // Login
    /**
     * @Route("/connexion", name="security_login")
     */

    public function login(){
    	return $this->render('security/login.html.twig');
      
      
    }

    // Logout
    /**
     * @Route("/deconnexion", name="security_logout")
     */

    public function logout() {}
}
