<?php
    class Book
    {
        private $title;
        private $id;

//construct function

        function __construct($title,  $id = NULL)
        {
            $this->title = $title;
            $this->id = $id;
        }

//set functions:

        function setTitle($new_title)
        {
            $this->title = $new_title;
        }

//Get functions:

        function getTitle()
        {
            return $this->title;
        }

        function getId()
        {
            return $this->id;
        }

        //Delete a single book:
        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM books WHERE id = {$this->getID()};");
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO books (title) VALUES ('{$this->getTitle()}');");
            $this->id = $GLOBALS ['DB']->lastInsertId();
        }

        function updateTitle($new_title)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}' WHERE id = {$this->getId()};");
            $this->title = $new_title;
        }
//static functions

        static function getAll()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();
            foreach ($returned_books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM books");
        }

        static function find($search_id)
        {
            $found_book = NULL;
            $books = Book::getAll();
            foreach ($books as $book) {
                $book_id = $book->getId();
                if ($book_id == $search_id) {
                    $found_book = $book;
                }
            }
            return $found_book;
        }


        //Add and get Title(s)
        function addAuthor($author)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books(author_id, book_id) VALUES ({$author->getId()}, {$this->getId()});");
        }

        //Working with join statement
        function getAuthors()
        {
            $query = $GLOBALS['DB']->query("SELECT authors.* FROM books
                JOIN authors_books ON (books.id = authors_books.book_id)
                JOIN authors ON (authors_books.author_id = authors.id)
                WHERE books.id = {$this->getId()};");

            $returned_authors = $query->fetchAll(PDO::FETCH_ASSOC);

            $authors = [];
            foreach($returned_authors as $author) {
                $author_name = $author['author_name'];
                $id = $author['id'];
                $new_author = new Author($author_name, $id);
                array_push($authors, $new_author);
            }
            return $authors;

        }

        function addCopy()
        {
          $GLOBALS['DB']->exec("INSERT INTO copies(book_id) VALUES ({$this->getId()});");
        }

        function getCopies()
        {
            $returned_copies = $GLOBALS['DB']->query("SELECT copies.* FROM books
                JOIN copies ON (copies.book_id = books.id)
                WHERE copies.book_id = {$this->getId()};");

            $copies = array();
            foreach($returned_copies as $copy) {
                $id = $copy['id'];
                array_push($copies, $id);
            }
            return $copies;
        }

    }
?>