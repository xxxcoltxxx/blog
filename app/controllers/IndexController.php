<?php
use System\Controller;

class IndexController extends Controller
{
    public function actionIndex($current_page = 1)
    {
        $limit = 5;
        $this->view->title = "Блог";
        $user = UserModel::isAuthorized();
        $posts = PostModel::getPosts($limit, ($current_page - 1) * $limit);
        $total = PostModel::getPostCount();
        $pagination = new \Utils\Pagination($total, $limit, $current_page);
        return $this->view->htmlPage("index/index", compact("user", "posts", "pagination"));
    }
}
