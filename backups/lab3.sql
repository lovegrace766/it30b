CREATE TABLE books(
    book_id INT PRIMARY KEY AUTO_INCREMENT PRIMARY KEY,
    book_title VARCHAR(100) NOT NULL,
    book_author VARCHAR(100) NOT NULL,
    book_category VARCHAR(50) NOT NULL,
    book_created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE borrow (
    borrow_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date TIMESTAMP INT NULL DEFAULT CURRENT_TIMESTAMP,
    borrow_return_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_student FOREIGN KEY (student_id) REFERENCES students(student_id),
    CONSTRAINT fk_book FOREIGN KEY (book_id) REFERENCES books(book_id)

);

SELECT br.borrow_id, s.student_id,
    CONCAT(s.student_first_name, '', s.student_last_name) AS student_name, 
    b.book_title, b.book_author, b.book_category,
    br.borrow_date
FROM borrow br
    JOIN students s ON br.student_id = s.student_id
    JOIN books b ON br.book_id = b.book_id
WHERE br.borrow_return_date IS NULL
ORDER BY br.borrow_date DESC; 


-- Alter the borrow_return_date column to allow NULL values and set the default to NULL
ALTER TABLE borrow
MODIFY borrow_return_date TIMESTAMP NULL DEFAULT NULL;

UPDATE borrow
SET borrow_return_date = NULL
WHERE borrow_return_date = '2026-08-25 18:33:13';


-- Return a book by updating the borrow_return_date to the current timestamp

UPDATE borrow
SET borrow_return_date = CURRENT_TIMESTAMP

-- Change value of borrow_id to the ID of the borrow record you want to uupdate
WHERE borrow_id = 1 AND borrow_return_date IS NULL;

SELECT br.borrow_id, s.student_id,
    CONCAT(s.student_first_name, '', s.student_last_name) AS student_name, 
    b.book_title, b.book_author, b.book_category,
    br.borrow_date,br.borrow_return_date
FROM borrow br
    JOIN students s ON br.student_id = s.student_id
    JOIN books b ON br.book_id = b.book_id
WHERE br.borrow_return_date IS NOT NULL
ORDER BY br.borrow_date DESC; 

