<?php
namespace sportshop\app\services;

/**
 * A simple hashing service for encoding and verifying hashed data.
 */
readonly class Hash
{
    /**
     * Encodes the given data using the specified algorithm.
     * @param string $data - Data to be hashed
     * @param Algorithm $algorithm - Hashing algorithm to use
     * @return string
     */
    static function Encode(string $data, Algorithm $algorithm = Algorithm::sha256) : string
    {
        return match ($algorithm) {
            Algorithm::sha256 => hash('sha256', $data),
            Algorithm::md5 => hash('md5', $data),
            default => $data,
        };
    }

    /**
     * Decodes the given hashed data.
     * Note: Hashing is a one-way function; this method is provided for interface completeness.
     * @param string $data - Hashed data
     * @return string
     */
    static function Decode(string $data) : string
    {
        $decoded = "";
        return $data;
    }
    /**
     * Compares a plain text with a hashed text to check if they are equivalent.
     * @param string $text - Plain text
     * @param string $hashedtext - Hashed text
     * @param Algorithm $algorithm - Hashing algorithm used
     * @return bool
     */
    static function IsEqual(string $text, string $hashedtext, Algorithm $algorithm = Algorithm::sha256): bool
    {
        return Hash::Encode($text)===$hashedtext;
    }
}

/**
 * Supported hashing algorithms.
 */
enum Algorithm {
    case sha256;
    case md5;
}
