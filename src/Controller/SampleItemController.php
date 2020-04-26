<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use App\Repository\SampleItemRepository;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\SampleItem;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Response;

use App\Form\SampleItemType;

use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/sample_item", name="sample_item.")
 */
class SampleItemController extends AbstractController
{





    /**
     * @Route("/list", name="list")
     */
    public function list(SampleItemRepository $sampleItemRepository)
    {
        $sample_items = $sampleItemRepository->findAll();

        return $this->render('sample_item/index.html.twig', [
            'sample_items' => $sample_items
        ]);
    }



    /**
     * @Route("/list_base", name="list_base")
     */
    public function list_base(Request $request)
    {

        $sample_items = $this->getDoctrine()->getRepository(SampleItem::class)->findAll();

        return $this->render('sample_item/index-base.html.twig', [
            'sample_items' => $sample_items
        ]);
    }



    /**
     * @Route("/list/{id}", name="list_byProduct")
     */
    public function list_byProduct(Request $request, $id)
    {

        $sample_items = $this->getDoctrine()->getRepository(SampleItem::class)
                ->findSampleItems_bySampleProductId($id);

        // return $this->render('item/index-base.html.twig', [
        return $this->render('sample_item/index.html.twig', [

            'sample_items' => $sample_items
        ]);
    }









    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {
        $sample_item = new SampleItem();

        // $form = $this->createFormBuilder($sample_item)
        //     ->add('name', TextType::class,
        //         [
        //             'attr' => [ 'class' => 'form-control' ],
        //         ]
        //     )
        //     ->add('position', IntegerType::class,
        //         [
        //             'attr' => ['class' => 'form-control' ],
        //         ]
        //     )
        //     ->add('description', TextType::class,
        //         [
        //             'attr' => [ 'class' => 'form-control' ],
        //         ]
        //     )
        //     ->add('value', IntegerType::class,
        //         [
        //             'attr' => ['class' => 'form-control' ],
        //         ]
        //     )
        //     ->add('save', SubmitType::class, 
        //     	[
        //         	'attr' => [ 'class' => 'btn btn-primary float-right' ]
	       //      ]
	       //  )
        //     ->getForm();

        $form = $this->createForm(SampleItemType::class, $sample_item);



        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            $sample_item = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($sample_item);
            $em->flush();

            return $this->redirectToRoute('sample_item.list_base');
        }


        return $this->render('sample_item/create-update.html.twig', [
            'form' => $form->createView()
        ]);

    }




    /**
     * @Route("/update/{id}", name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, $id)
    {
        $sample_item = new SampleItem();
        $sample_item = $this->getDoctrine()->getRepository(SampleItem::class)->find($id);

        $form = $this->createFormBuilder($sample_item)
            ->add('name', TextType::class,
                [
                    'attr' => [ 'class' => 'form-control' ],
                ]
            )
            ->add('position', IntegerType::class,
                [
                    'attr' => ['class' => 'form-control' ],
                ]
            )
            ->add('description', TextType::class,
                [
                    'attr' => [ 'class' => 'form-control' ],
                ]
            )
            ->add('value', IntegerType::class,
                [
                    'attr' => ['class' => 'form-control' ],
                ],
            )
            ->add('save', SubmitType::class, 
            	[
                	'attr' => [ 'class' => 'btn btn-primary float-right' ]
	            ]
	        )
            ->getForm();

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            // $sample_item = $form->getData();

            $em = $this->getDoctrine()->getManager();
            // $em->persist($sample_item);
            $em->flush();

            return $this->redirectToRoute('sample_item.list_base');
        }


        return $this->render('sample_item/create-update.html.twig', [
            'form' => $form->createView()
        ]);

    }




    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, $id)
    {
        $sample_item = $this->getDoctrine()->getRepository(SampleItem::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sample_item);
        $entityManager->flush();


        // $this->addFlash('success', 'Post was removed');
        // return $this->redirectToRoute('home');
        return $this->redirectToRoute('sample_item.list_base');
    }





    /**
     * @Route("/{id}", name="show")
     */
    public function show($id)
    {
        $sample_item = $this->getDoctrine()->getRepository(SampleItem::class)->find($id);

        return $this->render('sample_item/show.html.twig', [
            'sample_item' => $sample_item
        ]);
    }





    /**
     * @Route("/description/{id}", name="description")
     */
    public function description(Request $request, $id)
    {
        $description = $this->getDoctrine()->getRepository(SampleItem::class)->find($id)->getDescription();
        $name = $this->getDoctrine()->getRepository(SampleItem::class)->find($id)->getName();

        return new JsonResponse( [
            'name' => $name,
            'description' => $description,
        ] );
    }


    /**
     * @Route("/emptyDescription", name="emptyDescription")
     */
    public function emptyDescription()
    {
        // return new Response('Its coming from here.', 200, array('Content-Type' => 'text/html'));
        return new Response();
    }




}
