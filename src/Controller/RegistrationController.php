<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticator;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordEncoderInterface $encoder ,GoogleAuthenticator $authenticator1, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if($form->isValid()){

                $username =$request->request->get('registration_form')['username'];

                // encode the plain password
                //$hash = $encoder->encodePassword($user, $form->get('plainPassword')->getData());
                if(str_contains('admin', $username)){

                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        ));


                    $container = $this->container;

                    $secret = $authenticator1->generateSecret();
                    $user->setGoogleAuthenticatorSecret($secret);
                    $user->setRole('ROLE_ADMIN');
                    $date= new \DateTime();

                    $user->setDateInsci($date);
                    $user->setIsVerified(true);
                }else{
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );
                    $secret = $authenticator1->generateSecret();
                    $user->setGoogleAuthenticatorSecret($secret);
                    $user->setRole('ROLE_USER');
                    $date= new \DateTime();

                    $user->setDateInsci($date);
                    $user->setIsVerified(true);
                }








                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
               // $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                //    (new TemplatedEmail())
                //        ->from(new Address('souayahmelik@gmail.com', 'contact@softtodo'))
                 //       ->to($user->getEmail())
                  //      ->subject('Please Confirm your Email')
                  //      ->htmlTemplate('registration/confirmation_email.html.twig')
               // );
                // do anything else you need here, like send an email

                //  return $userAuthenticator->authenticateUser(
                //  $user,
                //   $authenticator,
                //   $request
                //  );
                $this->addFlash('success', 'Your account registred succusfully.');

                return $this->redirectToRoute('app_login');
            }


        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('dashboard');
    }
}
