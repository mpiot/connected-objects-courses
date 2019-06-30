<?php
$pdo = new PDO('mysql:host=localhost;dbname=basemmi16f13', 'mmi16f13', 'VjmP');

$action = $_GET['action'];

switch($action){
    case 'read':
        read($pdo);
        break;

    case 'write':
        write($pdo, $_GET['name'], (bool) $_GET['value']);
        break;

    default:
        echo 'not exist';
};

function read(PDO $pdo) {
    $stmt = $pdo->query("SELECT * FROM state");

    $result = [];
    while ($row = $stmt->fetch()) {
        $result[$row['name']] = (bool) $row['state'];
    }

    echo json_encode($result);
}

function write(PDO $pdo, $name, $value) {
    $stmt = $pdo->prepare("UPDATE state SET state = :state WHERE name = :field_name");
    $stmt->bindParam(':field_name', $name);
    $stmt->bindParam(':state', $value);
    $stmt->execute();

    read($pdo);
}

?>