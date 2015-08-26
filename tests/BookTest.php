<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Book.php";
    require_once "src/Author.php";

    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';

    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Book::deleteAll();
            Author::deleteAll();
        }

        function test_getTitle()
        {
            //Arrange
            $title = "Ben";
            $id = null;
            $test_book = new Book($title, $id);

            //Act
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals($title, $result);
        }

        function test_setTitle()
        {
            //Arrange
            $title = "Ben";
            $id = null;
            $test_book = new Book($title, $id);

            //Act
            $test_book->setTitle("Jeremy");
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals("Jeremy", $result);
        }

        function test_getId()
        {
           //Arrange
           $title = "Ben";
           $id = 1;
           $test_book = new Book($title, $id);

           //Act
           $result = $test_book->getId();

           //Assert
           $this->assertEquals(1, $result);
        }

        function test_save()
        {
            //Arrange
            $title = "Ben";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals($test_book, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $title = "Ben";
            $id = 1;
            $title2 = "Jeff";
            $id2 = 2;
            $test_book = new Book($title, $id);
            $test_book->save();
            $test_book2 = new Book($title2, 2, $id2);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals([$test_book, $test_book2], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $title = "Ben";
            $id = 1;
            $title2 = "Jeff";
            $id2 = 2;
            $test_book = new Book($title, $id);
            $test_book->save();
            $test_book2 = new Book($title2, 2, $id2);
            $test_book2->save();

            //Act
            Book::deleteAll();
            $result = Book::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $title = "Wesley Pong";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();
            $title2 = "Jon Airpair";
            $id = 2;
            $test_book2 = new Book($title2, 2, $id);
            $test_book2->save();

            //Act
            $result = Book::find($test_book->getId());

            //Assert
            $this->assertEquals($test_book, $result);
        }

        function test_getAuthors()
        {
            //Arrange
            $title = "Wesley Pong";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $name = "History";
            $id2 = 2;
            $test_author = new Author($name, $id2);
            $test_author->save();

            $name2 = "Algebra";
            $id3 = 3;
            $test_author2 = new Author($name2, $id3);
            $test_author2->save();

            //Act
            $test_book->addAuthor($test_author->getId());
            $test_book->addAuthor($test_author2->getId());
            $result = $test_book->getAuthors();

            //Assert
            $this->assertEquals([$test_author, $test_author2], $result);
        }

        // function test_deleteAuthor()
        // {
        //     //Arrange
        //     $title = "Wesley Pong";
        //     $id = 1;
        //     $test_book = new Book($title, $id);
        //     $test_book->save();
        //
        //     $name = "History";
        //     $id2 = 2;
        //     $test_author = new Author($name, $id2);
        //     $test_author->save();
        //
        //     $name2 = "Lit";
        //     $id3 = 3;
        //     $test_author2 = new Author($name2, $id3);
        //     $test_author2->save();
        //
        //     $test_book->addAuthor($test_author->getId());
        //     $test_book->addAuthor($test_author2->getId());
        //     //Act
        //
        //     $test_book->deleteAuthor($test_author->getId());
        //     $result = $test_book->getAuthors();
        //
        //     //Assert
        //     $this->assertEquals([$test_author2], $result);
        // }

        function test_deleteAllAuthors()
        {
            //Arrange
            $title = "Wesley Pong";
            $id = 1;
            $test_book = new Book($title, $id);
            $test_book->save();

            $name = "History";
            $id2 = 2;
            $test_author = new Author($name, $id2);
            $test_author->save();

            $name2 = "Lit";
            $id3 = 3;
            $test_author2 = new Author($name2, $id3);
            $test_author2->save();

            $test_book->addAuthor($test_author->getId());
            $test_book->addAuthor($test_author2->getId());

            //Act
            $test_book->deleteAllAuthors();
            $result = $test_book->getAuthors();

            //Assert
            $this->assertEquals([], $result);
        }

        function testUpdateTitle()
        {
            //Arrange
            $title = "bob";
            $test_book = new Book($title);
            $test_book->save();
            $new_title = "Mark Marvel";

            //Act
            $test_book->updateTitle($new_title);

            //Assert
            $this->assertEquals("Mark Marvel", $test_book->getTitle());
        }
    }
 ?>
