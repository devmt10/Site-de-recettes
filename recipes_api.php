<?php
// recipes_api.php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__.'/config/config.php';
require_once __DIR__.'/config/mysql.php';
require_once __DIR__.'/databaseconnect.php';
require_once __DIR__.'/functions.php';  // getAuthorName()

// 1) Load users & seasons
$u = $mysqlClient->query('SELECT user_id, full_name FROM user')->fetchAll(PDO::FETCH_KEY_PAIR);
$s = $mysqlClient->query('SELECT season_id, title FROM SEASON')->fetchAll(PDO::FETCH_KEY_PAIR);

// 2) Load recipes
$rs = $mysqlClient->query('SELECT * FROM recipe WHERE is_enabled = 1')->fetchAll(PDO::FETCH_ASSOC);

// 3) Attach author & season text
foreach ($rs as &$r) {
    $r['author'] = $u[(int)$r['user_id']] ?? '—';
    $r['season'] = $s[(int)$r['season_id']] ?? '—';
}
unset($r);

echo json_encode($rs, JSON_UNESCAPED_UNICODE);
