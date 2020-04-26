<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\User;

use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;

use App\Entity\Project;



class RegistrationController extends AbstractController
{



    /**
     * @Route("/register", name="register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $form = $this->createFormBuilder()
            ->add('username')
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
            ])
            ->add('register', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success float-right',
                ],
            ])
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $user = new User();


            try{
                if(!$data['username'] || !$data['password']){
                    return new JsonResponse([
                        'status' => 'error',
                        'info' => 'name and password must be defined'
                    ]);
                }
                $user->setUsername($data['username']);
            }
            catch(\Exception $e) {
                return new JsonResponse(['status' => 'error']);
            }


            $user->setPassword(
                $passwordEncoder->encodePassword($user, $data['password'])
            );
            $user->setRoles($user->getRoles());


            try{
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
            catch(\Exception $e) {
                return new JsonResponse([
                    'status' => 'error',
                    'info' => 'choose another name'
                ]);
            }

            return new JsonResponse(['status' => 'success']);
        }


        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);


    }






    /**
     * @Route("/admin_change_password/{id}", name="admin_change_password")
     */
    public function admin_change_password(Request $request, UserPasswordEncoderInterface $passwordEncoder, $id, UserInterface $user) {
        
        $form = $this->createFormBuilder()

            ->add('new_password', PasswordType::class, [
                'required' => true,
                'label' => 'New password'
            ])

            ->add('Change_Password', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success float-right',
                ],
                'label' => 'Submit'
            ])
            ->getForm();
        $form->handleRequest($request);

        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $user->setPassword($passwordEncoder->encodePassword($user, $data['new_password']));
            // $user->setPassword($data['new_password']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
        }

        return $this->render('registration/reset-by-admin.html.twig', [
            'form' => $form->createView(),
        ]);

    }




    /**
     * @Route("/change_password", name="change_password")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function change_password(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        
        $form = $this->createFormBuilder()
            ->add('old_password', PasswordType::class, [
                'required' => true,
                'label' => 'Old password'
            ])
            ->add('new_password', PasswordType::class, [
                'required' => true,
                'label' => 'New password'
            ])
            ->add('confirm_new_password', PasswordType::class, [
                'required' => true,
                'label' => 'Confirm new password'
            ])
            ->add('Change_Password', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success float-right',
                ],
                'label' => 'Submit'
            ])
            ->getForm();
        $form->handleRequest($request);


        $user = $this->getUser();
        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $old_pwd = $data['old_password']; 
            $new_pwd = $data['new_password'];
            $cfm_pwd = $data['confirm_new_password'];

            try{
                if(!$old_pwd || !$new_pwd || !$cfm_pwd){
                    return new JsonResponse([
                        'status' => 'error',
                        'info' => 'all passwords must be defined'
                    ]);
                }
                if($new_pwd != $cfm_pwd){
                    return new JsonResponse([
                        'status' => 'error',
                        'info' => 'new passwords must be the identical'
                    ]);                    
                }

                $checkPass = $passwordEncoder->isPasswordValid($user, $old_pwd);

                if($checkPass === true) {
                    $user->setPassword($passwordEncoder->encodePassword($user, $new_pwd));
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($user);
                    $em->flush();
                }
                else{
                    return new JsonResponse([
                        'status' => 'error',
                        'info' => 'current password is incorrect'
                    ]);
                }

            }
            catch(\Exception $e) {
                return new JsonResponse(['status' => 'error']);
            }
        }


        return $this->render('registration/reset.html.twig', [
            'form' => $form->createView(),
        ]);

    }



    /**
     * @Route("/admin_panel", name="admin_panel")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function admin_panel(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserInterface $user) {
        
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        // dump($users);

        return $this->render('registration/admin.html.twig', [
            'users' => $users,
            'currentUser' => $user,
        ]);


    }



    /**
     * @Route("/user_panel", name="user_panel")
     */
    public function user_panel(Request $request, UserInterface $user) {

// $user = new User();
        // $userId = 1;
        $userId = $user->getId();
        $questions = $this->getDoctrine()->getRepository(Project::class)->findProjectsName_byOwnerId($userId);
// dump($questions);

// fin userif in repo
        $answers = $this->getDoctrine()->getRepository(Project::class)->findProjectsName_byUserIdInside($userId);
// dump($answers);


        return $this->render('registration/user-page.html.twig', [
            'questions' => $questions,
            'answers' => $answers,
            'userId' => $userId,
        ]);


    }









    /**
     * @Route("/change_role/{id}/{roleName}", name="change_role", methods={"GET", "POST"})
     */
    public function change_role(Request $request, $id, $roleName, UserInterface $user)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        
        if($roleName == 'ROLE_USER'){
            $roleName = [];
        }
        else{
            $roleName = [$roleName];
        }
        
        $user->setRoles($roleName);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return new Response();
    }










}
