<?php
use System\Controller;

class IndexController extends Controller
{
    public function actionIndex()
    {
        $this->view->title = "Блог";
        $user = UserModel::isAuthorized();
        $posts = PostModel::getPosts(5);
        return $this->view->htmlPage("index/index", compact("user", "posts"));
    }
}
