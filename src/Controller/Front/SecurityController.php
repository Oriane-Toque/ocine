<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Form\Front\SubscribeType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('front/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/signup", name="app_signup", methods={"GET", "POST"})
     */
    public function signup(Request $request, UserPasswordHasherInterface $userPasswordHasher) {

        $user = new User();
        $form = $this->createForm(SubscribeType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on hash le mdp
            $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
            // on le remet dans $user->password
            $user->setPassword($hashedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Félicitation ! Inscription réussie, vous pouvez maintenant vous connecter et publier toutes les critiques que vous désirez !');

            return $this->redirectToRoute('movie_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/security/signup.html.twig', [
            'user' => $user,
            'form' => $form,
            'title' => 'Sign Up',
        ]);   
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
