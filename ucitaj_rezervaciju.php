<?php
require 'db-connect.php';

// SQL upit za dobijanje svih rezervacija
$query = "SELECT * FROM rezervacije";
$result = $conn->query($query);

$events = [];
while ($row = $result->fetch_assoc()) {
    $events[] = $row;
}

echo json_encode($events);


$conn->close();
?>
