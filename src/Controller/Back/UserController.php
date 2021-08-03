<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\Back\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/user", name="back_user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/browse", name="browse", methods={"GET"})
     */
    public function browse(UserRepository $userRepository): Response
    {
        return $this->render('back/user/users.html.twig', [
            'users' => $userRepository->findAll(),
            'title' => 'Users'
        ]);
    }

    /**
     * @Route("/add", name="add", methods={"GET","POST"})
     */
    public function add(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // on hash le mdp
            $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
            // on le remet dans $user->password
            $user->setPassword($hashedPassword);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('back_user_browse', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/add.html.twig', [
            'user' => $user,
            'form' => $form,
            'title' => 'Add User',
        ]);
    }

    /**
     * @Route("/read/{id<\d+>}", name="read", methods={"GET"})
     */
    public function read(User $user): Response
    {
        return $this->render('back/user/read.html.twig', [
            'user' => $user,
            'title' => 'User n°' . $user->getId(),
        ]);
    }

    /**
     * @Route("/edit/{id<\d+>}", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // hashage du mdp que si on a renseigné le champs mot de passe
            // Si le mot de passe du form n'est pas vide
            // c'est qu'on veut le changer !
            if ($form->get('password')->getData() != '') {
                dump($form->get('password')->getData());
                // C'est là qu'on encode le mot de passe du User (qui se trouve dans $user)
                $hashedPassword = $userPasswordHasher->hashPassword($user, $form->get('password')->getData());
                // On réassigne le mot passe encodé dans le User
                $user->setPassword($hashedPassword);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('back_user_browse', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id<\d+>}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('back_user_browse', [], Response::HTTP_SEE_OTHER);
    }
}
