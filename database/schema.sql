-- =========================================================
-- TradingLearn - Database Schema
-- Untuk InfinityFree / MySQL
-- Database: if0_42922110_trading
-- =========================================================

-- =========================================================
-- TABLE: users
-- =========================================================

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- TABLE: levels
-- =========================================================

CREATE TABLE IF NOT EXISTS levels (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level_order INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- TABLE: lessons
-- =========================================================

CREATE TABLE IF NOT EXISTS lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    level_id INT NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    video_url VARCHAR(500),
    content LONGTEXT,
    lesson_order INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_lessons_level
        FOREIGN KEY (level_id)
        REFERENCES levels(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- TABLE: lesson_progress
-- =========================================================

CREATE TABLE IF NOT EXISTS lesson_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    lesson_id INT NOT NULL,
    completed TINYINT(1) NOT NULL DEFAULT 0,
    completed_at DATETIME NULL,

    UNIQUE KEY uq_progress (user_id, lesson_id),

    CONSTRAINT fk_progress_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_progress_lesson
        FOREIGN KEY (lesson_id)
        REFERENCES lessons(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- TABLE: trading_journal
-- =========================================================

CREATE TABLE IF NOT EXISTS trading_journal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    trade_date DATE NOT NULL,
    asset VARCHAR(50) NOT NULL,
    timeframe VARCHAR(20),
    setup VARCHAR(100),
    market_condition VARCHAR(100),

    entry_price DECIMAL(18,6) NULL,
    stop_loss DECIMAL(18,6) NULL,
    take_profit DECIMAL(18,6) NULL,

    result ENUM('Win', 'Loss', 'Break Even') NOT NULL,
    risk_reward DECIMAL(8,2) DEFAULT 0,

    reason TEXT,
    lesson_learned TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX idx_journal_user_date (user_id, trade_date),

    CONSTRAINT fk_journal_user
        FOREIGN KEY (user_id)
        REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- TABLE: quiz_questions
-- =========================================================

CREATE TABLE IF NOT EXISTS quiz_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,

    question TEXT NOT NULL,

    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,

    correct_option CHAR(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =========================================================
-- DEFAULT LEVELS
-- =========================================================

INSERT INTO levels (
    level_order,
    title,
    description
)
SELECT
    1,
    'Fundamental Trading',
    'Kenali market, candle, order, risk, dan konsep dasar sebelum belajar setup.'
WHERE NOT EXISTS (
    SELECT 1
    FROM levels
    WHERE level_order = 1
);


INSERT INTO levels (
    level_order,
    title,
    description
)
SELECT
    2,
    'Market Structure',
    'Pelajari swing, trend, liquidity, BOS/CHoCH dan konteks harga.'
WHERE NOT EXISTS (
    SELECT 1
    FROM levels
    WHERE level_order = 2
);


INSERT INTO levels (
    level_order,
    title,
    description
)
SELECT
    3,
    'Strategy & Execution',
    'Gabungkan bias, setup, entry, invalidation, dan risk management.'
WHERE NOT EXISTS (
    SELECT 1
    FROM levels
    WHERE level_order = 3
);


-- =========================================================
-- DEFAULT LESSONS
-- =========================================================

INSERT INTO lessons (
    level_id,
    title,
    description,
    content,
    lesson_order
)
SELECT
    id,
    'Apa itu Trading?',
    'Dasar trading dan tujuan belajar yang realistis.',
    'Trading adalah proses mengambil keputusan berdasarkan rencana dan risiko.

Fokus awal adalah memahami market dan membangun proses yang konsisten, bukan mencari keuntungan cepat.

Pelajari istilah dasar:
- Asset
- Pair
- Timeframe
- Entry
- Stop Loss
- Take Profit
- Risk
- Reward',
    1
FROM levels
WHERE level_order = 1
AND NOT EXISTS (
    SELECT 1
    FROM lessons
    WHERE title = 'Apa itu Trading?'
);


INSERT INTO lessons (
    level_id,
    title,
    description,
    content,
    lesson_order
)
SELECT
    id,
    'Risk Management Dasar',
    'Kenapa risiko harus ditentukan sebelum entry.',
    'Sebelum memikirkan entry, tentukan invalidation dan risiko.

R:R adalah perbandingan potensi reward terhadap risiko.

Jurnal membantu mengevaluasi apakah keputusan mengikuti rencana.',
    2
FROM levels
WHERE level_order = 1
AND NOT EXISTS (
    SELECT 1
    FROM lessons
    WHERE title = 'Risk Management Dasar'
);


INSERT INTO lessons (
    level_id,
    title,
    description,
    content,
    lesson_order
)
SELECT
    id,
    'Market Structure Dasar',
    'Mengenal swing high, swing low, trend dan perubahan struktur.',
    'Market structure membantu membaca urutan pergerakan harga.

Gunakan swing high dan swing low sebagai konteks.

Setelah fondasi kuat, pelajari BOS dan CHoCH.',
    1
FROM levels
WHERE level_order = 2
AND NOT EXISTS (
    SELECT 1
    FROM lessons
    WHERE title = 'Market Structure Dasar'
);


-- =========================================================
-- DEFAULT QUIZ
-- =========================================================

INSERT INTO quiz_questions (
    question,
    option_a,
    option_b,
    option_c,
    option_d,
    correct_option
)
SELECT
    'Apa fungsi utama stop loss?',
    'Menambah profit',
    'Membatasi risiko ketika analisis invalid',
    'Menjamin trade win',
    'Memprediksi harga',
    'B'
WHERE NOT EXISTS (
    SELECT 1
    FROM quiz_questions
);


INSERT INTO quiz_questions (
    question,
    option_a,
    option_b,
    option_c,
    option_d,
    correct_option
)
SELECT
    'R:R 1:2 berarti...',
    'Risiko dua kali reward',
    'Reward dua kali risiko',
    'Pasti win',
    'Tidak ada risiko',
    'B'
WHERE NOT EXISTS (
    SELECT 1
    FROM quiz_questions
    WHERE question = 'R:R 1:2 berarti...'
);


-- =========================================================
-- SELESAI
-- =========================================================