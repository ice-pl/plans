<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Request;


use App\Entity\UserProject;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Project;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;


/**
 * @Route("/user_project", name="user_project.")
 */
class UserProjectController extends AbstractController
{

    /**
     * @Route("/", name="user_project")
     */
    public function index()
    {
        return $this->render('user_project/index.html.twig', [
            'controller_name' => 'UserProjectController',
        ]);
    }



    /**
     * @Route("/add/{userId}/{projectId}", name="add")
     */
    public function add(Request $request, $userId, $projectId)
    {

        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);

        $project = new Project();
        $project = $this->getDoctrine()->getRepository(Project::class)->find($projectId);

        $user_project = new UserProject();
        $user_project->setUserMap($user);
        $user_project->setProjectMap($project);

        if ( $user && $project ) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($user_project);
            $em->flush();
        }

// dump($user_project);
// die;

        // $projects2 = $this->getDoctrine()->getRepository(UserProject::class)->findAll();
        // $projects2 = $this->getDoctrine()->getRepository(UserProject::class)->findAll_byUserId($userId);
        // $projects2 = $this->getDoctrine()->getRepository(UserProject::class)->findAll_byProjectId($projectId);
        // $projects2 = $this->getDoctrine()->getRepository(UserProject::class)->findProjectId_byUserId($userId);

// dump($projects2);
// die;

        return new Response();
    }




    /**
     * @Route("/delete/{userId}/{projectId}", name="delete")
     */
    public function delete(Request $request, $userId, $projectId)
    {



            $howManyUsers = $this->getDoctrine()->getRepository(UserProject::class)->findAll_byProjectId($projectId);
            $count = 0;
            foreach ($howManyUsers as $key => $value) {
                $count = $count + 1;
            }


        $findUserProjectId_byUserId_byProjectId = $this->getDoctrine()->getRepository(UserProject::class)->findUserProjectId_byUserId_byProjectId($userId, $projectId);

        foreach ($findUserProjectId_byUserId_byProjectId as $nr => $v) {
            foreach ($v as $key => $value) {
                $singleUserProject = $this->getDoctrine()->getRepository(UserProject::class)->find($value);
		        $entityManager = $this->getDoctrine()->getManager();
		        $entityManager->remove($singleUserProject);
		        $entityManager->flush();
            }
        }



            if( $count == '1' ){
                $project = $this->getDoctrine()->getRepository(Project::class)->find($projectId);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($project);
                $entityManager->flush();
            }


        return $this->redirectToRoute('project.list');

        // return new Response();
    }








}
