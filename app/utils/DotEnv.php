<?php

namespace sportshop\app\utils;

use sportshop\app\services\Logger;

/**
 * Class DotEnv
 * Load environment variables from a .env file.
 */
class DotEnv
{
    protected string $path;
    private Logger $_logger;

    /**
     * DotEnv constructor.
     * @param string $path The directory path where the .env file is located.
     */
    public function __construct(string $path)
    {
        $this->_logger = Logger::GetInstance();
        
        if (!file_exists($path)) {
            throw new \InvalidArgumentException("The .env file does not exist: {$path}");
        }
        $this->path = $path . DIRECTORY_SEPARATOR . ".env";
    }
    /**
     * Load the environment variables from the .env file.
     */
    public function load(): void
    {
        try
        {
            $lines = file($this->path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    
            foreach ($lines as $line) {
                // saltamos los comentarios
                if (str_starts_with(trim($line), '#')) {
                    continue;
                }

                if (strpos($line, '=') !== false) {
                    list($name, $value) = explode('=', $line, 2);
                    $name = trim($name);
                    $value = trim($value);

                    $value = $this->removeQuotes($value);


                    if (!array_key_exists($name, $_ENV)) {
                        putenv("{$name}={$value}");
                        $_ENV[$name] = $value;
                        $_SERVER[$name] = $value;
                    }
                }
            }
        } catch (\Exception $e) {
            $this->_logger->Error("Not .env file in such directory to load");
        }
        
    }
    /**
     * Remove quotes from a string value.
     */
    protected function removeQuotes(string $value): string
    {
        if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
            (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
            return substr($value, 1, -1);
        }
        return $value;
    }
}