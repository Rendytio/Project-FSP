CREATE DATABASE IF NOT EXISTS fullstack;
USE fullstack;

DROP TABLE IF EXISTS jawaban;
DROP TABLE IF EXISTS soal;

CREATE TABLE soal (
    idsoal INT AUTO_INCREMENT PRIMARY KEY,
    nomor INT NOT NULL,
    pertanyaan TEXT NOT NULL,
    halaman_ke INT NOT NULL
);

CREATE TABLE jawaban (
    idjawaban INT AUTO_INCREMENT PRIMARY KEY,
    idsoal INT NOT NULL,
    isi_jawaban TEXT NOT NULL,
    benarkah TINYINT(1) NOT NULL,
    FOREIGN KEY (idsoal) REFERENCES soal(idsoal) ON DELETE CASCADE ON UPDATE CASCADE
);

INSERT INTO soal (idsoal, nomor, pertanyaan, halaman_ke) VALUES
(1, 1, 'Siapa nama superhero marvel laba laba', 1),
(2, 2, 'Siapa pria kaya dengan armor besi?', 1),
(3, 3, 'Berikut ini adalah avengers di mcu, kecuali', 2),
(4, 4, 'Siapa nama asli spiderman?', 2),
(5, 5, 'Apa Kata Terakhir Iron man?', 2),
(6, 6, 'Darimana Captain America Berasal dari?', 3),
(7, 7, 'Warna The Incredible Hulk adalah?', 3),
(8, 8, 'Antman adalah superhero bertema?', 3),
(9, 9, 'Nama Mesin Tony Stark adalah?', 4),
(10, 10, 'Musuh Spiderman adalah, kecuali', 4);


INSERT INTO jawaban (idjawaban, idsoal, isi_jawaban, benarkah) VALUES

(1, 1, 'Spiderman', 1),
(2, 1, 'Superman', 0),
(3, 1, 'Batman', 0),
(4, 1, 'Ironman', 0),

(5, 2, 'Ironman', 1),
(6, 2, 'Captain america', 0),
(7, 2, 'Hulk', 0),
(8, 2, 'Black Widow', 0),

(9, 3, 'Hawkeye', 0),
(10, 3, 'Sule', 1),
(11, 3, 'Ironman', 0),
(12, 3, 'Hulk', 0),

(13, 4, 'Peter Parker', 1),
(14, 4, 'Punten Parker', 0),
(15, 4, 'Peter Pikir', 0),
(16, 4, 'Potter Picker', 0),

(17, 5, 'I Am Ironman', 1),
(18, 5, 'Gua manusia baja hitam', 0),
(19, 5, 'With Great Power Comes Great Responsibility', 0),
(20, 5, 'Halo aku Ironman izin lewat', 0),

(21, 6, 'Buleleng', 0),
(22, 6, 'Madura', 0),
(23, 6, 'Brooklyn', 1),
(24, 6, 'Alaska', 0),

(25, 7, 'Biru', 0),
(26, 7, 'Pink', 0),
(27, 7, 'Ungu', 0),
(28, 7, 'Hijau', 1),

(29, 8, 'Semut', 1),
(30, 8, 'Gajah', 0),
(31, 8, 'Dinosaurus', 0),
(32, 8, 'Anjing', 0),

(33, 9, 'Jarvis', 1),
(34, 9, 'Abang Faisal', 0),
(35, 9, 'Hulk', 0),
(36, 9, 'Pikachu', 0),

(37, 10, 'Green Goblin', 1),
(38, 10, 'Buto Ijo', 0),
(39, 10, 'Power Ranger', 0),
(40, 10, 'Pohon Pisang', 0);