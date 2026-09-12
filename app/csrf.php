<?php
/**
 * Royal Haramain - CSRF Protection
 * Include file ini di setiap halaman yang punya form POST.
 * Atau pastikan config.php sudah punya fungsi csrf_token/csrf_field/csrf_validate.
 */

if (!function_exists('csrf_token')) {
    function csrf_token(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrf_token()) . '">';
    }
}

if (!function_exists('csrf_validate')) {
    function csrf_validate(string $token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
