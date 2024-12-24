<?php 
require 'db-connect.php'; 
session_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $reservationId = $_POST['id'];

    if (empty($reservationId)) {
        echo json_encode(['success' => false, 'message' => 'ID rezervacije nije poslat ili je prazan.']);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM rezervacije WHERE id = ?");
    $stmt->bind_param('i', $reservationId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Greška prilikom brisanja rezervacije.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Neispravan zahtev.']);
}

$conn->close();

?>