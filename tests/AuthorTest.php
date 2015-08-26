<?php
    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */
    require_once "src/Author.php";
    require_once "src/Book.php";

    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class AuthorTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Author::deleteAll();
            Book::deleteAll();
        }
        function test_getName()
        {
            //Arrange
            $name = "Stephen King";
            $id = null;
            $test_author = new Author($name, $id);
            //Act
            $result = $test_author->getName();
            //Assert
            $this->assertEquals($name, $result);
        }
        function test_setName()
        {
            //Arrange
            $name = "Stephen King";
            $id = null;
            $test_author = new Author($name, $id);
            //Act
            $test_author->setName("James Patterson");
            $result = $test_author->getName();
            //Assert
            $this->assertEquals("James Patterson", $result);
        }

        function test_getId()
        {
           //Arrange
           $name = "Biology";
           $id = 1;
           $test_author = new Author($name, $id);
           //Act
           $result = $test_author->getId();
           //Assert
           $this->assertEquals(1, $result);
        }
        function test_save()
        {
            //Arrange
            $name = "Stephen King";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();
            //Act
            $result = Author::getAll();
            //Assert
            $this->assertEquals($test_author, $result[0]);
        }
        function test_getAll()
        {
            //Arrange
            $name = "Stephen King";
            $id = 1;
            $name2 = "James Patterson";
            $id2 = 2;
            $test_author = new Author($name, $id);
            $test_author->save();
            $test_course2 = new Author($name2, $id2);
            $test_course2->save();
            //Act
            $result = Author::getAll();
            //Assert
            $this->assertEquals([$test_author, $test_course2], $result);
        }
        function test_deleteAll()
        {
            //Arrange
            $name = "James Patterson";
            $id = 1;
            $name2 = "Stephen King";
            $id2 = 2;
            $test_author = new Author($name, $id);
            $test_author->save();
            $test_course2 = new Author($name2, 2, $id2);
            $test_course2->save();
            //Act
            Author::deleteAll();
            $result = Author::getAll();
            //Assert
            $this->assertEquals([], $result);
        }
        function test_find()
        {
            //Arrange
            $name = "James Patterson";
            $id = 1;
            $test_author = new Author($name, $id);
            $test_author->save();
            $name2 = "Albebra";
            $id = 2;
            $test_author2 = new Author($name2, $id);
            $test_author2->save();
            //Act
            $result = Author::find($test_author->getId());
            //Assert
            $this->assertEquals($test_author, $result);
        }
        function test_getBooks()
        {
            //Arrange
            $title = "Wesley Pong";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $title2 = "Billy Bodega";
            $id2 = 1;
            $test_book2 = new Book($title2, $id2);
            $test_book2->save();

            $name = "James Patterson";
            $id3 = 2;
            $test_author = new Author($name, $id3);
            $test_author->save();
            //Act
            $test_author->addBook($test_book->getId());
            $test_author->addBook($test_book2->getId());
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
