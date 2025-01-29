-- Buat tabel barang
CREATE TABLE barang (
    id SERIAL PRIMARY KEY, -- ID produk
    nama_barang VARCHAR(100) NOT NULL, -- Nama produk
    kategori VARCHAR(50) NOT NULL, -- Kategori produk
    harga NUMERIC(10, 2) NOT NULL, -- Harga produk
    stok INT NOT NULL, -- Stok produk
    gambar TEXT, -- URL gambar produk
    tanggal_ditambahkan TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Tanggal data ditambahkan
);

-- Contoh data produk
INSERT INTO barang (nama_barang, kategori, harga, stok, gambar) VALUES
('Laptop Lenovo', 'Elektronik', 8500000, 10, 'https://example.com/images/laptop-lenovo.jpg'),
('Smartphone Samsung', 'Elektronik', 5500000, 15, 'https://example.com/images/smartphone-samsung.jpg'),
('TV LG 42"', 'Elektronik', 4500000, 8, 'https://example.com/images/tv-lg.jpg'),
('Kipas Angin', 'Elektronik', 300000, 20, 'https://example.com/images/kipas-angin.jpg');
