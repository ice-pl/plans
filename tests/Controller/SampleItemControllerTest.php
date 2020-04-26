<?php

namespace App\Tests\Controlller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
// use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;



// use App\Controller\SampleItemController;
// use Symfony\Component\HttpFoundation\Request;

// use Symfony\Component\BrowserKit\Cookie;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


// use App\Entity\User;
// use Symfony\Component\Console\Tester\CommandTester;


// use App\Entity\User;
// use Symfony\Bundle\FrameworkBundle\KernelBrowser;
// use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
// use Symfony\Component\BrowserKit\Cookie;
// use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;




class SampleItemControllerTest extends WebTestCase
{








    // public function testShowPost()
    // {
    //     $client = static::createClient();


    //     // $client = static::createClient([], [
    //     //     'PHP_AUTH_USER' => 'john_user',
    //     //     'PHP_AUTH_PW' => 'kitten',
    //     //     'last_username' => 'kitten',
    //     // ]);

    //     $client->request('GET', '/sample_item/list');

    //     // $this->assertEquals(200, $client->getResponse()->getStatusCode());
    //     // self::assertTrue($response->isSuccessful());
    //     // $this->assertTrue($response->isSuccessful());
    //     $this->assertTrue($client->getResponse()->isSuccessful());

    // }


    // /**
    //  * @dataProvider urlProvider
    //  */
    // public function testPageIsSuccessful($url)
    // {
    //     $client = self::createClient();
    //     $client->request('GET', $url);

    //     $this->assertTrue($client->getResponse()->isSuccessful());
    // }

    // public function urlProvider()
    // {
    //     yield ['/sample_item/list'];
    //     yield ['/sample_item/list_base'];
    //     yield ['/sample_item/list/3'];
    //     yield ['/sample_item/update/3'];
    //     yield ['/sample_item/3'];
    //     yield ['/sample_item/description/3'];
    //     // yield ['/sample_item/emptyDescription'];
    //     yield ['/sample_item/create'];
    // }


    public function testWhatBrowserShowInDescription()
    {
        $client = static::createClient();
        $client->request('GET', '/sample_item/description/3');

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
        );
        $this->assertContains('{"name":"to jest i3","description":"i3"}', $client->getResponse()->getContent());
    }







//     public function testDescription()
//     {
//         // $controller = new SampleItemController;

//         // $form = $this
//         //     ->getMockBuilder('Symfony\Tests\Component\Form\FormInterface')
//         //     ->setMethods(array('createView'))
//         //     ->getMock()
//         // ;
//         // $form
//         //     ->expects($this->once())
//         //     ->method('createView')
//         // ;

//         // $formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
//         // $formFactory
//         //     ->expects($this->once())
//         //     ->method('create')
//         //     ->will($this->returnValue($form))
//         // ;

//         // $templating = $this->getMock('Symfony\Component\Templating\EngineInterface');
//         // $templating
//         //     ->expects($this->once())
//         //     ->method('render')
//         // ;

//         // $controller->setFormFactory($formFactory);
//         // $controller->setTemplating($templating);

//         // $controller->registerAction(new Request);
//         // 
//         // 
        
// $stub = $this->createStub(SampleItemController::class);
// $stub->method('doSomething')
//              ->willReturn('foo');

//              $this->assertSame('foo', $stub->doSomething());

//     }






    // public function testAdminDeletePost()
    // {
    //     $client = static::createClient([], [
    //         'PHP_AUTH_USER' => 'jane_admin',
    //         'PHP_AUTH_PW' => 'kitten',
    //     ]);
    //     $crawler = $client->request('GET', '/en/admin/post/1');
    //     $client->submit($crawler->filter('#delete-form')->form());

    //     $this->assertResponseRedirects('/en/admin/post/', Response::HTTP_FOUND);

    //     $post = $client->getContainer()->get('doctrine')->getRepository(Post::class)->find(1);
    //     $this->assertNull($post);
    // }


    // public function testAdminDeletePost()
    // {
    //     $client = static::createClient([], [
    //         'PHP_AUTH_USER' => 'admin1',
    //         // 'PHP_AUTH_PW' => '1$NldRNC9Nbllzcmp3L2FMbw$0Tj2dlqS3AjgW0G5UDPEixfiLGIEHodlLrk15210wIM',
    //         'PHP_AUTH_PW' => '$argon2id$v=19$m=65536,t=4,p=1$NldRNC9Nbllzcmp3L2FMbw$0Tj2dlqS3AjgW0G5UDPEixfiLGIEHodlLrk15210wIM',
    //     ]);
    //     // $crawler = $client->request('GET', '/en/admin/post/1');
    //     // $client->submit($crawler->filter('#delete-form')->form());

    //     // $this->assertResponseRedirects('/en/admin/post/', Response::HTTP_FOUND);

    //     // $post = $client->getContainer()->get('doctrine')->getRepository(Post::class)->find(1);
    //     // $this->assertNull($post);

    //     $client->request('GET', '/');
    //     $this->assertTrue($client->getResponse()->isSuccessful());



    // }


    // private $client = null;

    // public function setUp()
    // {
    //     $this->client = static::createClient();
    // }

    // public function testSecuredHello()
    // {
    //     $this->logIn();
    //     $crawler = $this->client->request('GET', '/');

    //     $this->assertSame(Response::HTTP_OK, $this->client->getResponse()->getStatusCode());
    //     // $this->assertSame('Admin Dashboard', $crawler->filter('h1')->text());
    // }

    // private function logIn()
    // {
    //     $session = self::$container->get('session');

    //     $firewallName = 'secure_area';
    //     // if you don't define multiple connected firewalls, the context defaults to the firewall name
    //     // See https://symfony.com/doc/current/reference/configuration/security.html#firewall-context
    //     $firewallContext = 'secured_area';

    //     // you may need to use a different token class depending on your application.
    //     // for example, when using Guard authentication you must instantiate PostAuthenticationGuardToken
    //     $token = new UsernamePasswordToken('admin', null, $firewallName, ['ROLE_ADMIN']);
    //     $session->set('_security_'.$firewallContext, serialize($token));
    //     $session->save();

    //     $cookie = new Cookie($session->getName(), $session->getId());
    //     $this->client->getCookieJar()->set($cookie);
    // }


   //  protected function createAuthorizedClient()
   //  {
   //      $client = static::createClient();
   //      $container = static::$kernel->getContainer();
   //      $session = $container->get('session');
   //      $person = self::$kernel->getContainer()->get('doctrine')->getRepository('FoxPersonBundle:Person')->findOneByUsername('master');

   //      $token = new UsernamePasswordToken($person, null, 'main', $person->getRoles());
   //      $session->set('_security_main', serialize($token));
   //      $session->save();

   //      $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

   //      return $client;
   //  }

   // public function testView()
   //  {
   //      $client = $this->createAuthorizedClient();
   //      $crawler = $client->request('GET', '/');
   //      $this->assertEquals(
   //          200,
   //          $client->getResponse()->getStatusCode()
   //      );



    // abstract protected function getCurrentUser();

    // /**
    //  * @param string $firewallName
    //  * @param array $options
    //  * @param array $server
    //  * @return Symfony\Component\BrowserKit\Client
    //  */
    // protected function createClientWithAuthentication($firewallName, array $options = array(), array $server = array())
    // {
    //     /* @var $client \Symfony\Component\BrowserKit\Client */
    //     $client = $this->createClient($options, $server);
    //     // has to be set otherwise "hasPreviousSession" in Request returns false.
    //     $client->getCookieJar()->set(new \Symfony\Component\BrowserKit\Cookie(session_name(), true));
        
    //     /* @var $user UserInterface */
    //     $user = $this->getCurrentUser();
        
    //     $token = new UsernamePasswordToken($user, null, $firewallName, $user->getRoles());
    //     self::$kernel->getContainer()->get('session')->set('_security_' . $firewallName, serialize($token));
        
    //     return $client;
    // }




}