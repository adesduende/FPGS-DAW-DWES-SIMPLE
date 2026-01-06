<?php

namespace sportshop\app\controllers;

use sportshop\app\data\CartRepository;
use sportshop\app\data\UserRepository;
use sportshop\app\models\Cart;
use sportshop\app\models\User;
use sportshop\app\services\Auth;
use sportshop\app\services\Hash;
use sportshop\app\utils\GUID;

class AuthController extends ControllerBase
{
    protected readonly UserRepository $_userRepository;
    protected readonly CartRepository $_cartRepository;

    public function __construct(UserRepository $userRepository, CartRepository $cartRepository)
    {
        parent::__construct();
        $this->_userRepository = $userRepository;
        $this->_cartRepository = $cartRepository;
    }

    //[GET]
    public function SignInView(): void
    {
        session_start();

        $view = "/app/views/Auth/login.php";
        // Check if already logged in
        if (Auth::isLogin()) {
            header('Location: /');
            exit();
        }

        include LAYOUT;
    }

    //[GET]
    public function SignUpView(): void
    {
        if (Auth::isLogin()) {
            header('Location: /');
            exit();
        }
        $view = '/app/views/auth/signup.php';
        include LAYOUT;
    }

    //[POST]
    public function SignIn(array $request): void
    {
        $error = '';

        // Handle form submission

        $username = $request['username'] ?? '';
        $password = $request['password'] ?? '';

        // Basic validation
        if (empty($username) || empty($password)) {
            $error = 'Please fill in all fields';
        } else {
            $user = $this->_userRepository->GetByEmail($username);
            if (Hash::IsEqual($password, $user->HashedPassword)&&$user->IsActive) {
                session_start();
                $_SESSION['user_id'] = $user->Id->Id;
                $_SESSION['username'] = $user->Name;
                $_SESSION['user_rol'] = $user->Role;
                header('Location: /');
                exit();
            } elseif(!$user->IsActive) {
                $this->data['error'] = 'El usuario no ha sido activado';
                $view = "/app/views/Auth/login.php";
                include LAYOUT;
            }else{
                $this->data['error'] = 'Usuario o contraseña invalidos';
                $view = "/app/views/Auth/login.php";
                include LAYOUT;
            }
        }
    }

    //[POST]
    public function SignUp(array $request): void
    {
        $view = '/app/views/auth/signup.php';

        // Handle form submission
        $name = $request['name'] ?? '';
        $surname = $request['surname'] ?? '';
        $email = $request['email'] ?? '';
        $password = $request['password'] ?? '';
        $confirm_password = $request['confirm_password'] ?? '';
        $phone = $request['phone'] ?? '';
        $terms = isset($request['terms']);

        // Validation
        if (empty($name) || empty($surname) || empty($email) || empty($password) || empty($phone) || empty($confirm_password)) {
            $this->data['error'] = 'Por favor, complete todos los campos obligatorios';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->data['error'] = 'El email no es válido';
        } elseif (strlen($password) < 8) {
            $this->data['error'] = 'La contraseña debe tener al menos 8 caracteres';
        } elseif ($password !== $confirm_password) {
            $this->data['error'] = 'Las contraseñas no coinciden';
        } elseif (!$terms) {
            $this->data['error'] = 'Debe aceptar los términos y condiciones';
        } elseif ($this->_userRepository->GetByEmail($email)!==null) {
            $this->data['error'] = 'El email ya existe';
        }
        else{
            // TODO: Save to database
            $encodedPassword = Hash::Encode($password);
            $newuser = new User(
                GUID::Create(),
                $name,
                $surname,
                $email,
                $phone,
                $encodedPassword,
                'user',
                false
            );
            $newcart = new Cart(
                GUID::Create(),
                $newuser->Id->Id,
                []
            );
            $this->_userRepository->CreateUser($newuser,$newcart);
            $this->data['success'] = 'Registro exitoso. En breves momento sera activada tu cuenta por el administrador. Redirigiendo al login...';

            // Simulate success and redirect
            header('refresh:2;url=/auth/login');
        }

        $this->data['name'] = $name;
        $this->data['surname'] = $surname;
        $this->data['email'] = $email;
        $this->data['phone'] = $phone;

        include LAYOUT;
    }

    //[GET]
    public function SignOut(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /');
        exit();
    }
}