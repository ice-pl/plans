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



use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\UserProject;

use Symfony\Component\HttpFoundation\Response;


use App\Entity\Product;
use App\Entity\Item;
use App\Entity\SampleItem;

use Symfony\Component\HttpFoundation\JsonResponse;





use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;



use App\Entity\User;




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


        // $projects = $this->getDoctrine()->getRepository(Project::class)->findProjects_byUserId($userId);
        $idForProjects = $this->getDoctrine()->getRepository(UserProject::class)->findProjectId_byUserId($userId);


        $projects = array();
        foreach ($idForProjects as $nr => $v) {
            foreach ($v as $key => $value) {
                $singleProject = $this->getDoctrine()->getRepository(Project::class)->find($value);
            }
            array_push($projects, $singleProject);
        }
        // dump( $projects );


        return $this->render('project/index-base.html.twig', [
            'projects' => $projects,
        ]);
    }



    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list(Request $request, UserInterface $user)
    {

        $userId = $user->getId(); 


        // $projects = $this->getDoctrine()->getRepository(Project::class)->findProjects_byUserId($userId);
        $idForProjects = $this->getDoctrine()->getRepository(UserProject::class)->findProjectId_byUserId($userId);
// dump($idForProjects);

        $projects = array();
        foreach ($idForProjects as $nr => $v) {
            foreach ($v as $key => $value) {
                $singleProject = $this->getDoctrine()->getRepository(Project::class)->find($value);
            }
            array_push($projects, $singleProject);
        }


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
                    'attr' => [ 'class' => 'form-control w-75' ],
                ]
            )
            ->add('position', IntegerType::class,
                [
                    'attr' => 
                    [
                        'class' => 'form-control',
                        'value' => '99999',
                    ],
                ]
            )

            ->add('save', SubmitType::class, 
                [
                    'attr' => 
                        [ 
                            'class' => 'btn btn-primary float-right',
                            'data-dismiss' => "modal",
                        ]
                ]
            )
            ->getForm();
        
        // $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();
            $project->setOwner($user->getId());
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();


            // return $this->redirectToRoute('project.list');
            return $this->redirectToRoute('user_project.add', array('userId' => $user->getId(), 'projectId' => $project->getId() ) );
        }
        return $this->render('project/create-update.html.twig', [
            'form' => $form->createView(),
            'urlSaveType' => 'create',
            'project_id' => ' '
        ]);

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
                    'attr' => [ 'class' => 'form-control w-75' ],
                ]
            )
            ->add('position', IntegerType::class,
                [
                    'attr' => [ 'class' => 'form-control' ],
                ]
            )
            ->add('save', SubmitType::class, 
                [
                    'attr' => 
                        [ 
                            'class' => 'btn btn-primary float-right',
                            // 'data-dismiss'=>"modal",
                        ]
                ]
            )
            ->getForm();


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $project = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);

            $em->flush();

            return $this->redirectToRoute('project.list');
        }
        return $this->render('project/create-update.html.twig', [
            'form' => $form->createView(),
            'urlSaveType' => 'update',
            'project_id' => $id,
        ]);

    }















    /**
     * @Route("/nextSearch", name="nextSearch")
     */
    public function nextSearch(Request $request, ProjectRepository $projectRepository)
    {

        $form = $this->createFormBuilder()
            ->add('query', TextType::class,
                [
                    'attr' => [ 'class' => 'form-control w-100' ],
                    'label' => false,
                ]
            )
            ->add('Search', SubmitType::class, 
                [
                    'attr' => [ 'class' => 'btn btn-secondary my-2 my-sm-0 input-group-append' ]
                ]
            )
            ->getForm();

        $form->handleRequest($request);

//         if ($form->isSubmitted()) {

//             $query = $request->request->get('form')['query'];
//             if( $query ){
//                 $projects = $projectRepository->findProjectByName($query);
//             }
// dump($query);
// dump($projects);
// // die;
//             return $this->render('project/search-result.html.twig', [
//                 'projects' => $projects,
//             ]);


//         }




        return $this->render('project/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }







    /**
     * @Route("/handleSearchForAjax/{query}", name="handleSearchForAjax")
     */
    public function handleSearchForAjax($query, UserInterface $user)
    {
        $userId = $user->getId();
// dump($userId);

        if( $query ){
            $projects = $this->getDoctrine()->getRepository(Project::class)->findProjectByName($query);
        }

        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];

        $serializer = new Serializer($normalizers, $encoders);


        $jsonContent = $serializer->serialize($projects, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['products','projectInverse']]);

        // return $this->json($jsonContent);
        // return new JsonResponse(json_decode($jsonContent));
        // return new JsonResponse($jsonContent);
        return new JsonResponse([$jsonContent,'userId'=>$userId]);
    }



    /**
     * @Route("/ask/{projectId}", name="ask")
     */
    public function ask($projectId, UserInterface $user)
    {

        $project = new Project();
        $project = $this->getDoctrine()->getRepository(Project::class)->find($projectId);
        
        $current_share_questions = $project->getShareQuestions();

        if($current_share_questions == null){
            $current_share_questions = [];
        }

        $userId = $user->getId();
        $userName = $user->getUsername();
        $user = [];
        $user = [$userId,$userName];


        array_push($current_share_questions, $user);
        $current_share_questions = array_map("unserialize", array_unique(array_map("serialize", $current_share_questions)));
// dump($current_share_questions);

        $project->setShareQuestions($current_share_questions);

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        return new Response();
    }





    /**
     * @Route("/answer/{projectId}/{answer}/{nrInArray}", name="answer")
     */
    public function answer($projectId, $answer, $nrInArray, UserInterface $user)
    {

        $project = new Project();
        $project = $this->getDoctrine()->getRepository(Project::class)->find($projectId);

        $oldProject = $project->getShareQuestions();
        $current_share_question = $oldProject[$nrInArray];

        if($answer == 1){
            $userIdForUserProjectMap = $current_share_question[0];
            $userForUserProjectMap = new User();
            $userForUserProjectMap = $this->getDoctrine()->getRepository(User::class)->find($userIdForUserProjectMap);
        }

        array_splice($oldProject, $nrInArray, 1);
        if(empty($oldProject)){
           $oldProject = null; 
        }


        $project->setShareQuestions($oldProject);

        array_push($current_share_question, $answer);

        $current_share_answer = $project->getShareAnswers();
        if($current_share_answer == null){
            $current_share_answer = [];
        }
        array_push($current_share_answer, $current_share_question);






        $project->setShareAnswers($current_share_answer);

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();



        if($answer == 1){
            $user_project = new UserProject();
            $user_project->setUserMap($userForUserProjectMap);
            $user_project->setProjectMap($project);

            $em->persist($user_project);
            $em->flush();
        }


        return new Response();

    }






    /**
     * @Route("/dismiss/{projectId}/{nrInArray}", name="dismiss")
     */
    public function dismiss($projectId, $nrInArray, UserInterface $user)
    {

        $project = new Project();
        $project = $this->getDoctrine()->getRepository(Project::class)->find($projectId);

        $answers = $project->getShareAnswers();



        array_splice($answers, $nrInArray, 1);
        if(empty($answers)){
           $answers = null; 
        }

        $project->setShareAnswers($answers);

        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        return new Response();

    }






















    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, UserInterface $user, $id)
    {

        return $this->redirectToRoute('user_project.delete', array('userId' => $user->getId(), 'projectId' => $id ) );

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




    /**
     * @Route("/info/{projectId}", name="info")
     */
    public function info($projectId)
    {
        $project = $this->getDoctrine()->getRepository(Project::class)->find($projectId);

        $project->product = $this->getDoctrine()->getRepository(Product::class)->findProducts_byProjectId($projectId);
        foreach ($project->product as $product) {
            $prductId = $product->getId();
            $product->item = $this->getDoctrine()->getRepository(Item::class)->findItems_byProductId($prductId);

            foreach ($product->item as $item) {
                $parentItemId = $item->getParentId();
                $item->sampleItem = $this->getDoctrine()->getRepository(SampleItem::class)->find($parentItemId);
            }
        }


        // dump($project);


        return $this->render('project/info.html.twig', [
            'project' => $project,
            // 'products' => $products,
        ]);
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






}



