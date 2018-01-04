<?php
namespace CodeExperts\App\Controller;

use CodeExperts\MVC\BaseController;
use CodeExperts\MVC\View;
use CodeExperts\Tools\{
    PasswordHash,
    Session
};
use CodeExperts\DB\Connection;
use CodeExperts\App\Entity\User;
use CodeExperts\App\Entity\RememberPassword;
use CodeExperts\App\Service\{
    Authenticator,
    Sender
};

class AuthController extends BaseController
{
    public function login()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;

            try {
                $user = new User(Connection::getInstance($this->getConfig('database')));

                $authenticator = new Authenticator($data, $user);

                $user = $authenticator->authenticate();

                $authenticator->setUserInSession(new Session(), $user);

                return $this->redirect('admin/user');

            } catch(\Exception $e) {
                $this->addFlash('warning', $e->getMessage());

                return $this->redirect('auth/login');
            }

        }

        $view = new View(VIEWS_FOLDER . 'auth/login.phtml');

        return $view->render();
    }

    public function logout()
    {
        $session = (new Session())->sessionStart();

        if(isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            session_destroy();
            $_SESSION = array();
        }
        $this->addFlash('success', 'Logout efetuado com sucesso!');
        return $this->redirect('auth/login');
    }

    public function rememberpassword()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;

            $user = new User(Connection::getInstance($this->getConfig('database')));

            $user = $user->where(['email' => $data['email']]);

            if(!$user) {
                $this->addFlash('warning', 'Nada encontrado pra esse registro!');
                return $this->redirect('auth/rememberpassword');
            }

            $remember = new RememberPassword(Connection::getInstance($this->getConfig('database')));
            $remember->user_id = $user['id'];
            $remember->remember_token = uniqid() . sha1($user['email']);
            $remember->expires = date("Y-m-d", strtotime("+1 day"));

            if(!$remember->insert()) {
                $this->addFlash('warning', 'Erro ao registrar solicitação!');
                return $this->redirect('auth/rememberpassword');
            }

            $mailer = new \PHPMailer();
            $sendEmail = new Sender($mailer);

            $smtpConf = $this->getConfig('mailer');
            $sendEmail->setDataSend($smtpConf);

            $view = new View(VIEWS_FOLDER . '/email/remember-password.phtml');

            $view->user = $user['name'];
            $view->token = $remember->remember_token;
            $view = $view->render();

            $sendEmail->send($view, 'Você solicitou atualização de senha.', $user);

            $this->addFlash('success', 'Enviamos os procedimentos para seu email!');
            return $this->redirect('auth/rememberpassword');

        }

        $view = new View(VIEWS_FOLDER . 'auth/email.phtml');

        return $view->render();
    }

    public function updatepassword($token)
    {
        if(!$token) {
            $this->addFlash('error', 'Token Inválido!');
            return $this->redirect('auth/rememberpassword');
        }

        $remember = new RememberPassword(Connection::getInstance($this->getConfig('database')));

        $tokenInfo = $remember->where(['remember_token' => strip_tags($token)]);

        if(!$tokenInfo) {
            $this->addFlash('error', 'Token não encontrado!');
            return $this->redirect('auth/rememberpassword');
        }

        if(date('Ymd') > date('Ymd', strtotime($tokenInfo['expires']))) {
            $this->addFlash('error', 'Token expirado!');
            return $this->redirect('auth/rememberpassword');
        }

        $session = (new Session())->sessionStart();

        $_SESSION['user_remember_token'] = $tokenInfo['user_id'];

        $view = new View(VIEWS_FOLDER . 'auth/password.phtml');
        $view->token = $token;
        return $view->render();
    }

    public function proccessupdate($token)
    {
        $requestType = $_SERVER['REQUEST_METHOD'];

        if($requestType == 'GET') {
            $this->addFlash('error', 'Método não permitido!');
            return $this->redirect('auth/rememberpassword');
        }

        if(!$token) {
            $this->addFlash('error', 'Token Inválido!');
            return $this->redirect('auth/rememberpassword');
        }

        $data = $_POST;

        if($data['password'] != $data['confirm_password']) {
            $this->addFlash('error', 'Senhas não conferem!');
            return $this->redirect('auth/updatepassword/' . $token);
        }

        $session = (new Session())->sessionStart();

        $password = new \CodeExperts\Tools\PasswordHash();
        $password = $password->setPassword($data['password'])->generate();

        $user = new User(Connection::getInstance($this->getConfig('database')));
        $user->id = $_SESSION['user_remember_token'];
        $user->password = $password;

        unset($_SESSION['user_remember_token']);
        session_destroy();

        if(!$user->update()) {
            $this->addFlash('error', 'Erro ao atualizar senha!');
            return $this->redirect('auth/updatepassword/' . $token);
        }

        $this->addFlash('success', 'Senha alterada com sucesso, faça login para continuar!');
        return $this->redirect('auth/login');
    }
}