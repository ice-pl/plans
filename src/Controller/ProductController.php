<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

use App\Entity\Product;
use App\Repository\ProductRepository;

use App\Entity\Project;




use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use \stdClass;

use Doctrine\ORM\Query;


use App\Entity\Item;


use Symfony\Component\HttpFoundation\Response;




/**
 * @Route("/product", name="product.")
 */
class ProductController extends AbstractController
{






    /**
     * @Route("/list_base", name="list_base")
     */
    public function list_base(Request $request, UserInterface $user, $products=null)
    {

        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('product/index-base.html.twig', [
            'products' => $products
        ]);
    }



    /**
     * @Route("/list", name="list")
     */
    public function list(Request $request, UserInterface $user, $products=null)
    {

        $products = $this->getDoctrine()->getRepository(Product::class)->findAll();

        return $this->render('product/index.html.twig', [
            'products' => $products
        ]);
    }





    /**
     * @Route("/list/{projectId}", name="list_byProject")
     */
    public function list_byProject(Request $request, UserInterface $user, $projectId)
    {

        $userId = $user->getId(); 

        $projectsId = $this->getDoctrine()->getRepository(Project::class)
            ->findProjectsId_byUserId($userId);


        $projectExist = false;

        foreach($projectsId as $field){
            if($field['project_id'] == $projectId )
            $projectExist = true;
        }

        if( $projectExist == true){
            $products = $this->getDoctrine()->getRepository(Product::class)
                ->findProducts_byProjectId($projectId);

////////////////////////
////////////////////////

        // dump($products);


        foreach($products as $product){

            $productId = $product->getId();
            $items = $this->getDoctrine()->getRepository(Item::class)->findItems_byProductId($productId);

            foreach($items as $item){
                $product = $product->addItem($item);
            }
        }

        // dump($products);





// $query = $this->getDoctrine()
//     ->getRepository(Product::class)
//     ->createQueryBuilder('p')
//     ->getQuery();
// $result_product = $query->getResult(Query::HYDRATE_ARRAY);

// dump($result_product);



//         foreach($result_product as $product){
// $productId = $product['id'];


//     $items = $this->getDoctrine()->getRepository(Item::class)
//                 ->findItems_byProductId($productId);

// dump($items);






//         }





////////////////////////
////////////////////////

        }
        else{
            $products=0;
        }

        // return $this->render('product/index-base.html.twig', [
        return $this->render('product/index.html.twig', [

            'products' => $products
        ]);
    }








// create do poprawy


    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {

        $product = new Product();

        $form = $this->createFormBuilder($product)
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
            ->add('image', FileType::class, 
            	[
	                'mapped' => false,
	                'required' => false,
	            ]
	        )
            ->add('save', SubmitType::class, 
            	[
                	'attr' => [ 'class' => 'btn btn-primary float-right' ]
	            ]
	        )
            ->getForm();

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product.list');
        }


        return $this->render('product/create-update.html.twig', [
            'form' => $form->createView()
        ]);

    }





    /**
     * @Route("/update/{id}", name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, $id)
    {

        $product = new Product();
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $form = $this->createFormBuilder($product)
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
            ->add('image', FileType::class, 
            	[
	                'mapped' => false,
	                'required' => false,
	            ]
	        )
            ->add('save', SubmitType::class, 
            	[
                	'attr' => [ 'class' => 'btn btn-primary float-right' ]
	            ]
	        )
            ->getForm();

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            // $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            // $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product.list');
        }


        return $this->render('product/create-update.html.twig', [
            'form' => $form->createView()
        ]);

    }





    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, $id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        // $this->addFlash('success', 'Post was removed');
        return $this->redirectToRoute('home');
    }





    /**
     * @Route("/{id}", name="show")
     */
    public function show($id)
    {
        $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }





    /**
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

                $product = $em->getRepository(Product::class)->find($index);
                $product->setPosition($newPosition);

                $em->persist($product);
            }
        }
        $em->flush();

        return new Response();
    }











}
