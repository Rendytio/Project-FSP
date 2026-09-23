<?php
require_once("parent.php");

class Soal extends myparent {

    public function __construct() {
        parent::__construct();
    }

    public function getSoalByHalaman($halaman_ke) {
        $sql = "SELECT * FROM soal WHERE halaman_ke = ? ORDER BY nomor ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $halaman_ke);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function getAllSoal() {
        $sql = "SELECT * FROM soal ORDER BY nomor ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function getSoalById($idsoal) {
        $sql = "SELECT * FROM soal WHERE idsoal = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idsoal);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function getDaftarHalaman() {
        $sql = "SELECT DISTINCT halaman_ke FROM soal ORDER BY halaman_ke ASC";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }
}
?>