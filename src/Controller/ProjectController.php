<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/project')]
class ProjectController extends AbstractController
{
    #[Route('/', name: 'admin_project_index')]
    public function index(ProjectRepository $newsRepository,PaginatorInterface $paginator,Request $request): Response
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
            return $this->render('project/index.html.twig', array(
                'projectss'=>$projectss,
                'projects'=>$projects,
                'baseurl'=>$baseurl,

            ));

        }
        return $this->render('project/index.html.twig', array(
            'projectss'=>$projectss,
            'projects'=>$projects,
            'baseurl'=>$baseurl,

        ));
    }

    #[Route('/new', name: 'admin_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectRepository $projectRepository): Response
    {

        $em=$this->getDoctrine()->getManager();
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $project->setCreatedat(new \DateTime());
            $project->setTest(1);

            $uploadedFile = $request->files->get('app_project_picture')['picture'];

            if ($uploadedFile != null) {

                //dump($uploadedFile);
                $destination = $this -> getParameter('project_directory');
                $fileName = md5(uniqid()).'.'.$uploadedFile->guessExtension();

                $uploadedFile->move($destination,$fileName);
                $project->setPicture($fileName);


            }
            $uploadedFilename = $request->files->get('app_preject')['filename'];

            if ($uploadedFilename != null) {

                //dump($uploadedFile);

                $destination = $this -> getParameter('project_directory');
                $fileName = md5(uniqid()).'.'.$uploadedFilename->guessExtension();

                $uploadedFilename->move($destination,$fileName);
                $project->setFilename($fileName);


            }






            $em->persist($project);
            $em->flush();
            $this->addFlash('success', "project has been created successfully");

            return $this->redirectToRoute('admin_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('project/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_project_show', methods: ['GET'])]
    public function show(Project $news): Response
    {
        return $this->render('project/show.html.twig', [
            'project' => $news,
        ]);
    }
    public function generateReference($length)
    {
        $reference = implode('', [
            bin2hex(random_bytes(2)),
            bin2hex(random_bytes(2)),
            bin2hex(chr((ord(random_bytes(1)) & 0x0F) | 0x40)) . bin2hex(random_bytes(1)),
            bin2hex(chr((ord(random_bytes(1)) & 0x3F) | 0x80)) . bin2hex(random_bytes(1)),
            bin2hex(random_bytes(2))
        ]);

        return strlen($reference) > $length ? substr($reference, 0, $length) : $reference;
    }

    #[Route('/{id}/edit', name: 'admin_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Project $project, ProjectRepository $projectsRepository): Response
    {
        $em=$this->getDoctrine()->getManager();

        $oldpicture=$project->getPicture();
        $oldfilename=$project->getFilename();


        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $uploadedFile = $form->get('picture')->getData();

            if ($uploadedFile != null) {
                //dump($uploadedFile);
                $destination = $this -> getParameter('project_directory');
                $fileName = md5(uniqid()).'.'.$uploadedFile->guessExtension();
                $uploadedFile->move($destination,$fileName);
                $project->setPicture($fileName);
            }else{
                $project->setPicture($oldpicture);

            }

            $uploadedFilename = $form->get('filename')->getData();


            if ($uploadedFilename != null) {

                //dump($uploadedFile);
                $destination = $this -> getParameter('project_directory');
                $fileName = md5(uniqid()).'.'.$uploadedFile->guessExtension();

                $uploadedFile->move($destination,$fileName);
                $project->setFilename($fileName);


            }else{
                $project->setFilename($oldfilename);

            }









            $em->persist($project);

            $em->flush();
            $this->addFlash('success', "project has been updated successfully");


            return $this->redirectToRoute('admin_project_index');
        }

        return $this->renderForm('project/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_project_delete', methods: ['POST'])]
    public function delete(Request $request, Project $news, ProjectRepository $newsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$news->getId(), $request->request->get('_token'))) {
            $newsRepository->remove($news, true);
        }

        return $this->redirectToRoute('admin_project_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/delete/project", name="delete_project", methods="GET|POST")
     */
    public function delete_project(Request $request)
    {
        $em = $this->getDoctrine()->getManager();




            $id = $request->request->get('id');
            $news = $em->getRepository(Project::class)->findOneBy(array('id' => $id));


            if ($news) {

                $em->remove($news);
                $em->flush();
                $responseArray = array("message" => 'project has been successfully deleted');
            }

        return new JsonResponse($responseArray);
      //  return $this->json([
         //   'success' => true,
         //   'message' => "project has been successfully deleted"
       // ]);

    }


}
