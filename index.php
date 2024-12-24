<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: prijava.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="img/png" href="img/calendar.png">
  <title>Rezervacije | Početna</title>
 <!-- Bootstrap CSS -->
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  
  <!-- FullCalendar CSS -->
  <link rel="stylesheet" href="fullcalendar/lib/main.min.css">

  <!-- Vaš CSS -->
  <link href="css/style.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="js/jquery-3.6.0.min.js"></script>

  <!-- FullCalendar JS -->
  <script src="fullcalendar/lib/main.min.js"></script>
  <script src="fullcalendar/lib/locales/sr.js"></script>

  
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
            <a class="nav-link active" aria-current="page" href="index.php">Početna</a>
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
  
  <!-- Kalendar -->
  <section class="container my-5">
    <div id="calendar"></div>  <!-- Div u kojem će FullCalendar biti prikazan -->
 <!-- Modal za unos rezervacije -->
<div class="modal fade" id="reservationModal" tabindex="-1" aria-labelledby="reservationModalLabel" >
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="reservationModalLabel">Nova rezervacija</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="reservationForm">
          <div class="mb-3">
            
            <input type="text" class="form-control" id="name" placeholder="Ime gosta" name="name" required>
          </div>
          <div class="mb-3">
            <textarea class="form-control" id="description" placeholder="Opis rezervacije" name="description" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <input type="date" class="form-control" id="dateFrom" name="dateFrom" required>
          </div>
          <div class="mb-3">
            <input type="date" class="form-control" id="dateTo" name="dateTo" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-primary" id="saveReservation">Sačuvaj</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal za prikaz detalja rezervacije -->
<div class="modal fade" id="detaljiModal" tabindex="-1" role="dialog" aria-labelledby="detaljiModalLabel" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detaljiModalLabel">Detalji rezervacije</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="detaljiForm">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Ime gosta" id="detaljiIme" disabled>
                    </div>
                    <div class="form-group">
                        <label for="detaljiOpis">Opis</label>
                        <textarea class="form-control" id="detaljiOpis" rows="3" disabled></textarea>
                    </div>
                  
                </form>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-id="" style="background-color:red; color:antiquewhite;" id="deleteReservationBtn">Obriši</button>

            
            </div>
        </div>
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
