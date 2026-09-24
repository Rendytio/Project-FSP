<?php
session_start();

require_once("class/soal.php");
require_once("class/jawaban.php");

$objSoal = new Soal();
$objJawaban = new Jawaban();




$resHalaman =
$objSoal->getDaftarHalaman();

$rowHalaman =
$resHalaman->fetch_assoc();

$halamanPertama =
$rowHalaman["halaman_ke"];




$halaman =
isset($_GET['halaman']) ?
$_GET['halaman'] :
$halamanPertama;

if(!is_numeric($halaman)){
    $halaman = $halamanPertama;
}




if(isset($_POST['aksi'])){

    $halaman =
    $_POST['halaman'];

    if(!is_numeric($halaman)){
        $halaman = $halamanPertama;
    }


  

    if(isset($_POST['jawaban'])){

        foreach(
            $_POST['jawaban']
            as $idsoal => $idjawaban
        ){

            $_SESSION['jawaban'][$idsoal]
            = $idjawaban;



            $rowJawaban =
            $objJawaban->getJawabanById(
                $idjawaban
            );

            $benarkah =
            $rowJawaban["benarkah"];

            $_SESSION['status'][$idsoal]
            = $benarkah;
        }
    }



    if($_POST['aksi'] == "Previous"){

        $tujuan = null;

        $resHalaman =
        $objSoal->getDaftarHalaman();

        while(
            $rowHalaman =
            $resHalaman->fetch_assoc()
        ){

            if(
                $rowHalaman["halaman_ke"]
                <
                $halaman
            ){

                $tujuan =
                $rowHalaman["halaman_ke"];
            }
        }

        if(!is_null($tujuan)){

            header(
                "location:index.php?halaman=".
                $tujuan
            );
        }
    }


  

    else if($_POST['aksi'] == "Next"){

        $tujuan = null;

        $resHalaman =
        $objSoal->getDaftarHalaman();

        while(
            $rowHalaman =
            $resHalaman->fetch_assoc()
        ){

            if(
                $rowHalaman["halaman_ke"]
                >
                $halaman
                &&
                is_null($tujuan)
            ){

                $tujuan =
                $rowHalaman["halaman_ke"];
            }
        }


     

        if(is_null($tujuan)){

            header(
                "location:kesimpulan.php"
            );

        }else{

            header(
                "location:index.php?halaman=".
                $tujuan
            );
        }
    }
}




$resSoal =
$objSoal->getSoalByHalaman(
    $halaman
);



$halamanSebelumnya = null;

$resHalaman =
$objSoal->getDaftarHalaman();

while(
    $rowHalaman =
    $resHalaman->fetch_assoc()
){

    if(
        $rowHalaman["halaman_ke"]
        <
        $halaman
    ){

        $halamanSebelumnya =
        $rowHalaman["halaman_ke"];
    }
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
    name="viewport"
    content="width=device-width, initial-scale=1.0">

    <title>Quiz Fullstack</title>

    <link
    rel="stylesheet"
    href="css/style.css">

</head>


<body>

<div class="container">

    <h1>Quiz Fullstack</h1>

    <p>
        Halaman:
        <?php echo $halaman; ?>
    </p>


    <form
    action="index.php?halaman=<?php echo $halaman; ?>"
    method="post">


    <?php

    while(
        $rowSoal =
        $resSoal->fetch_assoc()
    ){

    ?>

        <div class="soal">

            <h3>

            <?php

            echo
            $rowSoal["nomor"].
            ". ".
            $rowSoal["pertanyaan"];

            ?>

            </h3>


            <?php

            $resJawaban =
            $objJawaban->getJawabanBySoal(
                $rowSoal["idsoal"]
            );


            while(
                $rowJawaban =
                $resJawaban->fetch_assoc()
            ){

                $checked = "";


            

                if(
                    isset(
                        $_SESSION['jawaban']
                        [$rowSoal["idsoal"]]
                    )
                ){

                    if(
                        $_SESSION['jawaban']
                        [$rowSoal["idsoal"]]
                        ==
                        $rowJawaban["idjawaban"]
                    ){

                        $checked =
                        "checked";
                    }
                }

            ?>


                <label class="jawaban">

                    <input
                    type="radio"

                    name="jawaban[
                    <?php
                    echo $rowSoal["idsoal"];
                    ?>
                    ]"

                    value="<?php
                    echo $rowJawaban["idjawaban"];
                    ?>"

                    <?php
                    echo $checked;
                    ?>
                    >

                    <?php
                    echo
                    $rowJawaban["isi_jawaban"];
                    ?>

                </label>


            <?php
            }
            ?>


        </div>


    <?php
    }
    ?>


        <input
        type="hidden"
        name="halaman"
        value="<?php echo $halaman; ?>">


        <?php

        if(
            !is_null(
                $halamanSebelumnya
            )
        ){

        ?>

            <input
            type="submit"
            name="aksi"
            value="Previous">

        <?php
        }
        ?>


        <input
        type="submit"
        name="aksi"
        value="Next">


    </form>

</div>


</body>

</html>