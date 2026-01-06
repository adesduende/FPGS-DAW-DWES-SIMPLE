<?php
    namespace sportshop\app\services;

    class Auth {        
        static function isAdmin():bool
        {
            session_start();

            if(isset($_SESSION['user_id'])&&$_SESSION['user_rol']==='admin')
                return true;

            return false;
        }

        static function isLogin(): bool
        {
            session_start();

            if (isset($_SESSION['user_id']))
                return true;

            return false;
        }
    }
        
?>