<?php
session_start(); 

ini_set('display_errors', 1);
error_reporting(E_ALL);


if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role_id'] == 2) {
        header("Location: index.php"); 
        exit();
    } elseif ($_SESSION['role_id'] == 1) {
        header("Location: admin.php"); 
        exit();
    }
}

// Obrada forme za prijavu
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $lozinka = trim($_POST['lozinka']);
    
    // Validacija da li su oba polja popunjena
    if (empty($email) || empty($lozinka)) {
        $error = "Molimo Vas da popunite sve podatke!";
    } else {
        // Povezivanje sa bazom
        $db = new mysqli('localhost', 'root', '', 'apartman'); 

        if ($db->connect_error) {
            die("Greška pri povezivanju sa bazom: " . $db->connect_error);
        }

        // Sprečavanje SQL injekcije
        $email = $db->real_escape_string($email);

        // Pretraga korisnika po emailu
        $query = "SELECT * FROM korisnik WHERE email = '$email'";
        $result = $db->query($query);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

          
            if (password_verify($lozinka, $user['lozinka'])) {
               
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role_id'] = $user['idUloge'];
                $_SESSION['ime'] = $user['ime'];
                $_SESSION['prezime'] = $user['prezime'];

              
                if ($user['idUloge'] == 1) {
                    header("Location: admin.php");
                } elseif ($user['idUloge'] == 2) {
                    header("Location: index.php"); 
                }
                exit();
            } else {
                $error = "Pogrešna lozinka!";
            }
        } else {
            $error = "Korisnik sa ovim emailom ne postoji!";
        }

        $db->close();
    }
}
?>

<!DOCTYPE html>
<html lang="sr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rezervacije | Prijava</title>
  <link rel="icon" type="img/png" href="img/calendar.png">
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
                       
                        <form action="prijava.php" method="POST">
                            <?php
                            if (isset($error)) {
                                echo "<div class='alert alert-danger'>$error</div>";
                            }
                            ?>
                            <input type="email" name="email" class="form-control my-4 py-2" placeholder="Email" required>
                            <input type="password" name="lozinka" class="form-control my-4 py-2" placeholder="Lozinka" required>
                            <div class="text-center mt-3">
                                <button class="btn btn-primary" type="submit">
                                    PRIJAVA
                                </button>
                                <a href="registracija.php" class="nav-link">Nemaš nalog? Registruj se.</a>
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
