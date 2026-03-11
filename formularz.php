<?php
session_start();
if (!isset($_SESSION['id_konta'])) {
    header("Location: index.html");
    exit;
}

$host = "localhost";
$dbname = "dbname";
$user = "user";
$pass = "pass";

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
    $idbankomatu = strtoupper($_POST['idbankomatu'] ?? null);
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

    $to = "email@email.com";
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
    ? "<p style='color:green;text-align:center;'>Raport zapisany i wysłany pomyślnie!</p> <br>
       <a href='index.html' style='display:block; text-align:center; font-size:1.5rem;'>Wyloguj</a>"
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
    <script src="script.js" defer></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">

    <script src="https://kit.fontawesome.com/490c528842.js" crossorigin="anonymous"></script>

    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
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
</body>
</html>
