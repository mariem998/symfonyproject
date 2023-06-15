<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Form\ResetPasswordFormType;
use App\Form\ResettPassFormType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\MailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig.', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }


    #[Route(path: '/oubli-pass', name: 'forgotten_password')]
    public function forgottenPassword(Request $request,
                                      UserRepository $userRepository,
                                      TokenGeneratorInterface $tokenGenerator,
                                      EntityManagerInterface $entityManager,
                                      MailService $mail): Response
    {
        $form=$this->createForm(ResetPasswordRequestFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            //on va chercher user par son email
            $user= $userRepository->findOneByEmail($form->get('email')->getData());
            //on verifi si il ya un user
            if ($user){
                //on génére le token
                $token=$tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();


                //réinitialisation du mot de passe
                $url= $this->generateUrl('reset_pass',['token'=>$token],
                    UrlGeneratorInterface::ABSOLUTE_URL
                );
                //on crée le données du mail
                $context= compact('url','user');
                //envoi du mail
                $mail->send(
                    'no-reply@Shop.com',
                    $user->getEmail(),
                    'réinitialisation ',
                    '/emails/password_reset.html.twig',
                    $context
                );
                $this->addFlash('success','Email sent successfully
');
                return $this->redirectToRoute('app_login');
            }
            //user est null
            $this->addFlash('danger','A problem arose');
            return $this->redirectToRoute('app_login');
        }


        return $this->render('security/reset_password_request.html.twig',[
            'requestPassForm'=>$form->createView()
        ]);

    }
   #[Route(path: '/oubli-pass/{token}', name: 'reset_pass')]
    public function resetPass(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher

    ): Response
    {
        // On vérifie si on a ce token dans la base
        $user= $userRepository->findOneByResetToken($token);
        if($user){
            $form = $this->createForm(ResetPasswordFormType::class);

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                // On efface le token
                $user->setResetToken('');
                $user->setPassword(
                    $passwordHasher->hashPassword(
                        $user,
                        $form->get('password')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('security/reset_password.html.twig', [
                'passForm' => $form->createView()
            ]);
        }
        $this->addFlash('danger', 'Jeton invalide');
        return $this->redirectToRoute('app_login');
    }


}
