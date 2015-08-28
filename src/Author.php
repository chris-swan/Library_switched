
<?php
    Class Author {

        private $id;
        private $author_name;

        //constructors
        function __construct($author_name, $id = null)
        {
            $this->author_name = $author_name;
            $this->id = $id;
        }

        //Setter
        function setAuthorName($new_author_name)
        {
            $this->author_name = (string) $new_author_name;
        }

        //Getters
        function getAuthorName()
        {
            return $this->author_name;
        }

        function getId()
        {
            return $this->id;
        }

        //Delete a single Author
        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors WHERE id = {$this->getID()};");
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO authors (author_name) VALUES ('{$this->getAuthorName()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        //Static Methods

        static function getAll()
        {
            $returned_authors = $GLOBALS['DB']->query("SELECT * FROM authors;");
            $authors = array();
            foreach($returned_authors as $author) {
                $author_name = $author['author_name'];
                $id = $author['id'];
                $new_author = new Author($author_name, $id);
                array_push($authors, $new_author);
            }
            return $authors;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM authors;");
        }

        static function find($search_id)
        {
            $found_author = NULL;
            $authors = Author::getAll();
            foreach ($authors as $author) {
                $author_id = $author->getId();
                if ($author_id == $search_id) {
                    $found_author = $author;
                }
            }
            return $found_author;
        }

        //Update methods

        function updateAuthorName($new_author_name)
        {
            $GLOBALS['DB']->exec("UPDATE authors SET author_name = '{$new_author_name}' WHERE id = {$this->getId()};");
            $this->author_name = $new_author_name;
        }

        //Add get and add book(s)
        function addBook($book)
        {
            $GLOBALS['DB']->exec("INSERT INTO authors_books (author_id, book_id) Values ({$this->getId()}, {$book->getID()});");
        }

        function getBooks()
        {
            $query = $GLOBALS['DB']->query("SELECT books.* FROM
            authors JOIN authors_books ON (authors.id = authors_books.author_id)
                    JOIN books ON (authors_books.book_id = books.id)
            WHERE authors.id = {$this->getID()};");
            $returned_books = $query->fetchAll(PDO::FETCH_ASSOC);

            $books = array();
            foreach($returned_books as $book){
                $title = $book['title'];
                $id = $book['id'];
                $new_book = new Book($title, $id);
                array_push($books, $new_book);
            }
            return $books;
        }

 ?>
