<?php

use Phalcon\Mvc\Controller;

/**
 * try class to use class(helper) in some namespace
 */
class TryController extends Controller
{
    public function indexAction()
    {
        echo 'App name from congif = ' . $this->di->get('config')->app->name;
        echo "<hr>";

        $helper = new \App\components\Helper();
        echo $helper->getTitle();
        $helper->setTitle("New title");
        echo "<br>" . $helper->getTitle();
        // print_r($this->di->get('config')->db);
    }
}
