<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\ConversationRepository;


use Symfony\Component\HttpFoundation\Request;

use App\Entity\Conversation;


use App\Repository\ItemRepository;
use Symfony\Component\Security\Core\User\UserInterface;


use App\Entity\Item;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;



/**
 * @Route("/conversation", name="conversation.")
 */
class ConversationController extends AbstractController
{



    /**
     * @Route("/list", name="list", methods={"GET"})
     */
    public function list(ConversationRepository $conversationRepository)
    {
        $conversations = $conversationRepository->findAll();

        return $this->render('conversation/index.html.twig', [
            'conversations' => $conversations
        ]);
    }


    /**
     * @Route("/list_base", name="list_base")
     */
    public function list_base(Request $request)
    {

        $conversations = $this->getDoctrine()->getRepository(Conversation::class)->findAll();

        return $this->render('conversation/index-base.html.twig', [
            'conversations' => $conversations
        ]);
    }






    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request, ItemRepository $itemRepository, UserInterface $user )
    {
        $item = new Item();
        $userId = $user->getId(); 

        $conversation = new Conversation();

        $item = $conversation->getItem();


        $form = $this->createFormBuilder($conversation)
            ->add('content', TextareaType::class,
                [
                    'attr' =>
                    [
                        'class' => 'form-control',
                    ],
                ]
            )
            ->add('save', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary float-right'
                ]
            ])
            ->getForm();


        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            $conversation = $form->getData();
        $conversation->setItem($item);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($conversation);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('conversation/create-update.html.twig', [
            'form' => $form->createView()
        ]);
    }









    /**
     * @Route("/list/{itemId}", name="list_byItem")
     */
    public function list_byItem(Request $request, UserInterface $user, $itemId)
    {
        $conversations = $this->getDoctrine()->getRepository(Conversation::class)->findConversations_byItemId( $itemId );


        // return $this->render('conversation/index-base.html.twig', [
        return $this->render('conversation/index.html.twig', [

            'conversations' => $conversations,
        ]);
    }












    /**
     * @Route("/create/{itemId}", name="create_byItem", methods={"GET", "POST"})
     */
    public function create_byItem(Request $request, UserInterface $user, $itemId)
    {

        $conversations = $this->getDoctrine()->getRepository(Conversation::class)->findConversations_byItemId( $itemId );


        $item = new Item();
        $item = $this->getDoctrine()->getRepository(Item::class)->findBy(['id' => $itemId ]);
        $item = $item[0];


        $conversation = new Conversation();

        $form = $this->createFormBuilder($conversation)
            ->add('content', TextareaType::class,
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

        if( $form->isSubmitted() ){
            $conversation = $form->getData();
            $conversation->setItem($item);
            $conversation->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($conversation);
            $entityManager->flush();
        }


        return $this->render('conversation/create-update.html.twig', [
            'conversations' => $conversations,
            'itemId' => $itemId,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }








    /**
     * @Route("/getLastId/{itemId}", name="getLastId")
     */
    public function getLastId(Request $request, $itemId)
    {
        $lastConversationId = $this->getDoctrine()->getRepository(Conversation::class)->findLastConversationId_byItemId($itemId);
        $lastConversationId = $lastConversationId[0]['conversation_id'];

        return new JsonResponse(
            [ 'lastConversationId' => $lastConversationId ]
        );
    }















    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, $id)
    {
        $conversation = $this->getDoctrine()->getRepository(Conversation::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($conversation);
        $entityManager->flush();

        return new JsonResponse();
    }





    /**
     * @Route("/{id}", name="show")
     */
    public function show($id)
    {
        $conversation = $this->getDoctrine()->getRepository(Conversation::class)->find($id);

        return $this->render('conversation/show.html.twig', [
            'conversation' => $conversation
        ]);
    }




}
