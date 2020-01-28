<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;




use Symfony\Component\HttpFoundation\Request;


use App\Repository\ProjectRepository;
use App\Entity\Project;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


use App\Form\ProjectType;

use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @Route("/project", name="project.")
 */
class ProjectController extends AbstractController
{






    /**
     * @Route("/list_base", name="list_base", methods={"GET"})
     */
    public function list_base(Request $request, UserInterface $user)
    {

        $userId = $user->getId(); 


        $projects = $this->getDoctrine()->getRepository(Project::class)->findProjects_byUserId($userId);


        return $this->render('project/index-base.html.twig', [
            'projects' => $projects
        ]);
    }



    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list(Request $request, UserInterface $user)
    {

        $userId = $user->getId(); 


        $projects = $this->getDoctrine()->getRepository(Project::class)->findProjects_byUserId($userId);


        return $this->render('project/index.html.twig', [
            'projects' => $projects
        ]);
    }



    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, UserInterface $user)
    {
        $project = new Project();

        $form = $this->createFormBuilder($project)
            ->add('name', TextType::class,
                [
                    'attr' => [ 'class' => 'form-control' ],
                ]
            )
            ->add('position', IntegerType::class,
                [
                    'attr' => [ 'class' => 'form-control' ],
                ]
            )

            ->add('save', SubmitType::class, 
                [
                    'attr' => [ 'class' => 'btn btn-primary float-right' ]
                ]
            )
            ->getForm();
        
        // $form = $this->createForm(ProjectType::class, $project);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $project->setUser($user);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('project/create-update.html.twig', [
            'form' => $form->createView()
        ]);

                // return new Response('<h1>uuu </h1>'  );

    }





    /**
     * @Route("/update/{id}", name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, $id)
    {
        $project = new Project();
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);


        $form = $this->createFormBuilder($project)
            ->add('name', TextType::class,
                [
                    'attr' => [ 'class' => 'form-control' ],
                ]
            )
            ->add('position', IntegerType::class,
                [
                    'attr' => [ 'class' => 'form-control' ],
                ]
            )

            ->add('save', SubmitType::class, 
                [
                    'attr' => [ 'class' => 'btn btn-primary float-right' ]
                ]
            )
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirectToRoute('project.list');
        }
        return $this->render('project/create-update.html.twig', [
            'form' => $form->createView()
        ]);

    }



    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, $id)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($project);
        $entityManager->flush();

        // $this->addFlash('success', 'Post was removed');
        return $this->redirectToRoute('project.list');
    }



    /**
     * @Route("/{id}", name="show")
     */
    public function show($id)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($id);

        return $this->render('project/show.html.twig', [
            'project' => $project
        ]);
    }




    /**
     * Resorts an item using it's doctrine sortable property
     * @Route("/sort/{id}/{position}", name="sort")
     */
    public function sort(Request $request, $id, $position)
    {
        $data = $request->request->all();

        if (isset($data['update'])){
            foreach($data['positions'] as $position){
                $index = $position[0];
                $newPosition = $position[1];

                $em = $this->getDoctrine()->getManager();

                $project = $em->getRepository(Project::class)->find($index);
                $project->setPosition($newPosition);

                $em->persist($project);
            }
        }
        $em->flush();

        return new Response();
    }








}



