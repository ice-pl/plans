<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


use App\Repository\ItemRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;


use App\Entity\Item;


use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use App\Entity\Product;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Component\HttpFoundation\JsonResponse;

use \stdClass;

use \DateTime;


use App\Entity\SampleItem;




/**
 * @Route("/item", name="item.")
 */
class ItemController extends AbstractController
{




    /**
     * @Route("/list", name="list")
     */
    public function list(ItemRepository $itemRepository)
    {
        $items = $itemRepository->findAll();

        return $this->render('item/index.html.twig', [
            'items' => $items
        ]);
    }



    /**
     * @Route("/list_base", name="list_base")
     */
    public function list_base(Request $request)
    {

        $items = $this->getDoctrine()->getRepository(Item::class)->findAll();

        return $this->render('item/index-base.html.twig', [
            'items' => $items
        ]);
    }








    /**
     * @Route("/list/{productId}", name="list_byProduct")
     */
    public function list_byProduct(Request $request, $productId)
    {

        $items = $this->getDoctrine()->getRepository(Item::class)
                ->findItems_byProductId($productId);

        $product = $this->getDoctrine()->getRepository(Product::class)
                ->find($productId);
        // return $this->render('item/index-base.html.twig', [
        return $this->render('item/index.html.twig', [

            'items' => $items,
            'product' => $product,

        ]);
    }















    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     */
    public function create(Request $request)
    {
        $item = new Item();

        $form = $this->createFormBuilder($item)
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
            $item = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($item);
            $em->flush();

            // return $this->redirectToRoute('item.list_base');
            return $this->redirectToRoute('item.list');
        }


        return $this->render('item/create-update.html.twig', [
            'form' => $form->createView()
        ]);

    }






    /**
     * @Route("/update/{id}", name="update", methods={"GET", "POST"})
     */
    public function update(Request $request, $id)
    {
        $item = new Item();
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);

        $form = $this->createFormBuilder($item)
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
            // $item = $form->getData();

            $em = $this->getDoctrine()->getManager();
            // $em->persist($item);
            $em->flush();

            // return $this->redirectToRoute('item.list_base');
            return $this->redirectToRoute('item.list');
        }


        return $this->render('item/create-update.html.twig', [
            'form' => $form->createView()
        ]);

    }








    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Request $request, $id)
    {
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);
        $entityManager->flush();


        // $this->addFlash('success', 'Post was removed');
        // return $this->redirectToRoute('home');
        // return $this->redirectToRoute('item.list_base');
        return $this->redirectToRoute('item.list');
    }





    /**
     * @Route("/{id}", name="show")
     */
    public function show($id)
    {
        $item = $this->getDoctrine()->getRepository(Item::class)->find($id);

        return $this->render('item/show.html.twig', [
            'item' => $item
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

                $item = $em->getRepository(Item::class)->find($index);
                $item->setPosition($newPosition);

                $newDateTime = new DateTime();
                if($newPosition>0 && $newPosition<=20){
                    // $item->setStartTime(null);
                    // $item->setEndTime(null);
                    // $item->setIntervalTime(null);
                    // $item->setDelayTime(null);
                    $em->persist($item);
                    $em->flush();
                }

                if($newPosition>20 && $newPosition<=40){
                    $item->setStartTime($newDateTime);
                    $em->persist($item);
                    $em->flush();
                }
                if($newPosition>40 && $newPosition<=60){
                    $item->setEndTime($newDateTime);

                    $intervalTime = $newDateTime->getTimestamp() - $item->getStartTime()->getTimestamp();
                    $item->setIntervalTime($intervalTime);

                    $em->persist($item);
                    $em->flush();



                    $allItemsThatType = $this->getDoctrine()->getRepository(Item::class)->findAllItems_byParentId($item->getParentId());

// interval
                    $howManyItemsForInterval = 0;
                    $cumIntervalTime = 0;
                    foreach($allItemsThatType as $count => $val){
                        if($val['interval_time'] == null){
                            $howManyItemsForInterval-=1;
                        }else{
                            $cumIntervalTime = $cumIntervalTime + $val['interval_time'];
                        }
                        $howManyItemsForInterval+=1;
                    }

                    $averageIntervalTime = 0;
                    if($howManyItemsForInterval != 0){
                        $averageIntervalTime = $cumIntervalTime/$howManyItemsForInterval;
                    }

                    $sampleItem = $em->getRepository(SampleItem::class)->find($item->getParentId());
                    $sampleItem->setIntervalCounted($averageIntervalTime);
                    $em->persist($sampleItem);
                    $em->flush();

// delay


                    foreach($allItemsThatType as $key => $value){
                        $itemForDelay = $em->getRepository(Item::class)->find($value['id']);
                        $delayTime = $itemForDelay->getIntervalTime() - $sampleItem->getIntervalCounted();
                        $itemForDelay->setDelayTime($delayTime);
                        $em->persist($itemForDelay);
                        $em->flush();
                    }

                    $counter = 0;
                    $cum = 0;
                    foreach($allItemsThatType as $key => $value){
                        $itemForAverageDelay = $em->getRepository(Item::class)->find($value['id']);
                        if( $itemForAverageDelay->getDelayTime() > 0){
                            $counter += 1;
                            $cum = $cum + $itemForAverageDelay->getDelayTime();
                        }
                    }
                    if($counter > 0){
                        $averageDelayTime = $cum/$counter;
                        $sampleItem->setDelayCounted($averageDelayTime);
                    }

                    $em->persist($sampleItem);
                    $em->flush();


                }


                // $em->persist($item);
            }
        }
        // $em->flush();

        return new Response();
    }




    /**
     * @Route("/description/{itemId}", name="description")
     */
    public function description(Request $request, $itemId)
    {

//         $item = $this->getDoctrine()->getRepository(Item::class)
//             ->findItemDescription_byId($itemId);
//         $description = $item[0]['description'];

        $description = $this->getDoctrine()->getRepository(Item::class)->find($itemId)->getDescription();
        $name = $this->getDoctrine()->getRepository(Item::class)->find($itemId)->getName();

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



    /**
     * @Route("/count/{productId}", name="count_byProduct")
     */
    public function count_byProduct(Request $request, $productId)
    {

        $items = $this->getDoctrine()->getRepository(Item::class)
                ->findItems_byProductId($productId);

        $todo = array();
        $todo['value'] = 0;
        $todo['elements'] = 0;


        $doing = array();
        $doing['value'] = 0;
        $doing['elements'] = 0;


        $done = array();
        $done['value'] = 0;
        $done['elements'] = 0;


        foreach($items as $field){
            if( ($field->getPosition()) >= 1 && ($field->getPosition()) <= 20 ){
                $todo['value'] = $todo['value'] + $field->getValue();
                $todo['elements'] += 1;
            }

            if( ($field->getPosition()) >= 21 && ($field->getPosition()) <= 40 ){
                $doing['value'] = $doing['value'] + $field->getValue();
                $doing['elements'] += 1;
            }

            if( ($field->getPosition()) >= 41 && ($field->getPosition()) <= 60 ){
                $done['value'] = $done['value'] + $field->getValue();
                $done['elements'] += 1;
            }
        }

        $undone = array();
        $undone['value'] = $todo['value'] + $doing['value'];
        $undone['elements'] = $todo['elements'] + $doing['elements'];







// dump($items);
// dump($todo['value']);
// dump($todo['elements']);

// dump($doing['value']);
// dump($doing['elements']);

// dump($done['value']);
// dump($done['elements']);

// dump($undone['value']);
// dump($undone['elements']);

// die;
        // return $this->render('item/index-base.html.twig', [
        // return $this->render('item/index.html.twig', [
        //     'items' => $items
        // ]);


        return new JsonResponse(
            [ 
                'todo' =>
                [ 
                    'value' => $todo['value'],
                    'elements' => $todo['elements'],
                ],
                'doing' =>
                [ 
                    'value' => $doing['value'],
                    'elements' => $doing['elements'],
                ],
                'done' =>
                [ 
                    'value' => $done['value'],
                    'elements' => $done['elements'],
                ],
                'undone' =>
                [ 
                    'value' => $undone['value'],
                    'elements' => $undone['elements'],
                ],
            ]
        );
        // return new Response();


    }





}
