<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use App\Form\EditProfileType;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    private $router;
    public function __construct( RouterInterface $router)
    {
        $this->router = $router;
    }
    #[Route('/profil/{id}', name: 'app_profil')]
    public function profil(Request $request, User $user,EntityManagerInterface $em): Response
    {

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $edit_profile=$request->request->get('edit_profile');
            $username=$edit_profile['username'];
            $email=$edit_profile['email'];

            $edit_profile=$request->request->get('edit_profile');


                $user->setUsername($username);
                $user->setEmail($email);



                $em->persist($user);
                $em->flush();
                $this->addFlash('success',"Your profile has been successfully modified");
                return $this->redirectToRoute('app_profil', ['id'=>$user->getId()], Response::HTTP_SEE_OTHER);




        }
        return $this->render('profil/profil.html.twig', [
            'controller_name' => 'ProfilController',
            'form' => $form->createView(),
        ]);
    }
    #[Route('/change/mot/de/passe/{id}', name: 'change_mot_de_passe')]
    public function change_mot_de_passe(Request $request,EntityManagerInterface $em ,User $user,UserPasswordHasherInterface $passwordHasher, UserPasswordHasherInterface $userPasswordHasher, EncoderFactoryInterface $encoderFactory): Response
    {



        if ($request->isMethod('post')) {
            $old_password=$request->request->get('old_password');
            $new_password=$request->request->get('new_password');
            $new2_password=$request->request->get('new2_password');

           // $encoder = $encoderFactory->getEncoder($user);
           // if ($encoder->isPasswordValid($user->getPassword(),$old_password,null)){
             //  $user->setPassword(
                //    $userPasswordEncoder->encodePassword(
                  //      $user,
                //        $new_password
                  //  )
               // );
            if ($passwordHasher->isPasswordValid($user, $old_password)) {


                if($new_password!=$new2_password){
                    $this->addFlash('error',"new passwords do not match");
                    $this->redirectToRoute('change_mot_de_passe',array('id'=>$user->getId()));

                }else{
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $new_password
                        )
                    );
                    $em->flush();
                    $this->addFlash('success',"the password has been changed successfully");
                    return $this->redirectToRoute('app_profil', ['id'=>$user->getId()], Response::HTTP_SEE_OTHER);

                }
            }else{
                $this->addFlash('error',"the old password incorrect");
                return $this->redirectToRoute('change_mot_de_passe', ['id'=>$user->getId()], Response::HTTP_SEE_OTHER);

            }


        }

        return $this->render('profil/change_mot_de_passe.html.twig', [
        ]);
    }
}
