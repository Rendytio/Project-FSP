<?php
session_start();

require_once("class/soal.php");
require_once("class/jawaban.php");

$objSoal = new soal();
$objJawaban = new jawaban();

$halamanPertama = $objSoal->getHalamanPertama();

$halaman = isset($_GET["halaman"]) ? $_GET["halaman"] : $halamanPertama;

if(!is_numeric($halaman)){
    $halaman = $halamanPertama;
}

if(isset($_POST["aksi"])){
    $halaman = $_POST["halaman"];

    if(!is_numeric($halaman)){
        $halaman = $halamanPertama;
    }

    if(isset($_POST["jawaban"])){
        foreach($_POST["jawaban"] as $idsoal => $idjawaban){
            $_SESSION["jawaban"][$idsoal] = $idjawaban;

            $benarkah = $objJawaban->cekJawaban($idjawaban);
            $_SESSION["status"][$idsoal] = $benarkah;
        }
    }

    if($_POST["aksi"] == "Previous"){
        $tujuan = $objSoal->getHalamanSebelumnya($halaman);

        if(!is_null($tujuan)){
            header("location:index.php?halaman=".$tujuan);
        }
    }
    else if($_POST["aksi"] == "Next"){
        $tujuan = $objSoal->getHalamanBerikutnya($halaman);

        if(is_null($tujuan)){
            header("location:kesimpulan.php");
        }
        else{
            header("location:index.php?halaman=".$tujuan);
        }
    }
}

$resSoal = $objSoal->getSoalByHalaman($halaman);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz Fullstack</title>
    <link rel="stylesheet" type="text/css" href="style/main.css">
</head>
<body>

<div class="container">
    <h1>Quiz Fullstack</h1>

    <p>Halaman <?php echo $halaman; ?></p>

    <form action="index.php?halaman=<?php echo $halaman; ?>" method="post">

        <?php
        while($rowSoal = $resSoal->fetch_assoc()){
        ?>

        <div class="soal">
            <h3>
                <?php
                echo $rowSoal["nomor"].". ".$rowSoal["pertanyaan"];
                ?>
            </h3>

            <?php
            $resJawaban = $objJawaban->getJawabanBySoal($rowSoal["idsoal"]);

            $arrJawaban = array();
            $jumlahJawaban = 0;

            while($rowJawaban = $resJawaban->fetch_assoc()){
                $arrJawaban[$jumlahJawaban] = $rowJawaban;
                $jumlahJawaban = $jumlahJawaban + 1;
            }

            $arrSudah = array();
            $jumlahTampil = 0;

            while($jumlahTampil < $jumlahJawaban){
                $indexAcak = mt_rand(0, $jumlahJawaban - 1);

                if(isset($arrSudah[$indexAcak])){
                    // index ini sudah pernah ditampilkan
                }
                else{
                    $rowJawaban = $arrJawaban[$indexAcak];

                    $checked = "";

                    if(isset($_SESSION["jawaban"][$rowSoal["idsoal"]])){
                        if($_SESSION["jawaban"][$rowSoal["idsoal"]] == $rowJawaban["idjawaban"]){
                            $checked = "checked";
                        }
                    }
            ?>

            <label class="jawaban">
                <input
                    type="radio"
                    name="jawaban[<?php echo $rowSoal["idsoal"]; ?>]"
                    value="<?php echo $rowJawaban["idjawaban"]; ?>"
                    <?php echo $checked; ?>
                >
                <?php echo $rowJawaban["isi_jawaban"]; ?>
            </label>

            <?php
                    $arrSudah[$indexAcak] = 1;
                    $jumlahTampil = $jumlahTampil + 1;
                }
            }
            ?>

        </div>

        <?php
        }
        ?>

        <input type="hidden" name="halaman" value="<?php echo $halaman; ?>">

        <?php
        $halamanSebelumnya = $objSoal->getHalamanSebelumnya($halaman);

        if(!is_null($halamanSebelumnya)){
        ?>
            <input type="submit" name="aksi" value="Previous">
        <?php
        }
        ?>

        <input type="submit" name="aksi" value="Next">

    </form>
</div>

</body>
</html>