<?php
session_start();

$host = "localhost";
$dbname = "srv85578_serwis_atm";
$user = "srv85578_serwis_atm";
$pass = "drz2YMcjYbSQEnzfra4n";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}

if (!isset($_SESSION['id_konta'])) {
    header("Location: index.html");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new1 = $_POST['password1'];
    $new2 = $_POST['password2'];

    if ($new1 === $new2 && strlen($new1) >= 6) {
        $hash = password_hash($new1, PASSWORD_DEFAULT);

        $newBackup = str_pad(rand(0, 999999), 6, "0", STR_PAD_LEFT);

        $sql = "UPDATE loginy SET haslo=?, ch_haslo=1, backup=? WHERE id_konta=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $hash, $newBackup, $_SESSION['id_konta']);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        session_unset();
        session_destroy();
        header("Location: index.html");
        exit;
    } else {
        echo "Hasła muszą być identyczne i mieć co najmniej 6 znaków!";
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zmiana hasła</title>
    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    
    <script src="https://kit.fontawesome.com/490c528842.js" crossorigin="anonymous"></script>
</head>
<body id="body">
    <i class="fa-solid fa-sun ikona" id="ikona"></i>
    <header class="m1">
        <h1 class="tytul">Serwis<span class="red">ATM</span></h1>
    </header>
    <main class="ramka">
        <form method="post">
            <h2 class="log">Zmień hasło</h2>
            <input type="password" name="password1" placeholder="Nowe hasło*" required>
            <br>
            <input type="password" name="password2" placeholder="Powtórz hasło*" required>
            <br>
            <input type="submit" value="Zmień hasło" class="btn">
            <a href="index.html" id="link">Powrót do strony logowania</a>
        </form>
        <p class="credits">&copyOskarKoss</p>
    </main>

    <script>
    const icon = document.getElementById("ikona");

    icon.addEventListener("click", () => {
        if (icon.classList.contains("fa-sun")) {
            icon.classList.remove("fa-sun", "fa-solid");
            icon.classList.add("fa-moon", "fa-regular");

            document.getElementById("body").style.transition = "all 1s";
            document.getElementById('ikona').style.color = 'white';
            document.getElementById('body').style.backgroundColor = '#1E1E1E';
            document.getElementById('body').style.color = 'white';
            document.getElementById('link').style.color = 'white';
        } 
        else if (icon.classList.contains("fa-moon")) {
            icon.classList.remove("fa-moon", "fa-regular");
            icon.classList.add("fa-sun", "fa-solid");

            document.getElementById("body").style.transition = "all 1s";
            document.getElementById('ikona').style.color = 'black';
            document.getElementById('body').style.backgroundColor = 'white';
            document.getElementById('body').style.color = 'black';
            document.getElementById('link').style.color = 'black';
        }
    });
</script>
</body>
</html>
