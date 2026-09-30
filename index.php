<?php
session_start();

require_once("class/soal.php");
require_once("class/jawaban.php");

$objSoal = new Soal();
$objJawaban = new Jawaban();

$daftarHalaman = array();
$resHalaman = $objSoal->getDaftarHalaman();
while($rowHalaman = $resHalaman->fetch_assoc()){
    $daftarHalaman[] = $rowHalaman["halaman_ke"];
}

$jumlahHalaman = count($daftarHalaman);
$halamanPertama = null;
if($jumlahHalaman > 0){
    $halamanPertama = $daftarHalaman[0];
}


$halaman = isset($_GET["halaman"]) ? $_GET["halaman"] : $halamanPertama;
if(isset($_POST["halaman"])){
    $halaman = $_POST["halaman"];
}


$posisiHalaman = 0;
$ketemu = false;
for($i = 0; $i < $jumlahHalaman; $i++){
    if($daftarHalaman[$i] == $halaman){
        $posisiHalaman = $i;
        $ketemu = true;
    }
}
if(!$ketemu){
    $halaman = $halamanPertama;
    $posisiHalaman = 0;
}


if(isset($_POST["aksi"]) && ($_POST["aksi"] == "Next" || $_POST["aksi"] == "Previous")){

    if(isset($_POST["jawaban"])){
        foreach($_POST["jawaban"] as $idsoal => $idjawaban){
            $pilihan = $objJawaban->getJawabanById($idjawaban);
            $_SESSION["jawaban"][$idsoal] = $idjawaban;
            $_SESSION["status"][$idsoal] = $pilihan["benarkah"];
        }
    }

    if($_POST["aksi"] == "Previous" && $posisiHalaman > 0){
        $tujuan = $daftarHalaman[$posisiHalaman - 1];
        header("location:index.php?halaman=".$tujuan);
        exit;
    }
    if($_POST["aksi"] == "Next"){
        if($posisiHalaman == $jumlahHalaman - 1){
            header("location:kesimpulan.php");
        }
        else{
            $tujuan = $daftarHalaman[$posisiHalaman + 1];
            header("location:index.php?halaman=".$tujuan);
        }
        exit;
    }
}

if($jumlahHalaman > 0){
    $resSoal = $objSoal->getSoalByHalaman($halaman);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marvel Quiz</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <div class="judul">
        <h1>Marvel Quiz</h1>
    </div>

    <?php if($jumlahHalaman == 0){ ?>
        <div class="soal"><p>Belum ada soal di database.</p></div>
    <?php }else{ ?>
        <p class="info">Halaman <?php echo $posisiHalaman + 1; ?> dari <?php echo $jumlahHalaman; ?></p>
        <form action="index.php" method="post">
            <?php while($rowSoal = $resSoal->fetch_assoc()){ ?>
                <div class="soal">
                    <h3><?php echo $rowSoal["nomor"].". ".htmlentities($rowSoal["pertanyaan"]); ?></h3>
                    <?php

                    $resJawaban = $objJawaban->getJawabanBySoal($rowSoal["idsoal"]);
                    while($rowJawaban = $resJawaban->fetch_assoc()){
                        $checked = "";
                        if(isset($_SESSION["jawaban"][$rowSoal["idsoal"]])){
                            if($_SESSION["jawaban"][$rowSoal["idsoal"]] == $rowJawaban["idjawaban"]){
                                $checked = "checked";
                            }
                        }
                    ?>
                        <label class="jawaban">
                            <input type="radio"
                                   name="jawaban[<?php echo $rowSoal["idsoal"]; ?>]"
                                   value="<?php echo $rowJawaban["idjawaban"]; ?>"
                                   <?php echo $checked; ?>>
                            <?php echo htmlentities($rowJawaban["isi_jawaban"]); ?>
                        </label>
                    <?php } ?>
                </div>
            <?php } ?>
            <input type="hidden" name="halaman" value="<?php echo $halaman; ?>">
            <div class="tombol">
                <?php if($posisiHalaman > 0){ ?>
                    <input type="submit" class="previous" name="aksi" value="Previous">
                <?php } ?>
                <input type="submit" name="aksi" value="Next">
            </div>
        </form>
    <?php } ?>
</div>
</body>
</html>
