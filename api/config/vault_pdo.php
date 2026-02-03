<?php
/**
 * getTenantPdo
 * Returns a PDO connection to a tenant schema using Vault-provided credentials.
 * Caches Vault credentials locally for the duration of their lease.
 *
 * @param string $vaultRole   Vault DB role name (e.g. "tenant-mjz")
 * @param string $schemaName  MySQL schema name (e.g. "MJZ_MB")
 * @return PDO
 * @throws RuntimeException on failure
 */
function getTenantPdo(string $vaultRole, string $schemaName): PDO
{
    $cacheDir  = __DIR__ . '/.vault-cache';
    $cacheFile = "$cacheDir/{$vaultRole}.json";
    $bufferSec = 10;

    if (!is_dir($cacheDir)) {
        mkdir($cacheDir, 0700, true);
    }

    // Check cache
    if (file_exists($cacheFile)) {
        $cached = json_decode(file_get_contents($cacheFile), true);
        if (
            isset($cached['username'], $cached['password'], $cached['lease_start'], $cached['lease_duration']) &&
            (time() - $cached['lease_start']) < ($cached['lease_duration'] - $bufferSec)
        ) {
            return new PDO(
                "mysql:host=127.0.0.1;dbname=$schemaName;charset=utf8mb4",
                $cached['username'],
                $cached['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        }
    }

    // Fetch fresh credentials from Vault
    $tokenFile = '/run/mightyreel/vault-token';

    if (!is_readable($tokenFile)) {
        throw new RuntimeException("Vault token file is missing or unreadable");
    }

    $vaultToken = trim(file_get_contents($tokenFile));
    $vaultUrl   = "http://127.0.0.1:8200/v1/database/creds/$vaultRole";

    $ch = curl_init($vaultUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ["X-Vault-Token: $vaultToken"]
    ]);
    $response = curl_exec($ch);
    $status   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status !== 200 || !$response) {
        throw new RuntimeException("Vault returned HTTP $status when requesting DB creds");
    }

    $data = json_decode($response, true);

    if (!isset($data['data']['username'], $data['data']['password'], $data['lease_duration'])) {
        throw new RuntimeException("Missing credentials or lease info in Vault response");
    }

    // Save to cache
    $cachePayload = [
        'username'       => $data['data']['username'],
        'password'       => $data['data']['password'],
        'lease_start'    => time(),
        'lease_duration' => $data['lease_duration']
    ];
    file_put_contents($cacheFile, json_encode($cachePayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

    return new PDO(
        "mysql:host=127.0.0.1;dbname=$schemaName;charset=utf8mb4",
        $cachePayload['username'],
        $cachePayload['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
}
