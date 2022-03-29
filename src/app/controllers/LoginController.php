<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use App\components\MyValidation as validation;


class LoginController extends Controller
{

    /**
     * log in user
     *
     * @return void
     */
    public function indexAction()
    {
        if ($_POST) {
            $escaper = new \App\components\MyEscaper();
            $email = $escaper->sanitize($this->request->getPost('email'));
            $password = $escaper->sanitize($this->request->getPost('password'));
            if (!empty($email) and !empty($password)) {
                $user = Users::findFirst("email='" . $email . "' and password = '" . $password . "'");
                if ($user) {
                    if ($user->status == 'approved') {
                        $session = $this->di->get('session');
                        $session->set('user', array('name' => $user->name, 'role' => $user->role, 'id' => $user->user_id));
                        if ($this->request->getPost('remember') == '1') {
                            $this->cookies->set(
                                'remember-me',
                                $user->email,
                                time() + 1 * 86400
                            );
                            $this->cookies->send();
                        }
                        $this->loginLogger->info('User logged in.user_id = ' . $user->user_id);
                        $this->response->redirect("index/dashboard");
                    } else {
                        $this->loginLogger->warning('Restricted user try to login user_id = ' . $user->user_id);
                        $this->flash->error("You are restricted to login by admin.");
                    }
                } else {
                    $this->loginLogger->alert('Incorrect credentials entered by user.');
                    $this->flash->error("E-mail or password is wrong.");
                }
            } else {
                $this->flash->info("One or more field is empty.");
            }
        }
    }

    /**
     * log in user if cookie is set
     *
     * @param [type] $email
     * @return void
     */
    public function loginByCookieAction($email)
    {
        $user = Users::findFirst("email='" . $email . "'");
        $session = $this->di->get('session');
        $session->set('name', $user->name);
        $session->set('email', $user->email);
        $session->set('id', $user->user_id);
        $this->response->redirect("index/dashboard");
    }

    /**
     * logout user
     *
     * @return void
     */
    public function logoutAction()
    {
        $this->di->get('session')->destroy();
        $this->cookies->get('remember-me')->delete();
        $this->response->redirect("index/index");
    }
}
