<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;

use App\Entity\SampleProduct;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\SampleItem;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\SampleProductType;



/**
 * @Route("/sample_product", name="sample_product.")
 */
class SampleProductController extends AbstractController
{



    /**
     * @Route("/list_base", name="list_base")
     */
    public function list_base(Request $request, $sample_products=null)
    {

        $sample_products = $this->getDoctrine()->getRepository(SampleProduct::class)->findAll();

        return $this->render('sample_product/index-base.html.twig', [
            'sample_products' => $sample_products
        ]);
    }




    /**
     * @Route("/list", name="list")
     */
    public function list(Request $request, $sample_products=null)
    {
        $sample_products = $this->getDoctrine()->getRepository(SampleProduct::class)->findAll();


        foreach($sample_products as $product){

            $productId = $product->getId();
            $items = $this->getDoctrine()->getRepository(SampleItem::class)->findSampleItems_bySampleProductId($productId);

            foreach($items as $item){
                $product = $product->addSampleItem($item);
            }
        }
// dump($sample_products);
// die;

        return $this->render('sample_product/index.html.twig', [
            'sample_products' => $sample_products
        ]);
    }





    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {

        $sample_product = new SampleProduct();

        $form = $this->createForm(SampleProductType::class, $sample_product);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getManager();
            $em->persist($sample_product);
            $em->flush();

            return $this->redirectToRoute('sample_product.list_base');
        }


        return $this->render('sample_product/create-update.html.twig', [
            'form' => $form->createView(),
            'urlSaveType' => 'create',
            'sample_product_id' => ' '
        ]);

    }





    /**
     * @Route("/update/{id}", name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, $id)
    {

        $sample_product = new SampleProduct();
        $sample_product = $this->getDoctrine()->getRepository(SampleProduct::class)->find($id);

        $howMany = $sample_product->getHowMany();

        $form = $this->createForm(SampleProductType::class, $sample_product);

        // $form = $this->createFormBuilder($sample_product)
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
        //     ->add('image', FileType::class, 
        //     	[
	       //          'mapped' => false,
	       //          'required' => false,
	       //      ]
	       //  )
        //     ->add('save', SubmitType::class, 
        //     	[
        //         	'attr' => [ 'class' => 'btn btn-primary float-right' ]
	       //      ]
	       //  )
        //     ->getForm();

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            // $sample_product = $form->getData();

            $sample_product->setHowMany($howMany);
            $em = $this->getDoctrine()->getManager();
            // $em->persist($sample_product);
            $em->flush();

            return $this->redirectToRoute('sample_product.list_base');
        }


        return $this->render('sample_product/create-update.html.twig', [
            'form' => $form->createView(),
            'urlSaveType' => 'update',
            'sample_product_id' => $id,
        ]);

    }



    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, $id)
    {
        $sample_product = $this->getDoctrine()->getRepository(SampleProduct::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sample_product);
        $entityManager->flush();

        // $this->addFlash('success', 'Post was removed');
		// return $this->redirectToRoute('sample_product.list_base');
        return $this->redirectToRoute('sample_product.list');

    }




    /**
     * @Route("/{id}", name="show")
     */
    public function show($id)
    {
        $sample_product = $this->getDoctrine()->getRepository(SampleProduct::class)->find($id);

        return $this->render('sample_product/show.html.twig', [
            'sample_product' => $sample_product
        ]);
    }






}
