<?php

    // Creating routes

    // Psr-7 Request and Response interfaces
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Message\ResponseInterface as Response;
    
    // HOME ROUTE
    //
  
    $app->get('/home', 'HomeController:home')->setName('home');
    $app->get('/', 'HomeController:index')->setName('index'); 
    $app->get('/about','HomeController:about')->setName('about');
    $app->get('/contact', 'HomeController:contact')->setName('contact');
    $app->post('/createContact' , 'HomeController:createContact')->setName('createContact');
    
    //
    //
    // TESTECONTROLLER
 
    $app->post('/hometeste', 'TesteController:hometeste')->setName('teste');
   
    $app->get('/logout', 'TesteController:logout')->setName('logout');
    $app->post('/login', 'TesteController:login')->setName('login');
    $app->get('/GetcontactID', 'TesteController:GetcontactID')->setName('GetcontactID');
    $app->post('/putContact', 'TesteController:putContact')->setName('putContact');
    $app->get('/DeleteContact' , 'TesteController:DeleteContact')->setName('DeleteContact');
    //
    



 