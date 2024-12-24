<?php
session_start();


if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: prijava.php");
    exit();
}

require 'db-connect.php';

// Provjera da li je prosleđen ID korisnika za brisanje
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

  
    $sql = "DELETE FROM korisnik WHERE id = ?";
    
  
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        
        header("Location: admin.php");
        exit();
    } else {
        echo "Došlo je do greške pri brisanju korisnika.";
    }
} else {
    echo "ID korisnika nije prosleđen.";
}
?>
