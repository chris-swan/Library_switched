<?php
    class Checkout {

        private $due_date;
        private $patron_id;
        private $copy_id;
        private $id;

        //constructor
        function __construct($due_date, $patron_id = NULL, $copy_id = NULL, $id = NULL)
        {
            $this->due_date = $due_date;
            $this->patron_id = $patron_id;
            $this->copy_id = $copy_id;
            $this->id = $id;
        }

        //Setter
        function setDueDate($new_due_date)
        {
            $this->due_date = $new_due_date;
        }

        //Getters
        function getDueDate()
        {
            return $this->due_date;
        }

        function getPatronId()
        {
            return $this->patron_id;
        }

        function getCopyId()
        {
            return $this->copy_id;
        }

        function getId()
        {
            return $this->id;
        }
        //Delete a single Due Date
        function delete()
        {
            $GLOBALS['DB']->exec("DELETE FROM checkouts WHERE id = {$this->getID()};");
        }

        function save()
        {
            $GLOBALS['DB']->exec("INSERT INTO checkouts (due_date) VALUES ('{$this->getDueDate()}');");
            $this->id = $GLOBALS['DB']->lastInsertId();
        }

        static function getAll()
        {
            $returned_checkouts = $GLOBALS['DB']->query("SELECT * FROM checkouts;");
            $checkouts = array();
            foreach($returned_checkouts as $checkout) {
                $due_date = $checkout['due_date'];
                $id = $checkout['id'];
                $patron_id = $checkout['patron_id'];
                $copy_id = $checkout['copy_id'];
                $new_checkout = new Checkout($due_date, $patron_id, $copy_id, $id);
                array_push($checkouts, $new_checkout);
            }
            return $checkouts;
        }

        static function deleteAll()
        {
            $GLOBALS['DB']->exec("DELETE FROM checkouts;");
        }

        static function find($search_id)
        {
            $found_checkout = NULL;
            $checkouts = Checkout::getAll();
            foreach ($checkouts as $checkout) {
                $checkout_id = $checkout->getId();
                if ($checkout_id == $search_id) {
                    $found_checkout = $checkout;
                }
            }
            return $found_checkout;
        }

        //Update method
        function updateDueDate($new_due_date)
        {
            //finish me, also needs a test. Not a great README.md...sorry...
        }

    }

 ?>