<?php

namespace sportshop\app\controllers;

use sportshop\app\data\interfaces\IUserRepository;
use sportshop\app\data\interfaces\ICartRepository;
use sportshop\app\models\Cart;
use sportshop\app\models\User;
use sportshop\app\services\Auth;
use sportshop\app\services\Hash;
use sportshop\app\utils\GUID;

/**
 * Auth Controller - This controller manage all the requests related to authentication
 */
class AuthController extends ControllerBase
{
    protected readonly IUserRepository $_userRepository;
    protected readonly ICartRepository $_cartRepository;

    /**
     * Constructor
     * @param IUserRepository $userRepository - The user repository
     * @param ICartRepository $cartRepository - The cart repository
     */
    public function __construct(IUserRepository $userRepository, ICartRepository $cartRepository)
    {
        parent::__construct();
        $this->_userRepository = $userRepository;
        $this->_cartRepository = $cartRepository;
    }

    //[GET]
    /**
     * This method manage the request to show the sign in view
     * @return void
     */
    public function SignInView(): void
    {
        session_start();

        $view = '/app/views/Auth/Login.php';
        // Check if already logged in
        if (Auth::isLogin()) {
            header('Location: /');
            exit();
        }

        include LAYOUT;
    }

    //[GET]
    /**
     * This method manage the request to show the sign up view
     * @return void
     */
    public function SignUpView(): void
    {
        if (Auth::isLogin()) {
            header('Location: /');
            exit();
        }
        $view = '/app/views/Auth/Signup.php';
        include LAYOUT;
    }

    //[POST]
    /**
     * This method manage the request to sign in a user
     * @param array $request - The request data ( username, password )
     * @return void
     */
    public function SignIn(array $request): void
    {
        $error = '';

        $username = $request['username'] ?? '';
        $password = $request['password'] ?? '';

        if (empty($username) || empty($password)) {
            $error = 'Please fill in all fields';
        } else {
            $user = $this->_userRepository->GetByEmail($username);
            
            if (!$user) {
                $this->data['error'] = 'Usuario o contraseña invalidos';
                $view = '/app/views/Auth/Login.php';
                include LAYOUT;
                return;
            }
            
            if (Hash::IsEqual($password, $user->HashedPassword) && $user->IsActive) {
                session_start();
                $_SESSION['user_id'] = $user->Id->Id;
                $_SESSION['username'] = $user->Name;
                $_SESSION['user_rol'] = $user->Role;
                header('Location: /');
                exit();
            } elseif(!$user->IsActive) {
                $this->data['error'] = 'El usuario no ha sido activado';
                $view = '/app/views/Auth/Login.php';
                include LAYOUT;
            }else{
                $this->data['error'] = 'Usuario o contraseña invalidos';
                $view = '/app/views/Auth/Login.php';
                include LAYOUT;
            }
        }
    }

    //[POST]
    /**
     * This method manage the request to sign up a new user
     * @param array $request - The request data ( name, surname, email, password, confirm_password, phone, terms )
     * @return void
     */
    public function SignUp(array $request): void
    {
        $view = '/app/views/Auth/Signup.php';

        // Handle form submission
        $name = $request['name'] ?? '';
        $surname = $request['surname'] ?? '';
        $email = $request['email'] ?? '';
        $password = $request['password'] ?? '';
        $confirm_password = $request['confirm_password'] ?? '';
        $phone = $request['phone'] ?? '';

        // Validation
        if (empty($name) || empty($surname) || empty($email) || empty($password) || empty($phone) || empty($confirm_password)) {
            $this->data['error'] = 'Por favor, complete todos los campos obligatorios';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->data['error'] = 'El email no es válido';
        } elseif (strlen($password) < 8) {
            $this->data['error'] = 'La contraseña debe tener al menos 8 caracteres';
        } elseif ($password !== $confirm_password) {
            $this->data['error'] = 'Las contraseñas no coinciden';        
        } elseif ($this->_userRepository->GetByEmail($email)!==null) {
            $this->data['error'] = 'El email ya existe';
        }
        else{
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
    /**
     * This method manage the request to sign out a user
     * @return void
     */
    public function SignOut(): void
    {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /');
        exit();
    }
}