<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    /**
     * Generate a JWT token.
     */
    public static function generateToken($user_email, $user_id,  $user_role)
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'Laravel Token', // Issuer
            // 'aud' => 'https://yourdomain.com', // Audience
            'iat' => time(),                   // Issued at
            'exp' => time() + 60 * 60 * 24,            // Expiration time (1 hour)
            'user_email' => $user_email,               // Subject (user email)
            'user_id' => $user_id,
            'role' => $user_role,
        ];

        return JWT::encode($payload, $key, 'HS256');
    }


    //password reset token
    public static function generateTokenResetPassword($user_email)
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'Laravel Token', // Issuer
            // 'aud' => 'https://yourdomain.com', // Audience
            'iat' => time(),                   // Issued at
            'exp' => time() + 60 * 5,            // Expiration time (1 hour)
            'user_email' => $user_email,               // Subject (user email)

        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    //verify token
    public static function verifyToken($token)
    {
        try {
            if (!$token) {
                return "unauthorized";
            } else {
                $key = env('JWT_KEY');
                return JWT::decode($token, new Key($key, 'HS256'));
            }
        } catch (\Throwable $e) {
            return "unauthorized";
        }
    }

    /**
     * Encode data to Base64 URL format.
     *
     * @param string $data
     * @return string
     */
    private static function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
