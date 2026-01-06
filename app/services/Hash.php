<?php
namespace sportshop\app\services;
//TODO: We need this service to encode or decode text (Ex: passwords)

readonly class Hash
{
    static function Encode(string $data, Algorithm $algorithm = Algorithm::sha256) : string
    {
        return match ($algorithm) {
            Algorithm::sha256 => hash('sha256', $data),
            Algorithm::md5 => hash('md5', $data),
            default => $data,
        };
    }

    static function Decode(string $data) : string
    {
        $decoded = "";
        return $data;
    }

    static function IsEqual(string $text, string $hashedtext, Algorithm $algorithm = Algorithm::sha256): bool
    {
        return Hash::Encode($text)===$hashedtext;
    }
}

enum Algorithm {
    case sha256;
    case md5;
}
