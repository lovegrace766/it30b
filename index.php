<?php
// Database Connection
$host = 'localhost';
$db = 'it30_lab_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysq=host-$host; dbnam=$db; charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try{
    $pdo = new PDO($dsn,$user,$pass, $poptions);
    echo 'connection successful';
}catch(PDOException $e){
    die("Database connection failed" . $e->getMessage());
}

// Session
$session_start();

// Determine current section
$section = $_GET['section'] ??'students';

// Deter,ime CRUD Operation
$action = $_GET['action'] ?? '';


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library System</title>
</head>
<body>
    <h1>Simple Library System</h1>
    <nav>
       <a href= "index.php?section=students">Students</a>
       <a href= "index.php?section=books">Books</a>
       <a href= "index.php?section=borrow">Borrows\</a>
    </nav>
    <hr>
    <?php if($section === 'students'): ?>
       <h1>Students</h1>
       <?php endif;?>
    <?php if($section === 'books'): ?>
       <h1>Books</h1>
       <?php endif;?>
    <?php if($section === 'borrow'): ?>
       <h1>Borrow</h1>
       <?php endif;?>
    
    
</body>
</html>