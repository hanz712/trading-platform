<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Database {
    // Kita gunakan format connection string lengkap (URI) untuk menghindari error parser host terpisah
    private $conn = null;

    public function getConnection() {
        if ($this->conn !== null) return $this->conn;
        try {
            // Format URI lengkap Pooler Supabase
            $dsn = "pgsql:host=aws-0-ap-southeast-2.pooler.supabase.co;port=5432;dbname=postgres";
            $username = "postgres.yqkwnudtgjtxwhjaxmbl";
            $password = "hanzganz01*";

            $this->conn = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_TIMEOUT => 15,
            ]);
            return $this->conn;
        } catch (PDOException $e) {
            http_response_code(500);
            // Menampilkan info detail untuk debugging
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
hp");
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
