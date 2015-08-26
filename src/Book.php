<?php
    class Book
    {
        private $title;
        private $id;
        function __construct($title, $id = null)
        {
            $this->title = $title;
            $this->id = $id;
        }
        function setTitle($new_title)
        {
            $this->title =  $new_title;
        }
        function getTitle()
        {
            return $this->title;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO books (title)
            VALUES ('{$this->getTitle()}');");
            $this->id = $GLOBALS['DB']->lastInsertID();
        }

        static function getAll()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT * FROM books;");
            $books = array();
            foreach($returned_books as $book) {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        static function deleteAll()
        {
            $GLOBALS ['DB']->exec("DELETE FROM books;");
        }

        static function find($search_id)
        {
            $found_book = null;
            $books = Book::getAll();
            foreach($books as $book) {
                $book_id = $book->getId();
                if ($book_id == $search_id) {
                    $found_book = $book;
                }
            }
            return $found_book;
        }

        function addAuthor($author_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id)
                VALUES ({$author_id}, {$this->id})");
        }

        function getAuthors()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT authors.* FROM
                authors JOIN authors_books ON (authors.id = authors_books.author_id)
                JOIN books ON (books.id = authors_books.book_id)
                WHERE books.id = {$this->getId()}");
            $authors = array();
            foreach($returned_authors as $author)
            {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        // function deleteAuthor($book_id)
        // {
        //     $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE
        //         book_id = {$book_id} AND author_id = {$this->id}");
        // }

        function deleteAllAuthors()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE book_id = {$this->id}");
        }

        //Update title
        function updateTitle($new_title)
        {
            $GLOBALS['DB']->exec("UPDATE books SET title = '{$new_title}'
                WHERE id = {$this->getId()}");
            $this->setTitle($new_title);
        }

    }
 ?>
