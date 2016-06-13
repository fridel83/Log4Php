<?php

namespace app\LoggerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('appLoggerBundle:Default:index.html.twig', array('name' => $name));
    }
}
