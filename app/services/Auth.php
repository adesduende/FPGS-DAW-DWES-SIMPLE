<?php
    namespace sportshop\app\services;

    /**
     * This service handles authentication and authorization checks.
     */
    class Auth {

        /**
         * Checks if the current user is an admin.
         *
         * @return bool True if the user is an admin, false otherwise.
         */
        static function isAdmin():bool
        {
            session_start();

            if(isset($_SESSION['user_id'])&&$_SESSION['user_rol']==='admin')
                return true;

            return false;
        }
        /**
         * Checks if a user is logged in.
         *
         * @return bool True if a user is logged in, false otherwise.
         */
        static function isLogin(): bool
        {
            session_start();

            if (isset($_SESSION['user_id']))
                return true;

            return false;
        }
    }
        
?>