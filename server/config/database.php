<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn = null;

    public function __construct() {
        // Menggunakan region Sydney (ap-southeast-2) sesuai project Supabase-mu
        $this->host = getenv('DB_HOST') ?: 'aws-0-ap-southeast-2.pooler.supabase.co';
        $this->db_name = getenv('DB_NAME') ?: 'postgres';
        $this->username = getenv('DB_USER') ?: 'postgres.yqkwnudtgjtxwhjaxmbl';
        $this->password = getenv('DB_PASSWORD') ?: 'hanzganz01*';
        $this->port = getenv('DB_PORT') ?: '5432';
    }

    public function getConnection() {
        if ($this->conn !== null) return $this->conn;
        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            return $this->conn;
        } catch (PDOException $e) {
            http_response_code(500);
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }
}

$database = new Database();
$pdo = $database->getConnection();

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /server/api/auth/login.php");
        exit;
    }
}

function require_admin() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: /server/api/auth/login.php");
        exit;
    }
}
?>
