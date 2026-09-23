<?php
require_once("parent.php");

class Jawaban extends myparent {

    public function __construct() {
        parent::__construct();
    }

    public function getJawabanBySoal($idsoal) {
        $sql = "SELECT * FROM jawaban WHERE idsoal = ? ORDER BY RAND()";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idsoal);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res;
    }

    public function getJawabanById($idjawaban) {
        $sql = "SELECT * FROM jawaban WHERE idjawaban = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idjawaban);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }

    public function getJawabanBenarBySoal($idsoal) {
        $sql = "SELECT * FROM jawaban WHERE idsoal = ? AND benarkah = 1";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("i", $idsoal);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_assoc();
    }
}
?>