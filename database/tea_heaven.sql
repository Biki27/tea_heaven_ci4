-- ============================================================
--  Tea Haven — Full Database Schema + Seed Data
--  Compatible with MySQL 8.0+
-- ============================================================

CREATE DATABASE IF NOT EXISTS tea_heaven CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tea_heaven;

-- ── USERS ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name          VARCHAR(120)  NOT NULL,
    email         VARCHAR(160)  UNIQUE NOT NULL,
    password      VARCHAR(255)  DEFAULT NULL,        -- NULL for OAuth-only users
    google_id     VARCHAR(120)  DEFAULT NULL,
    facebook_id   VARCHAR(120)  DEFAULT NULL,
    avatar        VARCHAR(400)  DEFAULT NULL,
    phone         VARCHAR(20)   DEFAULT NULL,
    address       TEXT          DEFAULT NULL,
    city          VARCHAR(100)  DEFAULT NULL,
    pincode       VARCHAR(12)   DEFAULT NULL,
    country       VARCHAR(80)   DEFAULT 'India',
    is_active     TINYINT(1)    NOT NULL DEFAULT 1,
    created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_google   (google_id),
    INDEX idx_facebook (facebook_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── CATEGORIES ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS categories (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    slug       VARCHAR(100) UNIQUE NOT NULL,
    image      VARCHAR(400) DEFAULT NULL,
    is_active  TINYINT(1)   NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── PRODUCTS ─────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS products (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id   INT UNSIGNED DEFAULT NULL,
    name          VARCHAR(200)   NOT NULL,
    slug          VARCHAR(200)   UNIQUE NOT NULL,
    description   TEXT           DEFAULT NULL,
    price         DECIMAL(10,2)  NOT NULL,
    sale_price    DECIMAL(10,2)  DEFAULT NULL,
    stock         INT            NOT NULL DEFAULT 100,
    image         VARCHAR(400)   DEFAULT NULL,
    badge         VARCHAR(40)    DEFAULT NULL,   -- e.g. "Sale", "New", "Popular"
    is_active     TINYINT(1)     NOT NULL DEFAULT 1,
    created_at    DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    INDEX idx_category (category_id),
    INDEX idx_active   (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── CART ─────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS cart (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id     INT UNSIGNED DEFAULT NULL,   -- NULL = guest (session-based)
    session_id  VARCHAR(128)  DEFAULT NULL,
    product_id  INT UNSIGNED  NOT NULL,
    quantity    INT           NOT NULL DEFAULT 1,
    created_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_user    (user_id),
    INDEX idx_session (session_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── ORDERS ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS orders (
    id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id             INT UNSIGNED  DEFAULT NULL,
    order_number        VARCHAR(30)   UNIQUE NOT NULL,
    status              ENUM('pending','confirmed','processing','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
    payment_status      ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
    payment_method      VARCHAR(50)   DEFAULT NULL,     -- 'razorpay', 'cod', 'upi'
    razorpay_order_id   VARCHAR(100)  DEFAULT NULL,
    razorpay_payment_id VARCHAR(100)  DEFAULT NULL,
    razorpay_signature  VARCHAR(256)  DEFAULT NULL,
    subtotal            DECIMAL(10,2) NOT NULL,
    tax                 DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    shipping            DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    discount            DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total               DECIMAL(10,2) NOT NULL,
    -- Shipping address snapshot
    first_name          VARCHAR(80)   NOT NULL,
    last_name           VARCHAR(80)   NOT NULL,
    email               VARCHAR(160)  NOT NULL,
    phone               VARCHAR(20)   DEFAULT NULL,
    address             TEXT          NOT NULL,
    city                VARCHAR(100)  NOT NULL,
    pincode             VARCHAR(12)   NOT NULL,
    country             VARCHAR(80)   NOT NULL DEFAULT 'India',
    notes               TEXT          DEFAULT NULL,
    created_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user   (user_id),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── ORDER ITEMS ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS order_items (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id      INT UNSIGNED  NOT NULL,
    product_id    INT UNSIGNED  NOT NULL,
    product_name  VARCHAR(200)  NOT NULL,
    product_image VARCHAR(400)  DEFAULT NULL,
    price         DECIMAL(10,2) NOT NULL,
    quantity      INT           NOT NULL,
    subtotal      DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id)   REFERENCES orders(id)   ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT,
    INDEX idx_order (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── PROMO CODES ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS promo_codes (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code            VARCHAR(30)   UNIQUE NOT NULL,
    discount_type   ENUM('flat','percent') NOT NULL DEFAULT 'percent',
    discount_value  DECIMAL(8,2)  NOT NULL,
    min_order       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    max_uses        INT           DEFAULT NULL,
    used_count      INT           NOT NULL DEFAULT 0,
    expires_at      DATETIME      DEFAULT NULL,
    is_active       TINYINT(1)    NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ════════════════════════════════════════════════════════════
--  SEED DATA
-- ════════════════════════════════════════════════════════════

-- Categories
INSERT INTO categories (name, slug, image) VALUES
('Black Tea',    'black-tea',    'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400'),
('Green Tea',    'green-tea',    'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400'),
('Herbal Tea',   'herbal-tea',   'https://images.unsplash.com/photo-1515694346937-94d85e41e93a?w=400'),
('Oolong Tea',   'oolong-tea',   'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=400'),
('Masala Chai',  'masala-chai',  'https://images.unsplash.com/photo-1561336313-0bd5e0b27ec8?w=400');

-- Products
INSERT INTO products (category_id, name, slug, description, price, sale_price, stock, image, badge) VALUES
(1,'Aam Panna Iced Tea',    'aam-panna-iced-tea',    'Refreshing mango-infused black iced tea.',   120.00, 99.00,  50, 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', 'Sale'),
(2,'Chamomile Green Tea',   'chamomile-green-tea',   'Soothing chamomile blended with green tea.', 54.00,  NULL,   80, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', 'Popular'),
(4,'Moroccan Mint Tea',     'moroccan-mint-tea',     'Bold mint aroma, Moroccan style.',            400.00, 349.00, 30, 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=400', 'Sale'),
(5,'Masala Chai',           'masala-chai',           'Classic Indian spiced milk tea blend.',       38.00,  NULL,   100,'https://images.unsplash.com/photo-1561336313-0bd5e0b27ec8?w=400', NULL),
(1,'Earl Grey',             'earl-grey',             'Premium bergamot-infused black tea.',         450.00, NULL,   40, 'https://images.unsplash.com/photo-1510591509098-f4fdc6d0ff04?w=400', NULL),
(1,'Darjeeling First Flush','darjeeling-first-flush','Muscatel notes, early spring harvest.',       270.00, 229.00, 60, 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', 'Sale'),
(2,'Japanese Sencha',       'japanese-sencha',       'Umami-rich steamed Japanese green tea.',     200.00, NULL,   70, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', 'New'),
(4,'Ti Guan Yin Oolong',    'ti-guan-yin-oolong',    'Chinese partially oxidised oolong classic.',  490.00, NULL,   25, 'https://images.unsplash.com/photo-1471943311424-646960669fbc?w=400', NULL),
(3,'Organic Rooibos',       'organic-rooibos',       'South African caffeine-free herbal tea.',     420.00, 380.00, 55, 'https://images.unsplash.com/photo-1515694346937-94d85e41e93a?w=400', 'Sale'),
(3,'Peppermint Bliss',      'peppermint-bliss',      'Pure peppermint leaves, caffeine-free.',      180.00, NULL,   90, 'https://images.unsplash.com/photo-1515694346937-94d85e41e93a?w=400', NULL),
(2,'Matcha Ceremonial',     'matcha-ceremonial',     'Grade-A ceremonial matcha from Uji, Japan.',  650.00, 599.00, 20, 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400', 'Popular'),
(1,'Assam Strong CTC',      'assam-strong-ctc',      'Bold, brisk Assam CTC best with milk.',       85.00,  NULL,   120,'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400', NULL);

-- Sample promo codes
INSERT INTO promo_codes (code, discount_type, discount_value, min_order, max_uses) VALUES
('WELCOME10', 'percent', 10.00, 200.00, 500),
('FLAT50',    'flat',    50.00, 300.00, 200),
('TEA20',     'percent', 20.00, 500.00, 100);
