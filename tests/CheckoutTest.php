<?php

    /**
    * @backupGlobals disabled
    * @backupStaticAttributes disabled
    */

    require_once "src/Checkout.php";
    //require_once "src/Book.php";

    $server = 'mysql:host=localhost;dbname=library_test';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    class CheckoutTest extends PHPUnit_Framework_TestCase
    {
        protected function tearDown()
        {
            // Author::deleteAll();
            // Book::deleteAll();
            Checkout::deleteAll();
        }

        function test_getDueDate()
        {
            //Arrange
            $due_date = "0001-01-01";
            $test_checkout = new Checkout($due_date);

            //Act
            $result = $test_checkout->getDueDate();

            //Assert
            $this->assertEquals($due_date, $result);
        }

        function test_save()
        {
            //Arrange
            $due_date = "0001-01-01";
            $test_checkout = new Checkout($due_date);
            $test_checkout->save();

            //Act
            $result = Checkout::getAll();

            //Assert
            $this->assertEquals($test_checkout, $result[0]);
        }

        function test_getAll()
        {
            //Arrange
            $due_date = "0001-01-01";
            $test_checkout = new Checkout($due_date);
            $test_checkout->save();

            $due_date2 = "2020-01-01";
            $test_checkout2 = new Checkout($due_date2);
            $test_checkout2->save();

            //Act
            $result = Checkout::getAll();

            //Assert
            $this->assertEquals($test_checkout, $result[0]);
        }

        function test_deleteAll()
        {
            //Arrange
            $due_date = "0001-01-01";
            $test_checkout = new Checkout($due_date);
            $test_checkout->save();

            $due_date2 = "2020-01-01";
            $test_checkout2 = new Checkout($due_date2);
            $test_checkout2->save();

            //Act
            Checkout::deleteAll();
            $result = Checkout::getAll();

            //Assert
            $this->assertEquals([], $result);
        }

        function test_find()
        {
            //Arrange
            $due_date = "0001-01-01";
            $test_checkout = new Checkout($due_date);
            $test_checkout->save();

            $due_date2 = "2020-01-01";
            $test_checkout2 = new Checkout($due_date2);
            $test_checkout2->save();

            //Act
            $result = Checkout::find($test_checkout->getId());

            //Assert
            $this->assertEquals($test_checkout, $result);
        }

    }
?>