<?php
    session_start();
    
    if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
        header("Location: prijava.php"); 
        exit();
    }
    require 'db-connect.php';
    // Preuzimanje podataka o korisnicima
    $sql = "SELECT korisnik.id, korisnik.ime, korisnik.prezime, korisnik.email, korisnik.lozinka, uloga.naziv AS uloga 
    FROM korisnik 
    JOIN uloga ON korisnik.idUloge = uloga.id";
    $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="img/png" href="img/calendar.png">
  <title>Rezervacije | Administrator</title>
  <!-- CSS -->
  <link href="css/style.css" rel="stylesheet">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<!-- Navigacija -->
  <nav class="navbar navbar-expand-lg">
    <div class="container meni">
      <a class="navbar-brand" href="index.php">
        <img src="img/calendar.png" alt="" width="60" height="60">
        Rezervacije
    </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon" ></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="admin.php">Korisnici</a>
          </li>
          <?php if (isset($_SESSION['user_id'])): ?>
          <li class="nav-item">
            <a class="nav-link" href="logout.php">Odjavi se</a>
          </li>
          <?php endif; ?>
         
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Admin - korisnici -->
  <section class="container my-5">         
    <div class="prikaz-korisnika">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ime</th>
                        <th>Prezime</th>
                        <th>Email</th>
                        <th>Uloga</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ime']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['prezime']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['uloga']) . "</td>";
                            echo "<td>"; 
                           
                            echo "<a href='obrisi-korisnika.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'><i class='bi bi-trash'></i></a>";
                            echo "</td>";
                            echo "</tr>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Nema korisnika u bazi.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</section>


  <!-- Footer -->
  <footer class="text-center py-3 mt-auto">
    <p class="mb-0">&copy; 2024 Rezervacije. Sva prava zadržana.</p>
  </footer>


  <script src="js/script.js"></script>
  <!-- Bootstrap JS + Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

  
</body>

</html>
