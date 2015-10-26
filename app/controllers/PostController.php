<?php

class PostController extends \System\Controller
{
    public function actionAdd()
    {
        $this->view->title = "Добавление поста";
        return $this->view->htmlPage("post/edit");
    }

    public function actionEdit($id)
    {
        $this->view->title = "Редактирование поста";
        $post = PostModel::get($id);
        return $this->view->htmlPage("post/edit", ['post' => $post]);
    }

    public function actionSave($id = null)
    {
        $user = UserModel::isAuthorized();
        if (!$user) {
            $this->redirect("/user/auth");
        }
        $input = \System\Engine::getInput();
        $data = $input->getArray("data");

        $data = array_intersect_key($data, array_flip(['title', 'content']));
        $data['user_id'] = $user->id;

        if ($id) {
            $post = PostModel::get($id);
        } else {
            $post = new PostModel();
        }
        $post->setData($data);
        $post->save();
        $this->redirect("/");
    }
}