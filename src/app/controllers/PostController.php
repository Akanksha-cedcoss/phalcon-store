<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class PostController extends Controller
{
    /**
     * post and show descriptions posted by users
     *
     * @return void
     */
    public function indexAction()
    {
        if ($this->request->getPost()) {
            $escaper = new \App\components\MyEscaper();
            $description = $escaper($this->request->getPost('description'));
            $user_id = $this->session->id;
            $post = new Posts();
            try {
                $post->assign(
                    array('user_id' => $user_id, 'description' => $description),
                    [
                        'user_id',
                        'description',
                    ]
                );
                $post->save();
                $posts = Posts::find();
                $details = '';
                foreach ($posts as $post) {
                    $details .= "User_id => " . $post->user_id . " || description => " . $post->description . "<hr>";
                }
                $this->response->setContent($details);
            } catch (Exception $e) {
                $this->response->setContent("Something went wrong.");
            }
        }
    }
}
