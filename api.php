<?php
// ============================================================
// CONFIGURAÇÕES DO BANCO DE DADOS
// IMPORTANTE: Na Hostinger, o PHP se conecta via 'localhost'
// O host externo (auth-db1937.hstgr.io) é só para phpMyAdmin
// ============================================================
define('DB_HOST',     'localhost');
define('DB_PORT',     3306);
define('DB_USER',     'u799109175_shopee');
define('DB_PASS',     'Q1k2v1y5@2025');
define('DB_NAME',     'u799109175_shopee');
define('DB_TABLE',    'tbl_produtos');

// ============================================================
// HEADERS CORS & JSON
// ============================================================
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// ============================================================
// CONEXÃO PDO
// ============================================================
function getDB(): PDO {
    $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}

// ============================================================
// ROTEAMENTO
// ============================================================
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

// ── PING — diagnóstico de conexão ────────────────────────────
// Acesse: seusite.com/api.php?action=ping
if ($action === 'ping') {
    try {
        $db = getDB();
        $db->query("SELECT 1");
        echo json_encode([
            'success' => true,
            'message' => '✅ Conexão com MySQL OK!',
            'host'    => DB_HOST,
            'db'      => DB_NAME,
            'table'   => DB_TABLE,
            'php'     => PHP_VERSION
        ]);
    } catch (Throwable $e) {
        echo json_encode([
            'success' => false,
            'message' => '❌ Falha na conexão',
            'error'   => $e->getMessage(),
            'host'    => DB_HOST,
            'db'      => DB_NAME
        ]);
    }
    exit;
}

try {
    $db = getDB();

    // ── GET /api.php?action=list ──────────────────────────────
    if ($method === 'GET' && $action === 'list') {
        $stmt = $db->query(
            "SELECT id, title, category, image, platform, price, originalPrice,
                    discount, rating, reviewCount AS reviews, installments, url,
                    dt_registro AS createdAt
             FROM " . DB_TABLE . "
             ORDER BY dt_registro DESC"
        );
        $rows = $stmt->fetchAll();

        // Cast numéricos
        foreach ($rows as &$row) {
            $row['price']         = (float) $row['price'];
            $row['originalPrice'] = $row['originalPrice'] ? (float) $row['originalPrice'] : null;
            $row['discount']      = $row['discount']      ? (float) $row['discount']      : null;
            $row['rating']        = $row['rating']        ? (float) $row['rating']        : null;
            $row['reviews']       = (int)   $row['reviews'];
        }

        echo json_encode(['success' => true, 'products' => $rows]);
        exit;
    }

    // ── POST /api.php?action=save ─────────────────────────────
    if ($method === 'POST' && $action === 'save') {
        $body = json_decode(file_get_contents('php://input'), true);

        if (!$body) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Body JSON inválido']);
            exit;
        }

        // Campos obrigatórios
        $required = ['title', 'price', 'url', 'image', 'platform'];
        foreach ($required as $field) {
            if (empty($body[$field])) {
                http_response_code(422);
                echo json_encode(['success' => false, 'error' => "Campo obrigatório ausente: $field"]);
                exit;
            }
        }

        $stmt = $db->prepare(
            "INSERT INTO " . DB_TABLE . "
             (title, category, image, platform, price, originalPrice, discount, rating, reviewCount, installments, url)
             VALUES
             (:title, :category, :image, :platform, :price, :originalPrice, :discount, :rating, :reviewCount, :installments, :url)"
        );

        $stmt->execute([
            ':title'         => trim($body['title']),
            ':category'      => trim($body['category']      ?? 'Oferta'),
            ':image'         => trim($body['image']),
            ':platform'      => trim($body['platform']),
            ':price'         => (float) $body['price'],
            ':originalPrice' => isset($body['originalPrice']) && $body['originalPrice'] !== '' ? (float) $body['originalPrice'] : null,
            ':discount'      => isset($body['discount'])      && $body['discount']      !== '' ? (float) $body['discount']      : null,
            ':rating'        => isset($body['rating'])        && $body['rating']        !== '' ? (float) $body['rating']        : null,
            ':reviewCount'   => (int) ($body['reviews'] ?? 0),
            ':installments'  => trim($body['installments'] ?? ''),
            ':url'           => trim($body['url']),
        ]);

        $newId = $db->lastInsertId();

        echo json_encode(['success' => true, 'id' => $newId, 'message' => 'Produto cadastrado com sucesso!']);
        exit;
    }

    // ── DELETE /api.php?action=delete&id=X ───────────────────
    if ($method === 'DELETE' && $action === 'delete') {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'ID inválido']);
            exit;
        }

        $stmt = $db->prepare("DELETE FROM " . DB_TABLE . " WHERE id = :id");
        $stmt->execute([':id' => $id]);

        if ($stmt->rowCount() === 0) {
            http_response_code(404);
            echo json_encode(['success' => false, 'error' => 'Produto não encontrado']);
            exit;
        }

        echo json_encode(['success' => true, 'message' => 'Produto excluído com sucesso!']);
        exit;
    }

    // ── Rota não encontrada ───────────────────────────────────
    http_response_code(404);
    echo json_encode(['success' => false, 'error' => 'Ação não encontrada. Use: list | save | delete']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro de banco de dados: ' . $e->getMessage()]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erro interno: ' . $e->getMessage()]);
}