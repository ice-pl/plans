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

use App\Entity\UserProject;

use App\Entity\SampleProduct;
// use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use App\Entity\SampleItem;

use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/product", name="product.")
 */
class ProductController extends AbstractController
{








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

        // $projectsId = $this->getDoctrine()->getRepository(Project::class)->findProjectsId_byUserId($userId);



        $projectsId = $this->getDoctrine()->getRepository(UserProject::class)->findProjectId_byUserId($userId);


        $projectExist = false;

        foreach($projectsId as $field){
            // if($field['project_id'] == $projectId )
            if($field['projectId'] == $projectId )
            $projectExist = true;
        }

        if( $projectExist == true){
            $products = $this->getDoctrine()->getRepository(Product::class)
                ->findProducts_byProjectId($projectId);




            foreach($products as $product){

                $productId = $product->getId();
                $items = $this->getDoctrine()->getRepository(Item::class)->findItems_byProductId($productId);

                foreach($items as $item){
                    $product = $product->addItem($item);
                }
            }


        }
        else{
            $products=0;
        }

        return $this->render('product/index.html.twig', [

            'products' => $products,
            'projectId' => $projectId,
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
            // ->add('image', AddressType::class, 
            //     [
            //         'label_format' => 'form.address.%name%',
            //         'mapped' => false,
            //         'required' => false,
            //     ]
            // )
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
        $projectId = $product->getProject()->getId();
// dump($projectId);
// die;



        $sampleProduct = $this->getDoctrine()->getRepository(SampleProduct::class)->find($product->getParentId());
    
        $newHowMany = $sampleProduct->getHowMany() - 1;
        $sampleProduct->setHowMany( $newHowMany );
        if($newHowMany < 0){
            $sampleProduct->setHowMany( 0 );
        }



        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        $entityManager->flush();

        $entityManager->persist($sampleProduct);
        $entityManager->flush();




        // $this->addFlash('success', 'Post was removed');
        // return $this->redirectToRoute('home');
        return $this->redirectToRoute('product.list_byProject', array('projectId' => $projectId));
        // return new Response();
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







    /**
     * @Route("/createFromSample/{id}/{position}/{projectId}", name="createFromSample")
     */
    public function createFromSample(Request $request, $id, $position, $projectId)
    {


        $data = $request->request->all();

        if (isset($data['update'])){
            foreach($data['positions'] as $position){
                $index = $position[0];
                $newPosition = $position[1];

                $sampleProduct = $this->getDoctrine()->getRepository(SampleProduct::class)->find($index);
                $product = new Product();


                $oldProductReflection = new \ReflectionObject($sampleProduct);
                $newProductReflection = new \ReflectionObject($product);

                foreach ($oldProductReflection->getProperties() as $property) {

                    if ($newProductReflection->hasProperty($property->getName())) {
                        $newProperty = $newProductReflection->getProperty($property->getName());
                        $newProperty->setAccessible(true);
                        $property->setAccessible(true);
                        $newProperty->setValue($product, $property->getValue($sampleProduct));
                    }
                    $product->setPosition($newPosition);
                }

                $sampleItems = $sampleProduct->getSampleItems();

                foreach ($sampleItems as $sampleItem) {

                    $item = new Item();
                    $oldItemReflection = new \ReflectionObject($sampleItem);
                    $newItemReflection = new \ReflectionObject($item);


                    foreach ($oldItemReflection->getProperties() as $property) {
                        if ($newItemReflection->hasProperty($property->getName())) {
                            $newProperty = $newItemReflection->getProperty($property->getName());
                            $newProperty->setAccessible(true);
                            $property->setAccessible(true);
                            $newProperty->setValue($item, $property->getValue($sampleItem));
                        }
                        $item->setPosition($newPosition);
                        $item->setParentId($sampleItem->getId());
                    }
                    
                    $product->addItem($item);
                }
            }
        }

        $project = $this->getDoctrine()->getRepository(Project::class)->find($projectId);
        $product->setProject($project);
        $product->setParentId($sampleProduct->getId());
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        $newId = $product->getId();

// dump($product->getId());


        $sampleProduct->setHowMany( $sampleProduct->getHowMany() + 1 );
        $em->persist($sampleProduct);
        $em->flush();

// die;

// return new JsonResponse(['newId' => $newId]);
        return new Response($newId);


        // $response = new JsonResponse();
        // $response->setData(array('newId' => $newId));

        // return $response;





    }











}
