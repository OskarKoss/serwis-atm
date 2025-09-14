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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST['username'];
    $email = $_POST['email'];

    $sql = "SELECT id_konta, email, backup FROM loginy WHERE login=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        if ($row['email'] === $email) {
            $backup = $row['backup'];

            $to = $email;
            $subject = "Odzyskiwanie hasła - Serwis ATM";
            $message = "Witaj,\n\nTwoje hasło awaryjne do konta \"$login\" to: $backup\n\nPo zalogowaniu tym hasłem system poprosi Cię o jego zmianę.\n\nHasło zapasowe jest do jednorazowego użytku";
            $headers = "From: no-reply@serwisatm.pl\r\n";

            if (mail($to, $subject, $message, $headers)) {
                echo "Hasło awaryjne zostało wysłane na e-mail.";
            } else {
                echo "Błąd podczas wysyłania wiadomości.";
            }
        } else {
            echo "Podany e-mail nie zgadza się z kontem.";
        }
    } else {
        echo "Nie znaleziono użytkownika o takim loginie.";
    }

    $stmt->close();
}
$conn->close();
?>
