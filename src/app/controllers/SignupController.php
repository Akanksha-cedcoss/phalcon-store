<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{

    /**
     * sign up user
     *
     * @return void
     */
    public function indexAction()
    {
        if ($_POST) {
            $escaper = new \App\components\MyEscaper();
            $email = $escaper->sanitize($this->request->getPost('email'));
            $name = $escaper->sanitize($this->request->getPost('name'));
            $password = $escaper->sanitize($this->request->getPost('password'));
            $password2 = $escaper->sanitize($this->request->getPost('password2'));
            if (empty($email) or empty($name) or empty($password) or empty($password2)) {
                $this->response->setContent("One or more field is empty.");
                return;
            }
            if ($password != $password2) {
                $this->response->setContent("Password did'not match.");
                return;
            }
            $user = new Users();
            try {
                $user->assign(
                    array('name' => $name, 'email' => $email, 'password' => $password),
                    [
                        'name',
                        'email',
                        'password'
                    ]
                );
                $user->save();
                if ($user) {
                    $session = $this->di->get('session');
                    $session->set('name', $user->name);
                    $session->set('email', $user->email);
                    $session->set('id', $user->user_id);
                    $this->response->redirect("index/dashboard");
                } else {
                    $this->response->setContent($user->getMessages());
                    $this->signupLogger->error($user->getMessages());
                }
            } catch (Exception $e) {
                $this->signupLogger->alert("This E-mail is already registered with us.");
                $this->response->setContent('This E-mail is already registered with us.');
            }
        }
    }
}
