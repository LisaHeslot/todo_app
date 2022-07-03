<?php

namespace App\Controller\api;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\services\Utils;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @Route("/api")
 */
class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="api_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AppAuthenticator $authenticator, EntityManagerInterface $entityManager, Utils $utils):JsonResponse
    {
        if (!$request->isMethod('post')) return new JsonResponse("Something goes wrong", Response::HTTP_METHOD_NOT_ALLOWED, [], true);
        $rawData = $utils->getJson($request);
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->submit($rawData);

        $user->setPassword(
            $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse($user, Response::HTTP_OK, [], false);
    }
}
