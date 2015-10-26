<?php


class UserController extends \System\Controller
{
    public function actionLogin()
    {
        $this->view->title = "Вход в систему";
        try {
            $input = \System\Engine::getInput();

            if ($input->exists("email")) {
                $email = $input->getStr("email", null, "Empty email field");
                $password = $input->getStr("password", null, "Empty Password field");

                UserModel::authorize($email, $password);
                $this->redirect("/");
            } else {
                return $this->view->htmlPage("user/auth");
            }
        } catch (Exception $e) {
            return $this->view->htmlPage("user/auth", ['message' => $e->getMessage()]);
        }
    }

    public function actionLogout()
    {
        unset($_SESSION['user']['id']);
        $this->redirect($this->referer, "Вы вышли из системы");
    }
}