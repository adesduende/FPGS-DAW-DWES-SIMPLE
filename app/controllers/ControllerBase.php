<?php
namespace sportshop\app\controllers;

use sportshop\app\services\Auth;

/**
 * Controller Base - Base class for all controllers
 */
abstract class ControllerBase
{
    public array $data;
    /**
     * Constructor - Initializes session and common data
     */
    protected function __construct()
    {
        session_start();
        $this->data = array();
        $this->data["user_id"] = $_SESSION['user_id'];
        $this->data['name'] = $_SESSION['username'];
        $this->data['cart_count'] = $_SESSION['cart_count'] ?? 0;
        $this->data['isLogin'] = Auth::isLogin();
        $this->data['isAdmin'] = Auth::isAdmin();
    }
    /**
     * Sends a JSON response
     * @param bool $result - The result of the operation
     * @param string $error - The message of error
     * @return void
     */
    protected function ResponseJson($result, $error=''): void
    {
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => $error]);
        }
        exit();
    }
}