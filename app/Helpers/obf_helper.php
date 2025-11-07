<?php

if (! function_exists('id_to_url')) {
    function id_to_url($id): string
    {
        // URL-safe base64 (no + / =)
        return rtrim(strtr(base64_encode((string)$id), '+/', '-_'), '=');
    }
}

if (! function_exists('url_to_id')) {
    function url_to_id(string $token): int
    {
        $pad = strlen($token) % 4 ? 4 - (strlen($token) % 4) : 0;
        $decoded = base64_decode(strtr($token . str_repeat('=', $pad), '-_', '+/'), true);
        return (int) ($decoded !== false ? $decoded : 0);
    }
}
