<?php

use Data\Handlers\Validate;

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
        $user = UserModel::isAuthorized();
        if (!$user) {
            $this->redirect("/user/auth");
        }
        Validate::check($post->user_id == $user->id, "У вас не прав для редактирования этого блога", 403);
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
            Validate::check($post->user_id == $user->id, "У вас не прав для редактирования этого блога", 403);
        } else {
            $post = new PostModel();
        }
        $post->setData($data);
        $post->save();
        $this->redirect("/");
    }

    public function actionDelete($id)
    {
        $user = UserModel::isAuthorized();
        if (!$user) {
            $this->redirect("/user/auth");
        }

        $post = PostModel::get($id);
        Validate::check($post->user_id == $user->id, "У вас не прав для редактирования этого блога", 403);

        $post->delete();
        $this->redirect("/");
    }

    public function actionShow($id)
    {
        $user = UserModel::isAuthorized();
        $post = PostModel::get($id);
        $this->view->title = $post->title;
        $message = $this->flash;
        return $this->view->htmlPage("post/show", compact("post", "user", "message"));
    }

    public function actionSaveComment($post_id)
    {
        $post = PostModel::get($post_id);
        $input = \System\Engine::getInput();
        $data = $input->getArray("data");
        $data = array_intersect_key($data, array_flip(["name", "message"]));

        $user = UserModel::isAuthorized();
        $data['post_id'] = $post_id;
        $data['message'] = strip_tags($data['message']);
        if ($user) {
            $data['user_id'] = $user->id;
        } else {
            $data['name'] = strip_tags($data['name']);
        }

        try {
            $comment = new PostCommentModel();
            $comment->setData($data);
            $comment->save();
            $this->redirect("/post/show/{$post->id}#comments");
        } catch (Exception $e) {
            $this->redirect("/post/show/{$post->id}#comments", $e->getMessage());
        }
    }
}