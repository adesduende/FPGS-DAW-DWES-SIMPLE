<?php

namespace sportshop\app\services;

class Logger {
    private Logger $_instance;

    private function __construct() {
        
    }

    public static function GetInstance(): Logger {
        static $instance = null;
        if ($instance === null) {
            $instance = new Logger();
        }
        return $instance;
    }

    public function Debug(string $message): void {
        if (getenv('APP_ENV') === 'development') {
            error_log("[DEBUG] " . $message );
        }
    }
    public function Error(string $message): void {
        error_log("[ERROR] " . $message);
    }
    public function Info(string $message): void {
        error_log("[INFO] " . $message);
    }
    public function Warning(string $message): void {
        error_log("[WARNING] " . $message);
    }


}