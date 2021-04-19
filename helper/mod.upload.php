<?php
session_start();
$modPathTemp = "../localstorage/upload/mods/";
$picPathTemp = "../localstorage/upload/pictures/";

$path = "../localstorage/upload/";
$url = "https://storage.fivemods.net/upload/mods/";

require_once('../config.php');
if (isset($_COOKIE['f_key']) || isset($_COOKIE['f_val'])) {

    $pdo = new PDO('mysql:dbname=' . $mysql['dbname'] . ';host=' . $mysql['servername'] . '', '' . $mysql['username'] . '', '' . $mysql['password'] . '');

    $selToken = $pdo->prepare("SELECT * FROM sessions WHERE newid = ?");
    $selToken->execute(array($_COOKIE['f_key']));
    if ($selToken->rowCount() == 0) {
        print_r("NOT_LOGGED_IN");
		exit();
		die();
    } 
} else {
    print_r("NOT_LOGGED_IN");
	exit();
	die();
}

if ($_SERVER ["REQUEST_METHOD"] === "POST") {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $tags = $_POST['tags'];
    $required = empty($_POST['required']) ? NULL : $_POST['required'];
    $id = $_POST['id'];

    if(empty($title) || empty($description) || empty($category) || empty($tags)) {
        print_r("ERR_EMPTY");
        exit();
        die();
    }
    
    foreach (scandir($modPathTemp) as $dir) {
        if(startsWith($dir, $id)) {
            $filePath = $path . $id . "/" . preg_replace("/([\/#&%§$.,]{1,})/", "_",str_replace(" ", "_", strtolower($title))) . "." . strtolower(end(explode('.', $dir)));
            mkdir($path . $id);
            rename($modPathTemp . $dir, $filePath);

            $downloadLink = $url . $id . "/" . preg_replace("/([\/#&%§$.,]{1,})/", "_",str_replace(" ", "_", strtolower($title))) . "." . strtolower(end(explode('.', $dir)));
        }
        
    }

    $pics = [];

    foreach (scandir($picPathTemp) as $dir) {
        if(startsWith($dir, $id)) {
            $fileName = randomChars();
            $filePath = $path . $id . "/" . $fileName . "." . strtolower(end(explode('.', $dir)));
            rename($picPathTemp . $dir, $filePath);
            array_push($pics, $url . $id . "/" . $fileName . "." . strtolower(end(explode('.', $dir))));
        }
    }
    $selVals = $pdo->prepare("SELECT * FROM user WHERE uuid = ?");
    $selVals->execute(array($_SESSION['uuid']));
    $vals = $selVals->fetch();

    $statement = $pdo->prepare("INSERT INTO mods (m_authorid, m_name, m_picture, m_category, m_tags, m_description, m_requiredmod, m_downloadlink) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $statement->execute(array($vals['id'], $title, implode(" ", $pics), $category, $tags, $description, $required, $downloadLink));
}

function randomChars($length = 6)
{
   $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
   return substr(str_shuffle($permitted_chars), 0, $length);
}

function startsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    return substr( $haystack, 0, $length ) === $needle;
}

?>