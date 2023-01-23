<?php

namespace App\Controller;

use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/', name: 'app_login')]
    public function loginAction(AuthenticationUtils $authenticationUtils,Request $request): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

         if ($this->getUser()!= null) {

             return $this->redirectToRoute('app_login');
         }else{
             return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

         }




    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    #[Route(path: '/2fa', name: '2fa_login')]
    public function check2fa(Request $request ,GoogleAuthenticatorInterface $authenticator,TokenStorageInterface $storage): Response
    {
        $code=$authenticator->getQRContent($storage->getToken()->getUser());
        $qrcode="http://chart.apis.google.com/chart?cht=qr&chs=150x150&chl=".$code;
        return $this->render('security/2fa_login.thml.twig', [
            'qrcode'=>$qrcode

    ]);

    }
}
