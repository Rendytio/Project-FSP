<?php
session_start();

require_once("class/soal.php");
require_once("class/jawaban.php");

// 1. Cek jika tombol PLAY AGAIN diklik
if (isset($_POST['play_again'])) {
    session_unset();    // Hapus semua data di session
    session_destroy();  // Hancurkan session
    header("Location: index.php"); // Kembali ke halaman awal
    exit();
}

$objSoal = new Soal();
$objJawaban = new Jawaban();

// Ambil array jawaban & status dari session
$jawabanUser = isset($_SESSION['jawaban']) ? $_SESSION['jawaban'] : array();
$statusUser  = isset($_SESSION['status']) ? $_SESSION['status'] : array();

$totalSkor = 0;
$poinPerSoal = 10; // Nilai 10 poin per nomor yang benar

// Ambil semua daftar halaman/soal dari database
$resHalaman = $objSoal->getDaftarHalaman();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Halaman Kesimpulan</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <h1>Halaman Kesimpulan</h1>
    <p>Menampilkan semua soal dan jawaban beserta skor akhir.<br>
    Setiap 1 soal yang benar akan mendapatkan nilai 10</p>

    <ol>
    <?php 
    // Loop seluruh halaman untuk mengambil soal-soal yang ada
    while ($rowHalamans = $resHalaman->fetch_assoc()) {
        $resSoal = $objSoal->getSoalByHalaman($rowHalamans['halaman_ke']);
        
        while ($rowSoal = $resSoal->fetch_assoc()) {
            $idsoal = $rowSoal['idsoal'];
            
            // Cek apakah user menjawab soal ini (cara lama)
            $idjawabanUser = isset($jawabanUser[$idsoal]) ? $jawabanUser[$idsoal] : null;
            $isBenar       = isset($statusUser[$idsoal]) ? $statusUser[$idsoal] : 0;

            // Ambil teks jawaban user dari DB
            $teksJawabanUser = "-";
            if (!is_null($idjawabanUser)) {
                $rowJawabanUser = $objJawaban->getJawabanById($idjawabanUser);
                if ($rowJawabanUser) {
                    $teksJawabanUser = $rowJawabanUser['isi_jawaban'];
                }
            }

            // Hitung skor akhir
            if ($isBenar == 1) {
                $totalSkor += $poinPerSoal;
            }

            // Tentukan class warna teks berdasarkan kondisi $isBenar
            $colorClass = ($isBenar == 1) ? 'text-green' : 'text-red';

            // Selalu cari teks jawaban yang benar dari DB untuk setiap soal
            $teksJawabanBenar = "";
            $resJawaban = $objJawaban->getJawabanBySoal($idsoal);
            while ($rowJwb = $resJawaban->fetch_assoc()) {
                if ($rowJwb['benarkah'] == 1) {
                    $teksJawabanBenar = $rowJwb['isi_jawaban'];
                    break;
                }
            }
            ?>
           <li class="soal-item">
                <?php echo htmlentities($rowSoal['pertanyaan']); ?>
                <div class="jawaban-info">
                    <span class="<?php echo $colorClass; ?>">
                        Jawaban kamu : <?php echo htmlentities($teksJawabanUser); ?> 
                        (<?php echo ($isBenar == 1) ? 'benar' : 'salah'; ?>)
                    </span>
                    
                    <br>Jawaban yang benar : <?php echo htmlentities($teksJawabanBenar); ?>
                </div>
            </li>
            <?php
        }
    }
    ?>
    </ol>

    <div class="skor-container">
        Skor Akhir : <?php echo $totalSkor; ?>
    </div>

    <!-- Tombol PLAY AGAIN -->
    <form method="POST" action="">
        <button type="submit" name="play_again" class="btn-play-again">PLAY AGAIN</button>
    </form>

</body>
</html>