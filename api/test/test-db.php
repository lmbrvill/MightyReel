<?php
require_once __DIR__ . '/../config/vault_pdo.php';

try {
    $pdo = getTenantPdo('tenant-mjz', 'MJZ_MB');
    $result = $pdo->query("SELECT NOW() AS server_time")->fetch();
    echo "Connected! Server time: " . $result['server_time'];
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
