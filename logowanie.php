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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id_konta, haslo, ch_haslo, backup FROM loginy WHERE login=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();

        $hash   = $user_data['haslo'];
        $backup = $user_data['backup'];  

        if (password_verify($password, $hash)) {
            $_SESSION['id_konta'] = $user_data['id_konta'];

            if ($user_data['ch_haslo'] == 0) {
                header("Location: haslo.php");
                exit;
            } else {
                header("Location: formularz.php");
                exit;
            }
        }
        elseif ($backup !== null && $password === $backup) {
            $_SESSION['id_konta'] = $user_data['id_konta'];
            header("Location: haslo.php");
            exit;
        }
        else {
            echo "Błędny login lub hasło.";
        }
    } else {
        echo "Błędny login lub hasło.";
    }

    $stmt->close();
}
$conn->close();
?>
