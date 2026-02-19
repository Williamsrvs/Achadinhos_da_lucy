CREATE Table u799109175_shopee.tbl_produtos (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    image VARCHAR(255) NOT NULL,
    platform VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    originalPrice DECIMAL(10, 2) NOT NULL
    discount DECIMAL(10, 2) NOT NULL,
    rating DECIMAL(10, 2) NOT NULL,
    reviewCount DECIMAL(10, 2) NOT NULL,
    installments VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    dt_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    
)