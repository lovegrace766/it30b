<?php
// Database Connection
$host = 'localhost';
$db = 'it30b_db_lab';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo 'Connection successful';
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Session
session_start();

// Determine current section
$section = $_GET['section'] ?? '';

// Determine CRUD Operation
$action = $_GET['action'] ?? '';

// Fetch Students
$students = [];

if ($section === 'students') {

    $stmt = $pdo->query("
        SELECT *
        FROM students
        ORDER BY student_id DESC
    ");

    $students = $stmt->fetchAll();
}

// Create Student
if($section==='students' && $action==='create'){

    if($_SERVER['REQUEST_METHOD']==='POST') {

        $firstName = trim($_POST['student_first_name'] ?? '');
        $lastName = trim($_POST['student_last_name'] ?? '');
        $course = trim($_POST['student_course'] ?? '');

        if($firstName !=='' && $lastName !=='' && $course !== ''){
            $sql = "
                INSERT INTO students(
                student_first_name,
                student_last_name,
                student_course
            )
            VALUES (?,?,?)
        ";

        $stmt=$pdo->prepare($sql);

        $stmt->execute([
            $firstName,
            $lastName,
            $course
        ]);

        header("Location: index.php?section=students");
        exit;

        }
    }
}

// Update  Student
if($section==='students' && $action==='update'){
    $studentId = (int) ($_GET['id']) ?? 00;

    if($_SERVER['REQUEST_METHOD'] ==='POST'){

        $firstName = $_POST['student_first_name'] ?? '';
        $lastName = $_POST['student_last_name'] ?? '';
        $course = $_POST['student_course'] ?? '';


        $sql=("
            UPDATE STUDENTS
            SET
                student_first_name = ?,
                student_last_name = ?,
                student_course = ?
            WHERE student_id = ?
        ");

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $firstName,
            $lastName,
            $course,
            $studentId
        ]);

        header("Location: index.php?section=students");
        exit;

    }

    // Retrieve student info by default

    $stmt = $pdo->prepare("
        SELECT *
        FROM students
        WHERE student_id = ?
    ");

    $stmt->execute([$studentId]);

    $student = $stmt->fetch();

    if(!$student){
        die("Student Not Found");

    }
}

// Fetch Books
$books = [];

if ($section === 'books') {

    $stmt = $pdo->query("
        SELECT *
        FROM books
        ORDER BY book_id DESC
    ");

    $books = $stmt->fetchAll();
}

// Create Books
if($section==='books' && $action==='create'){

    if($_SERVER['REQUEST_METHOD']==='POST') {

        $bookTitle = trim($_POST['book_title'] ?? '');
        $bookAuthor = trim($_POST['book_author'] ?? '');
        $bookCategory = trim($_POST['book_category'] ?? '');

        if($bookTitle !=='' && $bookAuthor !=='' && $bookCategory !== ''){
            $sql = "
                INSERT INTO books(
                book_title,
                book_author,
                book_category
            )
            VALUES (?,?,?)
        ";

        $stmt=$pdo->prepare($sql);

        $stmt->execute([
            $bookTitle,
            $bookAuthor,
            $bookCategory
        ]);

        header("Location: index.php?section=books");
        exit;

        }
    }
}

// Update  Books
if($section==='books' && $action==='update'){
    $bookId = (int) ($_GET['id']) ?? 00;

    if($_SERVER['REQUEST_METHOD'] ==='POST'){

        $bookTitle = $_POST['book_title'] ?? '';
        $bookAuthor = $_POST['book_author'] ?? '';
        $bookCategory = $_POST['book_category'] ?? '';

    if($bookTitle !=='' && $bookAuthor !=='' && $bookCategory !== ''){
        $sql="
            UPDATE BOOKS
            SET
                book_title = ?,
                book_author = ?,
                book_category = ?
            WHERE book_id = ?
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            $bookTitle,
            $bookAuthor,
            $bookCategory,
            $bookId
        ]);

        header("Location: index.php?section=books");
        exit;

    }
    }

    // Retrieve books info by default

    $stmt = $pdo->prepare("
        SELECT *
        FROM books
        WHERE book_id = ?
    ");

    $stmt->execute([$bookId]);

    $book = $stmt->fetch();

    if(!$book){
        die("Books Not Found");

    }
}

// RETRIEVE BORROWED BOOKS
if ($section=='borrow'){

    // Retreive students
    $stmt = $pdo->prepare("
    SELECT 
        student_id,
        student_first_name,
        student_last_name
    FROM students
    ORDER BY student_last_name, student_first_name
    ");

    $students = $stmt->fetchAll();

    // Retrieve books
    $stmt = $pdo->prepare("
    SELECT 
        book_id,
        book_title,
        book_author
    FROM books
    ORDER BY book_title
    ");

    $books = $stmt->fetchAll();
}



// CREATE BORROW
if($section==='borrow' && $action==='create'){

     if($_SERVER['REQUEST_METHOD'] === 'POST'){

       $studentId = (int) ($_POST['student_id'] ?? 00);
       $bookId = (int) ($_POST['book_id'] ?? 00);

       if($studentId >0 && $bookId >0){

        // Check if student has an unreturned book
        $stmt = $pdo->prepare("
            SELECT borrow_id
            FROM borrow
            WHERE student_id=?
                  AND borrow_return_date IS NULL
            LIMIT 1    
        ");

        $stmt->execute([$studentId]);

        $studentBorrow = $stmt->fetch();

        if($studentBorrow){
            $_SESSION['alert'] = 'This student cannot borrow another book because a previous book has not been returned';
        }else{
            // Check if book is already borrowed
            $stmt = $pdo->prepare("
                 SELECT borrow_id
                 FROM borrow
                 WHERE book_id = ?
                        AND borrow_return_date IS NULL
                LIMIT 1
            ");


            $stmt->execute([$borrowId]);

            $bookBorrow = $stmt->fetch();

            if($bookBorrow){
                $_SESSION['alert'] = 'This book cannot be borrowed because it has not been returned.';
            } else {
                // CREATE BORROW RECORD FINALLY!
                $stmt = $pdo->prepare("
                     INSERT INTO borrow(
                       student_id,
                       book_id
                    )
                       VALUES(?,?)   
                ");

                $stmt->execute([
                    $studentId,
                    $bookId
                ]);

                $_SESSION['alert'] = 'Book borrowed successfully.';

            }
      }
      header("Location: index.php?section=borrow");
      exit;
    }
  }
}





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
        <a href="index.php?section=students">Students</a>
        <a href="index.php?section=books">Books</a>
        <a href="index.php?section=borrow">Borrow</a>
    </nav>

    <hr>

    <?php if ($section === 'students'): ?>
        <h1>Students</h1>

        <p>
            <a href="index.php?section=students&action=create">
                Add New Student
            </a>
        </p>

        <?php if($action==='create'): ?>
            <h2>Create Student</h2>

            <form method="POST">
            <p>
                <label>First Name:</label>
                <br>
                <input  type="text"
                        name="student_first_name"
                        required
                />
            </p>
            <p>
                <label>Last Name:</label>
                <br>
                <input  type="text"
                        name="student_last_name"
                        required
                />
            </p>
            <p>
                <label>Course:</label>
                <br>
                <input  type="text"
                        name="student_course"
                        required
                />
            </p>

            <button type="submit">
                Save
            </button>

            <a href="index.php?section=students">
                Cancel
            </a>

            </form>
            
    <?php elseif($action==='update'): ?>
            <h2>Update Student Info</h2>

            <form method="POST">
            <p>
                <label>First Name:</label>
                <br>
                <input  type="text"
                        name="student_first_name"
                        value="<?= htmlspecialchars($student['student_first_name']) ?>"
                        required
                />
            </p>
            <p>
                <label>Last Name:</label>
                <br>
                <input  type="text"
                        name="student_last_name"
                        value="<?= htmlspecialchars($student['student_last_name']) ?>"
                        required
                />
            </p>
            <p>
                <label>Course:</label>
                <br>
                <input  type="text"
                        name="student_course"
                        value="<?= htmlspecialchars($student['student_course']) ?>"
                        required
                />
            </p>

            <button type="submit">
                Save
            </button>

            <a href="index.php?section=students">
                Cancel
            </a>

            </form>

            <?php else: ?>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Course</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($students as $student): ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars($student['student_id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($student['student_first_name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($student['student_last_name']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($student['student_course']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($student['student_created_at']) ?>
                        </td>

                        <td>
                            <a href="index.php?section=students&action=update&id=<?=$student['student_id']?> "
                            >Edit
                            </a>

                            <a href="#">Delete</a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif;?>

    <?php elseif ($section === 'books'): ?>

        <h2>Books</h2>
        <p>
            <a href="index.php?section=books&action=create">
                Add New Books
            </a>
        </p>

        <?php if($action==='create'): ?>
            <h2>Create Books</h2>

            <form method="POST">
            <p>
                <label>Book Title:</label>
                <br>
                <input  type="text"
                        name="book_title"
                        required
                />
            </p>
            <p>
                <label>Book Author:</label>
                <br>
                <input  type="text"
                        name="book_author"
                        required
                />
            </p>
            <p>
                <label>Book Category:</label>
                <br>
                <input  type="text"
                        name="book_category"
                        required
                />
            </p>

            <button type="submit">
                Save
            </button>

            <a href="index.php?section=books">
                Cancel
            </a>

            </form>
            
    <?php elseif($action==='update'): ?>
            <h2>Update Books Info</h2>

            <form method="POST">
            <p>
                <label>Book Title:</label>
                <br>
                <input  type="text"
                        name="book_title"
                        value="<?= htmlspecialchars($book['book_title']) ?>"
                        required
                />
            </p>
            <p>
                <label>Book Author:</label>
                <br>
                <input  type="text"
                        name="book_author"
                        value="<?= htmlspecialchars($book['book_author']) ?>"
                        required
                />
            </p>
            <p>
                <label>Book Category:</label>
                <br>
                <input  type="text"
                        name="book_category"
                        value="<?= htmlspecialchars($book['book_category']) ?>"
                        required
                />
            </p>

            <button type="submit">
                Save
            </button>

            <a href="index.php?section=books">
                Cancel
            </a>

            </form>

            <?php else: ?>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($books as $books): ?>

                    <tr>
                        <td>
                            <?= htmlspecialchars($books['book_id']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($books['book_title']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($books['book_author']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($books['book_category']) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars($books['book_created_at']) ?>
                        </td>

                        <td>
                            <a href="index.php?section=books&action=update&id=<?=$books['book_id']?> "
                            >Edit
                            </a>

                            <a href="#">Delete</a>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>

    <?php endif;?>

    <?php endif;?>

    <?php if($section === 'borrow'): ?>
        <h2>Borrow</h2>
        
        <p>
           <a href="index.php?section=borrow&action=create">
                <h2>Borrow a Book</h2>
           </a>
    </p>
         <?php if($action=='create'); ?>
            <h2>Borrow a Book</h2>

            <form method="POST">
    </form>
    <?php endif; ?>




</body>

<?php if(isset($_SESSION['alert'])): ?>

    <script>
        alert( <?= json_encode($_SESSION['alert']) ?> );
    </script>
   
     <?php unset($_SESSION['alert']); ?>

<?php endif;?>
</html>
