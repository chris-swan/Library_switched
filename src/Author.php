<?php
    class Author
    {
        private $name;
        private $id;
        function __construct($name, $id = null)
        {
            $this->name = $name;
            $this->id = $id;
        }
        function setName($new_name)
        {
            $this->name = (string) $new_name;
        }
        function getName()
        {
            return $this->name;
        }

        function getId()
        {
            return $this->id;
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO authors (name)
            VALUES ('{$this->getName()}');");
            $this->id = $GLOBALS['DB']->lastInsertID();
        }

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
            $authors = array();
            foreach($returned_authors as $author) {
                $name = $author['name'];
                $id = $author['id'];
                $new_author = new Author($name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll()
        {
            $GLOBALS ['DB']->exec("DELETE FROM authors;");
        }

        static function find($search_id)
        {
            $found_author = null;
            $authors = Author::getAll();
            foreach($authors as $author) {
                $author_id = $author->getId();
                if ($author_id == $search_id) {
                    $found_author = $author;
                }
            }
            return $found_author;
        }

        function addBook($book_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id)
                VALUES ({$this->id}, {$book_id})");
        }

        function getBooks()
        {
            $returned_books = $GLOBALS['DB']->query("SELECT books.* FROM
                books JOIN authors_books ON (books.id = authors_books.book_id)
                JOIN authors ON (authors.id = authors_books.author_id)
                WHERE authors.id = {$this->getId()}");
            $books = array();
            foreach($returned_books as $book)
            {
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

        // function deleteBook($book_id)
        // {
        //     $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE
        //         book_id = {$book_id} AND author_id = {$this->id}");
        // }

        function deleteAllBooks()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors_books WHERE author_id = {$this->id}");
        }

        //Update name
        function updateName($new_name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET name = '{$new_name}'
                WHERE id = {$this->getId()}");
            $this->setName($new_name);
        }

    }
 ?>
