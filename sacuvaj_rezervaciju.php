<?php
require 'db-connect.php'; 
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die(json_encode(['success' => false, 'message' => 'Prijavite se da biste nastavili.']));
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    
    $name = htmlspecialchars(trim($_POST['name']));
    $description = htmlspecialchars(trim($_POST['description']));
    $dateFrom = htmlspecialchars(trim($_POST['dateFrom']));
    $dateTo = htmlspecialchars(trim($_POST['dateTo']));

    
    if (empty($name) || empty($description) || empty($dateFrom) || empty($dateTo)) {
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => 'Sva polja su obavezna!']));
    }


    $dateFrom = date('Y-m-d', strtotime($dateFrom));
    $dateTo = date('Y-m-d', strtotime($dateTo));

    if ($dateFrom > $dateTo) {
        http_response_code(400);
        die(json_encode(['success' => false, 'message' => 'Datum od mora biti pre datuma do.']));
    }

 
    $stmt_check = $conn->prepare(
        "SELECT COUNT(*) 
         FROM rezervacije 
         WHERE korisnik = ? 
           AND (
             (? BETWEEN od AND do) 
             OR (? BETWEEN od AND do) 
             OR (od BETWEEN ? AND ?) 
             OR (do BETWEEN ? AND ?)
           )"
    );

    $stmt_check->bind_param('issssss', $userId, $dateFrom, $dateTo, $dateFrom, $dateTo, $dateFrom, $dateTo);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();

    if ($count > 0) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Već imate rezervaciju u odabranom periodu.']);
        exit;
    }

    $stmt_check->close();

    // Unos nove rezervacije
    $stmt = $conn->prepare("INSERT INTO rezervacije (ime, opis, od, do, korisnik) VALUES (?, ?, ?, ?, ?)");

    if ($stmt === false) {
        http_response_code(500);
        die(json_encode(['success' => false, 'message' => 'Greška prilikom pripreme upita: ' . $conn->error]));
    }

    $stmt->bind_param('sssss', $name, $description, $dateFrom, $dateTo, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'id' => $stmt->insert_id]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Greška prilikom čuvanja rezervacije: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare("SELECT id, ime, opis, od, do FROM rezervacije WHERE korisnik = ?");
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $reservations = [];
    while ($row = $result->fetch_assoc()) {
        $reservations[] = [
            'id' => $row['id'],
            'title' => $row['ime'],
            'description' => $row['opis'],
            'start' => $row['od'],
            'end' => $row['do'],
            'color' => '#ffb3b3' 
        ];
    }

    echo json_encode($reservations);

    $stmt->close();
    $conn->close();
    exit;
}

http_response_code(405);
echo json_encode(['success' => false, 'message' => 'Metoda nije dozvoljena.']);
?>
