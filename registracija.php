<?php

session_start();
$host = "localhost";
$dbname = "apartman";
$username = "root";
$password = ""; 
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Konekcija sa bazom nije moguća: " . $e->getMessage();
    exit();
}
// Obrada forme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Uzimanje podataka iz forme
    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $email = $_POST['email'];
    $lozinka = $_POST['lozinka'];

    
    if (empty($ime) || empty($prezime) || empty($email) || empty($lozinka)) {
        echo "Sva polja su obavezna!";
    } else {
       
        $stmt = $pdo->prepare("SELECT id FROM korisnik WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            echo "Korisnik sa tim email-om već postoji!";
        } else {
          
            $hashed_password = password_hash($lozinka, PASSWORD_DEFAULT);

           
            $stmt = $pdo->prepare("INSERT INTO korisnik (ime, prezime, email, lozinka, idUloge) 
                                   VALUES (:ime, :prezime, :email, :lozinka, 2)");
            $stmt->execute([
                'ime' => $ime,
                'prezime' => $prezime,
                'email' => $email,
                'lozinka' => $hashed_password
            ]);

            echo "<script>
            
            window.location.href = 'prijava.php';
          </script>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="img/png" href="img/calendar.png">
  <title>Rezervacije | Registracija</title>
  <link href="css/style.css" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>

<body class="prijava">
<section>
    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4 m-auto">
                <div class="card border-0">
                    <div class="card-body">
                        <div class="text-center">
                            <img src="img/calendar.png" width="100rem"/>
                        </div>
                   
                        <form method="POST">
                            <input type="text" name="ime" id="" class="form-control my-4 py-2" placeholder="Ime">
                            <input type="text" name="prezime" id="" class="form-control my-4 py-2" placeholder="Prezime">
                            <input type="email" name="email" id="" class="form-control my-4 py-2" placeholder="Email">
                            <input type="password" name="lozinka" id="" class="form-control my-4 py-2" placeholder="Lozinka">
                            <div class="text-center mt-3">
                                <button class="btn btn-primary">
                                    REGISTRUJ SE
                                </button>
                                <a href="prijava.php" class="nav-link">Imaš nalog? Prijavi se.</a>
                            </div>
                        </form>
                        
                    </div>
                </div>    
           
            </div>
        </div>
    </div>
</section>
  

  <!-- Bootstrap JS  -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

</body>
</html>
