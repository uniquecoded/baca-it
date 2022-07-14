<?php 

// koneksi database
$host = "localhost";
$username = "root";
$password = "";
$db = "baca-it";

$conn = mysqli_connect($host, $username, $password, $db);

// query
function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while( $row = mysqli_fetch_assoc($result) ) {
        $rows [] = $row;
    }
    return $rows;
}

// delete ebook
function hapus($id) {
    global $conn;
    mysqli_query($conn, "DELETE FROM ebook WHERE id = $id");
    return mysqli_affected_rows($conn);

}

// upload ebook
function tambah($data) {
    global $conn;
    $judul = htmlspecialchars($data["judul"]);
    $deskripsi = htmlspecialchars($data["deskripsi"]);
    $pdf = upload();
    if(!$pdf) {
        return false;
    }

$query = "INSERT INTO ebook VALUES ('', '$judul', '$deskripsi', '$pdf')";

mysqli_query($conn, $query);

return mysqli_affected_rows($conn);

}


// upload pdf

function upload() {
    $namaFile = $_FILES['pdf']['name'];
    $ukuranFile = $_FILES['pdf']['size'];
    $error = $_FILES['pdf']['error'];
    $tmpName = $_FILES['pdf']['tmp_name'];

    if( $error === 4) {
        echo"<script>
                alert('pilih gambar terlebih dahulu');
        </script>";
    return false;
    }

    $ektensiPdfValid = ['pdf'];
    $ektensiPdf = strtolower(end($ektensiPdfValid));

    if(!in_array($ektensiPdf, $ektensiPdfValid) ) {
        echo "<script>
            alert('yang kamu upload bukan pdf!');
        </script>";
        return false;
    }


    if ( $ukuranFile > 100000000 ) {
        echo "<script>
            alert('ukuran pdf terlalu besar');
        </script>";
        return false;
    }


    $namaFileBaru = uniqid();
    $namaFileBaru .= ".";
    $namaFileBaru .= $ektensiPdf;

    move_uploaded_file($tmpName, 'pdf/' . $namaFileBaru);
    return$namaFileBaru;



}

?>