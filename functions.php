<?php
if (!function_exists('getRecipes')) {
    function getRecipes(array $rs): array {
        return array_values(array_filter($rs, fn($r) =>
            (int)($r['is_enabled'] ?? 0) === 1
        ));
    }
}
if (!function_exists('getAuthorName')) {
    function getAuthorName(int $uid, array $users): string {
        foreach ($users as $u) {
            if ((int)$u['user_id'] === $uid) return $u['full_name'];
        }
        return 'Auteur inconnu';
    }
}
if (!function_exists('redirectToUrl')) {
    function redirectToUrl(string $url): never {
        if (!headers_sent()) {
            header("Location: {$url}");
            exit;
        }
        echo "<script>window.location.replace('{$url}');</script>";
        exit;
    }
}
