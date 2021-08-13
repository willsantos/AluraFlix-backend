<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @Route("/auth/register", name="register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordHasherInterface $encoder)
    {
        $password = $request->get('password');
        $username = $request->get('username');


        $user = new User();
        $user
            ->setUsername($username)
            ->setPassword($encoder->hashPassword($user,$password));

        $newUser = $this->getDoctrine()->getManager();
        $newUser->persist($user);
        $newUser->flush();

        return $this->json([
           'user'=> $user->getUserIdentifier()
        ]);

    }


}
