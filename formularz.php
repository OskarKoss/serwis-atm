<?php
session_start();
if (!isset($_SESSION['id_konta'])) {
    header("Location: index.html");
    exit;
}

$host = "localhost";
$dbname = "srv85578_serwis_atm";
$user = "srv85578_serwis_atm";
$pass = "drz2YMcjYbSQEnzfra4n";

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Błąd połączenia");
}

$id_konta = $_SESSION['id_konta'];
$stmt = $conn->prepare("SELECT imie, nazwisko FROM serwisanci WHERE id_konta=?");
$stmt->bind_param("i", $id_konta);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$imie = $user_data['imie'] ?? '';
$nazwisko = $user_data['nazwisko'] ?? '';
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = $_POST['data'] ?? null;
    $idbankomatu = $_POST['idbankomatu'] ?? null;
    $czas = $_POST['czas'] ?? null;
    $dojazd = $_POST['dojazd'] ?? null;
    $czesci = trim($_POST['czesci'] ?? '') ?: "Brak";
    $nota = trim($_POST['nota'] ?? '') ?: "Brak";

    $rodzaj = null;
    if (isset($_POST['naprawa'])) {
        $rodzaj = "naprawa";
    } elseif (isset($_POST['przeglad'])) {
        $rodzaj = "przegląd";
    }

    $stmt = $conn->prepare(
        "INSERT INTO serwisy (id_serwisanta, data_serwisu, id_bankomatu, rodzaj_serwisu, czas_trwania, dojazd, czesci, opis)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("isssiiss", $id_konta, $data, $idbankomatu, $rodzaj, $czas, $dojazd, $czesci, $nota);
    $stmt->execute();
    $stmt->close();

    $to = "okossakowski08@gmail.com";
    $from = "noreply@serwisatm.pl";

    $subject = "=?UTF-8?B?" . base64_encode("Raport serwisowy: $imie $nazwisko, Bankomat $idbankomatu") . "?=";

    $message = "Nowy raport serwisowy.\n\n" .
               "Serwisant: $imie $nazwisko\n" .
               "Data: $data\n" .
               "Bankomat: $idbankomatu\n" .
               "Rodzaj: $rodzaj\n" .
               "Czas: $czas rbh\n" .
               "Dojazd: $dojazd km\n" .
               "Części: $czesci\n" .
               "Uwagi: $nota\n";

    $headers  = "From: Serwis ATM <$from>\r\n";
    $headers .= "Reply-To: $from\r\n";
    $headers .= "Return-Path: $from\r\n";
    $headers .= "MIME-Version: 1.0\r\n";

    $sent = false;

    if (!empty($_FILES['photo']['tmp_name'])) {
        $file_tmp = $_FILES['photo']['tmp_name'];
        $file_name = basename($_FILES['photo']['name']);
        $file_type = mime_content_type($file_tmp);

        if (in_array($file_type, ['image/jpeg', 'image/png', 'application/pdf'])) {
            $boundary = md5(time());
            $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

            $body  = "--$boundary\r\n";
            $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
            $body .= $message . "\r\n";
            $body .= "--$boundary\r\n";
            $body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n\r\n";
            $body .= chunk_split(base64_encode(file_get_contents($file_tmp)));
            $body .= "--$boundary--";

            $sent = mail($to, $subject, $body, $headers, "-f$from");
        }
    }

    if (!$sent) {
        $sent = mail($to, $subject, $message, $headers, "-f$from");
    }

    echo $sent
        ? "<p style='color:green;text-align:center;'>Raport zapisany i wysłany pomyślnie!</p>"
        : "<p style='color:red;text-align:center;'>Błąd wysyłania maila</p>";
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formularz zgłoszenia</title>
    <link rel="stylesheet" href="style.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/490c528842.js" crossorigin="anonymous"></script>
</head>
<body id="body">
    <i class="fa-solid fa-sun ikona" id="ikona"></i>
    <header>
        <h1>Witaj <?php echo htmlspecialchars($imie . " " . $nazwisko); ?>!</h1>
    </header>
    <h2 class="centra">Serwis<span class="red">ATM</span></h2>
    <main>
        <form method="post" action="" enctype="multipart/form-data" onsubmit="return validateFileUpload();">
            <label for="data">Data:</label><br>
            <input type="date" id="data" name="data" required value="<?php echo date('Y-m-d'); ?>" class="formula"><br><br>

            <label for="idbankomatu">ID Bankomatu:</label><br>
            <input type="text" id="idbankomatu" name="idbankomatu" pattern="^[A-Z]{4}[0-9]{4}$" required placeholder="ABCD1234" title="Wprowadź ID o poprawnym formacie: SGBNXXXX/BPSNXXXX" class="formula"><br><br>

            <h2>Rodzaj serwisu</h2>
            <div>
                <label><input type="checkbox" name="naprawa" id="naprawa" onchange="updateForm()"> Naprawa</label>
                <label><input type="checkbox" name="przeglad" id="przeglad" onchange="updateForm()"> Przegląd</label>
            </div><br>

            <div id="dynamicForm"></div><br>

            <h2>Załącznik</h2>
            <label for="photo" id="zalacznik">Dodaj zdjęcie lub PDF (tylko jeden plik)</label><br>
            <div class="flex">
                <div class="centra2">
                    <input type="file" id="photo" name="photo" accept="image/*,.pdf" class="centra1" required>
                    <i class="fa-solid fa-file-upload ikonka"></i>
                </div>
            </div>
            <input type="submit" value="Wyślij formularz" class="btn">
        </form>
        <a href="https://github.com/OskarKoss" class="credits" id="link">&copy;OskarKoss</a>
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
            
        } else if (icon.classList.contains("fa-moon")) {
            icon.classList.remove("fa-moon", "fa-regular");
            icon.classList.add("fa-sun", "fa-solid");
            document.getElementById("body").style.transition = "all 1s";
            document.getElementById('ikona').style.color = 'black';
            document.getElementById('body').style.backgroundColor = 'white';
            document.getElementById('body').style.color = 'black';
            document.getElementById('link').style.color = 'black';
        }
    });

    function updateForm() {
        const naprawa = document.getElementById('naprawa').checked;
        const przeglad = document.getElementById('przeglad').checked;
        const form = document.getElementById('dynamicForm');

        form.innerHTML = '';

        if (naprawa || przeglad) {
            if (naprawa) {
                form.innerHTML += `
                    <label>Czas:<br><input type="number" name="czas" placeholder="rbh" class="formula"/></label><br><br>
                    <label>Dojazd:<br><input type="number" name="dojazd" placeholder="km" class="formula"/></label><br><br>
                    <label>Części:<br><textarea name="czesci" rows="4" cols="40" placeholder="Użyte części" class="formula"></textarea></label><br><br>
                    <label>Opis/Uwagi:<br><textarea name="nota" rows="4" cols="40" placeholder="Opis lub uwagi" class="formula"></textarea></label><br><br>
                `;
            } else if (przeglad) {
                form.innerHTML += `
                    <label>Dojazd:<br><input type="number" name="dojazd" placeholder="km" class="formula"/></label><br><br>
                    <label>Opis/Uwagi:<br><textarea name="nota" rows="4" cols="40" placeholder="Opis lub uwagi" class="formula"></textarea></label><br><br>
                `;
            }
        }
    }

    function validateFileUpload() {
        const fileInput = document.getElementById("photo");
        if (!fileInput.value) {
            alert("Musisz dodać jeden załącznik (zdjęcie lub PDF)!");
            return false;
        }
        return true;
    }
    </script>
</body>
</html>