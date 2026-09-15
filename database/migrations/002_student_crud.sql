-- student SQL#1 : select all students
SELECT * FROM students;

-- students SQL#2 : selct students in asc order;
SELECT * FROM students
   ORDER BY student_id ASC;

-- students SQL#3 : selct students in desc order;
SELECT * FROM students
   ORDER BY student_id DESC;

-- students SQL#4 : selct students in asc order by last_name;
SELECT * FROM students
   ORDER BY student_last_name ASC;

-- students SQL#5 : selct students in desc order by last_name;
SELECT * FROM students
   ORDER BY student_last_name DESC;

-- students SQL#6 : selct students in asc order by first_name;
SELECT * FROM students
   ORDER BY student_first_name ASC;

-- students SQL#7 : selct students in desc order by first_name;
SELECT * FROM students
   ORDER BY student_first_name DESC;

-- You can modify displayed columns by selecting
-- specific columns after SELECT command

SELECT student_first_name,
       student_last_name
FROM students
ORDER BY student_first_name ASC

-- student SQL#9 LIMIT 1 - you can change the limit to any number
SELECT student_first_name,
       student_last_name
FROM students
ORDER BY student_first_name ASC
LIMIT 1; 

-- student SQL#10 - Select a student based on id
SELECT student_first_name,
       student_last_name
FROM students
WHERE student_id = 1
LIMIT 1;


-- student SQL#10 - update student name based on id
UPDATE students
SET student_first_name='say2',
    student_last_name='margate'
WHERE student_id = 2;



