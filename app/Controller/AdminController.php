<?php
namespace App\Controller;
use Slim\Views\Twig as View;
use App\Model\Contact;
use App\Model\User;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;


class AdminController
{
    private $em;
    private $container;
    private $flash;
    public function __construct($container ,EntityManager $em ,$flash)
    {
        $this->em = $em;
        $this->container=$container;
        $this->flash = $flash;
    }

    public function home(Request $request, Response $response, $args) 
    {
        if(isset($_SESSION['email'])){

        $contact =  $this->em->getRepository('App\Model\Contact')->findAll();
        return $this->container->view->render($response ,'admin/home.twig' ,Array( 'contact' => $contact));

        }else{
            
            $this->flash->addMessageNow('msg', 'Você não tem Acesso');
            $messages = $this->flash->getMessages();
            //var_dump($messages);
            return $this->container->view->render($response ,'index.twig'  ,Array( 'messages' => $messages));
        }
    }


    public function login($request, $response, $args)
    {
       

            $login  =  $this->em->getRepository('App\Model\User')->findAll();
           
                foreach($login as $l){
                    
                    if($l->getEmail() == $_POST['email'] AND $l->getSenha() == $_POST['senha']){
                      
                      
                        $_SESSION['email'] = $l->getEmail();
                        
                        if(isset($_SESSION['email'])){

                        $url = $this->container->get('router')->pathFor('home');
                        return $response->withStatus(302)->withHeader('Location', $url); 
                        
                        }else{
                            echo "A Session não existe" . $_SESSION['email'];
                        }        
                }else{
                    $this->flash->addMessageNow('msg', 'Você não tem Acesso');
                    $messages = $this->flash->getMessages();
                    //var_dump($messages);
                    return $this->container->view->render($response ,'index.twig'  ,Array( 'messages' => $messages)); 
                   
                }

          

        }
    }
    


public function logout($request, $response, $args)
{

    session_destroy();
    $url = $this->container->get('router')->pathFor('index');
    return $response->withStatus(302)->withHeader('Location', $url); 

}
    // New Contact //
    public function hometeste($request, $response, $args)
    {
       
        
        $contact = new Contact();
        $this->em->persist($contact);
        $contact->setName($_POST['name']);
        $contact->setEmail($_POST['email']);
        $contact->setTelefone($_POST['telefone']);
        $contact->setText($_POST['message']);
        $contact->setPublicationDate(new \DateTime());
        $this->em->flush();
        return $this->container->view->render($response ,'contact.twig');
       
        
}

    // GET Contact By Id //
    public function GetcontactID($request, $response, $args)
    {
        $contact =  $this->em->getRepository('App\Model\Contact')->findBy(Array('id' => $_GET['id']));
        return $this->container->view->render($response ,'admin/updatecontato.twig',Array( 'contact' => $contact));
    }

    // Update Contact //
    public function putContact($request, $response, $args)
    {  
  
       if(isset($_SESSION['email'])){
        $contact =  $this->em->find('App\Model\Contact',$_POST['id']);
        $contact->setEmail($_POST['email']);
        $contact->setTelefone($_POST['telefone']);
        $contact->setPublicationDate(new \DateTime());
        $this->em->flush();
    // Retornando o nome da rota 
        $url = $this->container->get('router')->pathFor('home');
        return $response->withStatus(302)->withHeader('Location', $url);

       }else{
        $this->flash->addMessageNow('msg', 'Você não tem Acesso');
        $messages = $this->flash->getMessages();
        //var_dump($messages);
        return $this->container->view->render($response ,'index.twig'  ,Array( 'messages' => $messages));   
       }
    }

    // Delete Contact //

    public function DeleteContact($request, $response, $args)
    {
        if(isset($_SESSION['email'])){
        $contact =  $this->em->find('App\Model\Contact',$_GET['id']);
        $this->em->remove($contact);
        $this->em->flush();

         // Retornando o nome da rota 
         $url = $this->container->get('router')->pathFor('home');
         return $response->withStatus(302)->withHeader('Location', $url);
        }else{
            $this->flash->addMessageNow('msg', 'Você não tem Acesso');
            $messages = $this->flash->getMessages();
            //var_dump($messages);
            return $this->container->view->render($response ,'index.twig'  ,Array( 'messages' => $messages));  
        }
    }

}