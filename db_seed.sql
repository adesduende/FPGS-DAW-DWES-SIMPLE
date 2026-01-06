-- Set character encoding
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Create a database if not exists: sportshop
USE SportShop;

-- Insert an admin user
-- Password: admin123 (hashed with SHA256)
INSERT INTO user (id, name, surname, email, phone_number, hashed_password, role, is_active)
VALUES (
    'a1b2c3d4-e5f6-4a5b-8c9d-0e1f2a3b4c5d',
    'Admin',
    'System',
    'admin@sportshop.com',
    '+34600000000',
    '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9',
    'admin',
    TRUE
);

-- Insert cart for admin user
INSERT INTO cart (id, user_id)
VALUES (
    'b2c3d4e5-f6a7-4b5c-9d0e-1f2a3b4c5d6e',
    'a1b2c3d4-e5f6-4a5b-8c9d-0e1f2a3b4c5d'
);

-- Insert default user for testing
-- Password: admin123 (hashed with SHA256)
INSERT INTO user (id, name, surname, email, phone_number, hashed_password, role, is_active)
VALUES (
    'c3d4e5f6-a7b8-4c5d-9e0f-1a2b3c4d5e6f',
    'Juan',
    'Perez',
    'usuario@sportshop.com',
    '+34611111111',
    '240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9',
    'user',
    TRUE
);

-- Insert cart for default user
INSERT INTO cart (id, user_id)
VALUES (
    'd4e5f6a7-b8c9-4d5e-0f1a-2b3c4d5e6f7a',
    'c3d4e5f6-a7b8-4c5d-9e0f-1a2b3c4d5e6f'
);

-- Insert some categories
INSERT INTO category (id, name, description)
VALUES 
    ('e5f6a7b8-c9d0-4e5f-1a2b-3c4d5e6f7a8b', 'Fútbol', 'Equipamiento y ropa para fútbol'),
    ('f6a7b8c9-d0e1-4f5a-2b3c-4d5e6f7a8b9c', 'Baloncesto', 'Equipamiento y ropa para baloncesto'),
    ('a7b8c9d0-e1f2-4a5b-3c4d-5e6f7a8b9c0d', 'Tenis', 'Equipamiento y ropa para tenis'),
    ('b8c9d0e1-f2a3-4b5c-4d5e-6f7a8b9c0d1e', 'Running', 'Equipamiento y ropa para correr'),
    ('c9d0e1f2-a3b4-4c5d-5e6f-7a8b9c0d1e2f', 'Natación', 'Equipamiento y ropa para natación');

-- Insert some products
INSERT INTO product (id, name, category_id, price, image_url, rating, stock, badge, discount, description, is_active)
VALUES 
    -- Fútbol
    ('d0e1f2a3-b4c5-4d5e-6f7a-8b9c0d1e2f3a', 'Balón Nike Strike', 'e5f6a7b8-c9d0-4e5f-1a2b-3c4d5e6f7a8b', 29.99, '/images/image.png', 4.5, 50, 'Popular', 0.00, 'Balón de fútbol profesional Nike Strike con tecnología de control mejorado', TRUE),
    ('e1f2a3b4-c5d6-4e5f-7a8b-9c0d1e2f3a4b', 'Zapatillas Adidas Predator', 'e5f6a7b8-c9d0-4e5f-1a2b-3c4d5e6f7a8b', 129.99, '/images/image.png', 4.8, 30, 'New', 10.00, 'Zapatillas de fútbol Adidas Predator con excelente tracción y control', TRUE),
    ('f2a3b4c5-d6e7-4f5a-8b9c-0d1e2f3a4b5c', 'Camiseta Selección España', 'e5f6a7b8-c9d0-4e5f-1a2b-3c4d5e6f7a8b', 89.99, '/images/image.png', 4.7, 100, 'Best Seller', 15.00, 'Camiseta oficial de la Selección Española de Fútbol', TRUE),
    
    -- Baloncesto
    ('a3b4c5d6-e7f8-4a5b-9c0d-1e2f3a4b5c6d', 'Balón Spalding NBA', 'f6a7b8c9-d0e1-4f5a-2b3c-4d5e6f7a8b9c', 39.99, '/images/image.png', 4.6, 40, 'Popular', 0.00, 'Balón oficial de la NBA Spalding de cuero compuesto', TRUE),
    ('b4c5d6e7-f8a9-4b5c-0d1e-2f3a4b5c6d7e', 'Zapatillas Jordan Air', 'f6a7b8c9-d0e1-4f5a-2b3c-4d5e6f7a8b9c', 179.99, '/images/image.png', 4.9, 20, 'New', 0.00, 'Zapatillas de baloncesto Jordan Air con amortiguación máxima', TRUE),
    
    -- Tenis
    ('c5d6e7f8-a9b0-4c5d-1e2f-3a4b5c6d7e8f', 'Raqueta Wilson Pro Staff', 'a7b8c9d0-e1f2-4a5b-3c4d-5e6f7a8b9c0d', 199.99, '/images/image.png', 4.8, 25, 'Premium', 20.00, 'Raqueta de tenis profesional Wilson Pro Staff usada por profesionales', TRUE),
    ('d6e7f8a9-b0c1-4d5e-2f3a-4b5c6d7e8f9a', 'Pelotas Tenis Head', 'a7b8c9d0-e1f2-4a5b-3c4d-5e6f7a8b9c0d', 12.99, '/images/image.png', 4.4, 200, 'Best Seller', 0.00, 'Pack de 3 pelotas de tenis Head para todas las superficies', TRUE),
    
    -- Running
    ('e7f8a9b0-c1d2-4e5f-3a4b-5c6d7e8f9a0b', 'Zapatillas Nike Air Zoom', 'b8c9d0e1-f2a3-4b5c-4d5e-6f7a8b9c0d1e', 149.99, '/images/image.png', 4.7, 60, 'Popular', 10.00, 'Zapatillas de running Nike Air Zoom con tecnología de amortiguación reactiva', TRUE),
    ('f8a9b0c1-d2e3-4f5a-4b5c-6d7e8f9a0b1c', 'Reloj Garmin Forerunner', 'b8c9d0e1-f2a3-4b5c-4d5e-6f7a8b9c0d1e', 299.99, '/images/image.png', 4.9, 35, 'New', 0.00, 'Reloj GPS para running Garmin Forerunner con monitor cardíaco', TRUE),
    
    -- Natación
    ('a9b0c1d2-e3f4-4a5b-5c6d-7e8f9a0b1c2d', 'Gafas Speedo Vanquisher', 'c9d0e1f2-a3b4-4c5d-5e6f-7a8b9c0d1e2f', 24.99, '/images/image.png', 4.5, 80, 'Premium', 0.00, 'Gafas de natación Speedo Vanquisher anti-vaho con protección UV', TRUE),
    ('b0c1d2e3-f4a5-4b5c-6d7e-8f9a0b1c2d3e', 'Gorro Arena Classic', 'c9d0e1f2-a3b4-4c5d-5e6f-7a8b9c0d1e2f', 9.99, '/images/image.png', 4.3, 150, '', 0.00, 'Gorro de silicona Arena Classic duradero y cómodo', TRUE),
    ('c1d2e3f4-a5b6-4c5d-7e8f-9a0b1c2d3e4f', 'Traje Baño Speedo', 'c9d0e1f2-a3b4-4c5d-5e6f-7a8b9c0d1e2f', 69.99, '/images/image.png', 4.6, 45, 'Popular', 15.00, 'Traje de baño competición Speedo con tecnología hidrodinámica', TRUE);

