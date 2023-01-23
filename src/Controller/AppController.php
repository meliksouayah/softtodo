<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\User;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Mime\Email;

class AppController extends AbstractController
{



    /**
     * @Route("/home" , name="home")
     */
    public function index(): Response
    {



        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * Finds and displays a project entity.
     *
     * @Route("project/list", name="project_list")
     */
    public function project_listAction(Request $request,PaginatorInterface $paginator)

    {


        $baseurl = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();

        $em = $this->getDoctrine()->getManager();

        $query =  $em->getRepository(Project::class)->all();
        $projectss =  $em->getRepository(Project::class)->all()->getResult();


        $projects = $paginator->paginate(
            $query, $request->query->getInt('page', 1), 4
        );
        if ($request->isMethod('post')) {


            $filename = $request->request->get('filename');
            $status = $request->request->get('status');
            $name = $request->request->get('name');
            $query = $em->getRepository(Project::class)->searchAccueil($name,$status,$filename);


            $projects = $paginator->paginate(
                $query, $request->query->getInt('page', 1), 10
            );
            return $this->render('project/liste.html.twig', array(
                'projectss'=>$projectss,
                'projects'=>$projects,
                'baseurl'=>$baseurl,

            ));

        }


        return $this->render('project/liste.html.twig', array(
            'projectss'=>$projectss,
            'projects' => $projects,
        ));
    }
    //details of project
    /**
     * Finds and displays a news entity.
     *
     * @Route("project/{id}/details", name="project_details")
     */
    public function projectdetailsAction(Project $project)

    {
        $em = $this->getDoctrine()->getManager();

        $related_news =  $em->getRepository(Project::class)->all()->getResult();


        return $this->render('project/details.html.twig', array(
            'project' => $project,

        ));
    }

    /**
     * @Route("/contact" , name="contact")
     */
    public function contact(MailerInterface $mailer,Request $request): Response
    {
        if ($request->isMethod('post')) {
            $mail=$request->request->get('email');
            $subject=$request->request->get('subject');
            $comments=$request->request->get('comments');


            $email = (new Email())
                ->from($mail)
                ->to('souayahmelik@gmail.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject($subject)
                ->text($comments);

            $mailer->send($email);
        }


        return $this->render('app/contact.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @Route("/dashboard" , name="dashboard")
     */
    public function dashboard(): Response
    {



        return $this->render('app/dashboarduser.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }
}
