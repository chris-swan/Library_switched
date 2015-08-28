<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Author.php";
    require_once "src/Book.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class BookTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            Author::deleteAll();
            Book::deleteAll();
        }

        function test_getTitle()
        {
            //Arrange
            $title = "Sea Wolf";
            $test_book = new Book($title);

            //Act
            $result = $test_book->getTitle();

            //Assert
            $this->assertEquals($title, $result);
        }


        function test_getId()
        {
            //Arrange
            $title = "Sea Wolf";
            $id = 1;
            $test_book = new Book($title, $id);

            //Act
            $result = $test_book->getId();

            //Assert
            $this->assertEquals($id, $result);

        }

        function test_save()
        {
            //Arrange
            $title = "Sea Wolf";
            $test_book = new Book($title);
            $test_book->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals($test_book, $result[0]);
        }

        function test_delete()
        {
            //Arrange
            $title = "Sea Wolf";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Eye of the World";
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            $test_book2->delete();
            $result = Book::getall();

            //Assert
            $this->assertEquals([$test_book], $result);
        }

        function test_deleteAll()
        {
            //Arrange
            $title = "Sea Wolf";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Eye of the World";
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            Book::deleteAll();
            $result = Book::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_getAll()
        {
            //Arrange
            $title = "Sea Wolf";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Eye of the World";
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            $result = Book::getAll();

            //Assert
            $this->assertEquals($test_book, $result[0]);
        }

        function test_find()
        {
            //Arrange
            $title = "Sea Wolf";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Eye of the World";
            $test_book2 = new Book($title2);
            $test_book2->save();

            //Act
            $result = Book::find($test_book->getId());

            //Assert
            $this->assertEquals($test_book, $result);
        }

        function test_updateTitle()
        {
            //Arrange
            $title = "Sea Wolf";
            $test_book = new Book($title);
            $test_book->save();

            $title2 = "Eye of the World";
            $test_book->updateTitle($title2);

            //Act
            $id = $test_book->getId();
            $result = new Book($title2, $id);

            //Assert
            $this->assertEquals(Book::find($id), $result);
        }

        function test_AddAuthor()
        {
            //Arrange
            $author_name = "Jack London";
            $test_author = new Author($author_name);
            $test_author->save();

            $title = "Sea Wolf";
            $test_book = new Book($title);
            $test_book->save();

            //Act
            $result = [$test_author];
            $test_book->addAuthor($test_author);

            //Assert
            $this->assertEquals($test_book->getAuthors(), $result);
        }

        function test_addCopy()
        {
          $name = "Big Lebowski";
          $test_book = new Book($name);
          $test_book->save();

          $test_book->addCopy();
          $test_book->addCopy();
          $result = $test_book->getCopies();

          $this->assertEquals(2, count($result));
        }

    }

?>
