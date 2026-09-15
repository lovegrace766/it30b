-- books SQL#1 : select all books
SELECT * FROM books;

-- books SQL#2 : select books in asc order;
SELECT * FROM books
   ORDER BY book_id ASC;

-- books SQL#3 : select books in desc order;
SELECT * FROM books
   ORDER BY book_id DESC;

-- books SQL#4 : select books in asc order by book_author;
SELECT * FROM books
   ORDER BY book_author ASC;

-- books SQL#5 : select books in desc order by book_author;
SELECT * FROM books
   ORDER BY book_author DESC;

-- books SQL#6 : select books in asc order by book_title;
SELECT * FROM books
   ORDER BY book_title ASC;

-- books SQL#7 : select books in desc order by book_title;
SELECT * FROM books
   ORDER BY book_title DESC;

-- You can modify displayed columns by selecting
-- specific columns after SELECT command

SELECT book_title,
       book_author
FROM books
ORDER BY book_title ASC;

-- book SQL#9 LIMIT 1 - you can change the limit to any number
SELECT book_title,
       book_author
FROM books
ORDER BY book_title ASC
LIMIT 1; 

-- book SQL#10 - Select a books based on id
SELECT book_title,
       book_author
FROM books
WHERE book_id = 1
LIMIT 1;


-- books SQL#10 - update books name based on id
UPDATE books
SET book_title='To Kill a Mockingbird',
    book_author='Harper Lee'
WHERE book_id = 2;