<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Database {
    private $host = "sql107.infinityfree.com";
    private $db_name = "if0_42922110_trading";
    private $username = "if0_42922110";
    private $password = "hanzganz012";
    public $conn = null;

    public function getConnection() {
        if ($this->conn !== null) return $this->conn;
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            return $this->conn;
        } catch (PDOException $e) {
            die("Database Error: Periksa koneksi.");
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