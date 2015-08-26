<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Author.php";
    require_once "src/Book.php";
    require_once "src/Patron.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class PatronTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Author::deleteAll();
            Book::deleteAll();
            Patron::deleteAll();
        }
        function test_getPatron()
        {
            //Arrange
            $patron = "Stephen King";
            $id = null;
            $test_patron = new Patron($patron, $id);
            //Act
            $result = $test_patron->getpatron();
            //Assert
            $this->assertEquals($patron, $result);
        }
        function test_setPatron()
        {
            //Arrange
            $patron = "Stephen King";
            $id = null;
            $test_patron = new Patron($patron, $id);
            //Act
            $test_patron->setPatron("James Patterson");
            $result = $test_patron->getPatron();
            //Assert
            $this->assertEquals("James Patterson", $result);
        }

        function test_getId()
        {
           //Arrange
           $patron = "Biology";
           $id = 1;
           $test_patron = new Patron($patron, $id);
           //Act
           $result = $test_patron->getId();
           //Assert
           $this->assertEquals(1, $result);
        }
        function test_save()
        {
            //Arrange
            $patron = "Stephen King";
            $id = 1;
            $test_patron = new Patron($patron, $id);
            $test_patron->save();
            //Act
            $result = Patron::getAll();
            //Assert
            $this->assertEquals($test_patron, $result[0]);
        }
        function test_getAll()
        {
            //Arrange
            $patron = "Stephen King";
            $id = 1;
            $patron2 = "James Patterson";
            $id2 = 2;
            $test_patron = new Patron($patron, $id);
            $test_patron->save();
            $test_course2 = new Patron($patron2, $id2);
            $test_course2->save();
            //Act
            $result = Patron::getAll();
            //Assert
            $this->assertEquals([$test_patron, $test_course2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $patron = "James Patterson";
            $id = 1;
            $patron2 = "Stephen King";
            $id2 = 2;
            $test_patron = new Patron($patron, $id);
            $test_patron->save();
            $test_course2 = new Patron($patron2, 2, $id2);
            $test_course2->save();
            //Act
            Patron::deleteAll();
            $result = Patron::getAll();
            //Assert
            $this->assertEquals([], $result);
        }
        function test_find()
        {
            //Arrange
            $patron = "James Patterson";
            $id = 1;
            $test_patron = new Patron($patron, $id);
            $test_patron->save();
            $patron2 = "Albebra";
            $id = 2;
            $test_patron2 = new Patron($patron2, $id);
            $test_patron2->save();
            //Act
            $result = Patron::find($test_patron->getId());
            //Assert
            $this->assertEquals($test_patron, $result);
        }
        function test_getCheckouts()
        {
            //Arrange
            $patron = "Wesley Pong";
            $id = 1;
            $test_patron = new Patron($patron, $id);
            $test_book->save();

            $title = "Billy Bodega";
            $id2 = 1;
            $test_book2 = new Book($title, $id2);
            $test_book2->save();

            $title = "James Patterson";
            $id3 = 2;
            $test_author = new Book($name, $id3);
            $test_author->save();

            $test_copy = $GLOBALS['DB']->query("SELECT id FROM
                copies JOIN books ON (copies.book_id = books.id)
                WHERE books.id = copies.book_id");

            //Act
            $test_patron->addCheckout($test_book->getId());
            $test_patron->addCheckout($test_book2->getId());
            //Assert
            $this->assertEquals($test_author->getBooks(), [$test_book, $test_book2]);
        }

        // function test_deleteBook()
        // {
        //     //Arrange
        //     $title = "Wesley Pong";
        //     $id = 1;
        //     $test_student = new Book($name ,$id);
        //     $test_student->save();
        //     $name2 = "Billy Bodega";
        //     $id2 = 1;
        //     $test_student2 = new Book($name2, $enrollment_date2, $id2);
        //     $test_student2->save();
        //     $name = "James Patterson";
        //     $id2 = 2;
        //      = 'HIST:101';
        //     $test_author = new Author($name, $id2);
        //     $test_author->save();
        //     $test_author->addStudent($test_student->getId());
        //     $test_author->addStudent($test_student2->getId());
        //     //Act
        //     $test_author->deleteStudent($test_student->getId());
        //     $result = $test_author->getStudents();
        //     //Assert
        //     $this->assertEquals([$test_student2], $result);
        // }

        function test_deleteAllBooks()
        {
            //Arrange
            $name = "Wesley Pong";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();

            $title = "Billy Bodega";
            $id2 = 1;
            $test_book = new Book($title, $id2);
            $test_book->save();

            $title = "James Patterson";
            $id3 = 2;
            $test_book2 = new Book($title, $id3);
            $test_book2->save();
            $test_author->addBook($test_book->getId());
            $test_author->addBook($test_book2->getId());
            //Act
            $test_author->deleteAllBooks();
            $result = $test_author->getBooks();
            //Assert
            $this->assertEquals([], $result);
        }

        function testUpdateName()
        {
            //Arrange
            $name = "Stephen King";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();
            $new_name = "James Patterson";
            //Act
            $test_author->updateName($new_name);
            //Assert
            $this->assertEquals("James Patterson", $test_author->getName());
        }

        // function testUpdateBook()
        // {
        //     //Arrange
        //     $name = "Psychology 101";
        //      = "PSY101";
        //     $test_author = new Author($name, );
        //     $test_author->save();
        //     $new_course_number = "PSY101A";
        //     //Act
        //     $test_author->updateCourseNumber($new_course_number);
        //     //Assert
        //     $this->assertEquals("PSY101A", $test_author->getCourseNumber());
        // }
    }
 ?>
