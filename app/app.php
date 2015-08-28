<?php
    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Author.php";
    require_once __DIR__."/../src/Book.php";
    require_once __DIR__."/../src/Checkout.php";
    require_once __DIR__."/../src/Patron.php";

    $app = new Silex\Application();
    $app['debug'] = true;
    $server = 'mysql:host=localhost;dbname=library';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();
    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/books", function() use ($app) {
        return $app['twig']->render('books.html.twig', array('books'=>Book::getAll()));
    });

    $app->get("/authors", function() use ($app) {
        return $app['twig']->render('authors.html.twig', array('authors'=>Author::getAll()));
    });

    $app->get("/patrons", function() use ($app) {
        return $app['twig']->render('patrons.html.twig', array('patrons'=>Patron::getAll()));
    });

    $app->get("/book/{id}", function($id) use ($app) {
        $book = Book::find($id);
        $book_id = $book->getId();
        return $app['twig']->render('book.html.twig', array('copies'=>$book->getCopies(), 'book' => $book, 'authors' => $book->getAuthors(), 'all_authors' => Author::getAll()));
    });

    $app->get("/author/{id}", function($id) use ($app) {
        $author = Author::find($id);
        $author_id = $author->getId();
        return $app['twig']->render('author.html.twig', array('author' => $author, 'books' => $author->getBooks(), 'all_books' => Book::getAll()));
    });

    $app->get("/patron/{id}", function($id) use ($app) {
        $patron = Patron::find($id);
        $patron_id = $patron->getId();
        return $app['twig']->render('patron.html.twig', array('patron' => $patron, 'checkouts' => $patron->getCheckouts()));
    });

    //-------------------------------------------------------//
    $app->post("/books", function() use ($app) {
        $new_book = new Book($_POST['title']);
        $new_book->save();
        return $app['twig']->render('books.html.twig', array('books'=>Book::getAll()));
    });

    $app->post("/authors", function() use ($app) {
        $new_author = new Author($_POST['name']);
        $new_author->save();
        return $app['twig']->render('authors.html.twig', array('authors'=>Author::getAll()));
    });

    $app->post("/patrons", function() use ($app) {
        $new_patron = new Patron($_POST['name']);
        $new_patron->save();
        return $app['twig']->render('patrons.html.twig', array('patrons'=>Patron::getAll()));
    });

    $app->post("/add_author", function() use ($app) {
        $book = Book::find($_POST['book_id']);
        $author = Author::find($_POST['author_id']);
        $book->addAuthor($author);
        return $app['twig']->render('book.html.twig', array('copies'=>$book->getCopies(), 'book' => $book, 'authors' => $book->getAuthors(), 'all_authors' => Author::getAll()));
    });

    $app->post("/add_book", function() use ($app) {
        $book = Book::find($_POST['book_id']);
        $author = Author::find($_POST['author_id']);
        $author->addBook($book);
        return $app['twig']->render('author.html.twig', array('author' => $author, 'books' => $author->getBooks(), 'all_books' => Book::getAll()));
    });

    $app->post("/add_copy", function() use ($app) {
        $book = Book::find($_POST['book_id']);
        $book->addCopy();
        return $app['twig']->render('book.html.twig', array('copies'=>$book->getCopies(), 'book' => $book, 'authors' => $book->getAuthors(), 'all_authors' => Author::getAll()));
    });

    return $app;
    
    ?>