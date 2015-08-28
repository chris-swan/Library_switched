<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Author.php";
    //require_once "src/Book.php";

    $server = 'mysql:host=localhost;dbname=library_test';
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

        function test_getAuthorName()
        {
            //Arrange
            $author_name = "Jack London";
            $test_author = new Author($author_name);

            //Act
            $result = $test_author->getAuthorName();

            //Assert
            $this->assertEquals($author_name, $result);
        }

        function test_getId()
        {
            //Arrange
            $author_name = "Jack London";
            $id = 1;
            $test_author = new Author($author_name, $id);

            //Act
            $result = $test_author->getId();

            //Assert
            $this->assertEquals(true, is_numeric($result));

        }

        function test_save()
        {
            //Arrange
            $author_name = "Jack London";
            $test_author = new Author($author_name);
            $test_author->save();

            //Act
            $result = Author::getAll();

            //Assert
            $this->assertEquals($test_author, $result[0]);
        }

        function test_delete()
        {
            //Arrange
            $author_name = "Jack London";
            $test_author = new Author($author_name);
            $test_author->save();

            $author_name2 = "Robert Jordan";
            $test_author2 = new Author($author_name2);
            $test_author2->save();

            //Act
            $test_author2->delete();
            $result = Author::getAll();

            //Assert
            $this->assertEquals([$test_author], $result);
        }

        function test_getAll()
        {
            //Arrange
            $author_name = "Jack London";
            $test_author = new Author($author_name);
            $test_author->save();

            $author_name2 = "Robert Jordan";
            $test_author2 = new Author($author_name2);
            $test_author2->save();

            //Act
            $result = Author::getAll();

            //Assert
            $this->assertEquals($test_author, $result[0]);
        }

        function test_deleteAll()
        {
            //Arrange
            $author_name = "Jack London";
            $test_author = new Author($author_name);
            $test_author->save();

            $author_name2 = "Robert Jordan";
            $test_author2 = new Author($author_name2);
            $test_author2->save();

            //Act
            Author::deleteAll();
            $result = Author::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            $author_name = "Jack London";
            $test_author = new Author($author_name);
            $test_author->save();

            $author_name2 = "Robert Jordan";
            $test_author2 = new Author($author_name2);
            $test_author2->save();

            //Act
            $result = Author::find($test_author->getId());

            //Assert
            $this->assertEquals($test_author, $result);
        }

        function test_updateAuthorName()
        {
            $author_name = "Jack London";
            $test_author = new Author($author_name);
            $test_author->save();

            $author_name2 = "Robert Jordan";
            $test_author->updateAuthorName($author_name2);

            //Act
            $id = $test_author->getId();
            $result = new Author($author_name2, $id);

            //Assert
            $this->assertEquals(Author::find($id), $result);
        }

        function test_addBook()
        {
            //Arrange
            $author_name = "Jack London";
            $test_author = new Author($author_name);
            $test_author->save();

            $title = "Sea Wolf";
            $test_book = new Book($title);
            $test_book->save();


            //Act
            $result = [$test_book];
            $test_author->addBook($test_book);

            //Assert
            $this->assertEquals($test_author->getBooks(), $result);
        }

        function test_getBooks()
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


    }
?>
