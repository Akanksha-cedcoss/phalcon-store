<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {
        if ($this->cookies->has('remember-me')) {
            $email = $this->cookies->get('remember-me')->getValue();
            $this->response->redirect("login/loginByCookie/" . $email . "");
        }
    }

    /**
     * if user not logged in redirect him to index page
     *
     * @return void
     */
    public function dashboardAction()
    {
        if (!($this->di->get('session')->has('id') or $this->cookies->has('remember-me'))) {
            $this->response->redirect("index/index");
        }
        $this->view->name = $this->di->get('session')->name;
    }
}
