<?php

namespace Rimote\ValidationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('RimoteValidationBundle:Default:index.html.twig');
    }
}
