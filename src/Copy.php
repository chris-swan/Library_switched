<?php
    class Copy
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
            $GLOBALS['DB']->exec("INSERT INTO copies (title)
            VALUES ('{$this->getTitle()}');");
            $this->id = $GLOBALS['DB']->lastInsertID();
        }

        static function getAll()
        {
            $returned_copys = $GLOBALS['DB']->query("SELECT * FROM copies;");
            $copies = array();
            foreach($returned_copys as $copy) {
                $title = $copy['title'];
                $id = $copy['id'];
                $new_copy = new Copy($title, $id);
                array_push($copies, $new_copy);
            }
            return $copies;
        }

        static function deleteAll()
        {
            $GLOBALS ['DB']->exec("DELETE FROM copies;");
        }

        static function find($search_id)
        {
            $found_copy = null;
            $copies = Copy::getAll();
            foreach($copies as $copy) {
                $copy_id = $copy->getId();
                if ($copy_id == $search_id) {
                    $found_copy = $copy;
                }
            }
            return $found_copy;
        }

        function addPatron($patron_id)
        {
            $GLOBALS['DB']->exec("INSERT INTO copies_patrons (copy_id, patron_id)
                VALUES ({$this->id}), {$patron_id}");
        }

        function getPatrons()
        {
            $returned_patrons = $GLOBALS['DB']->query("SELECT patrons.* FROM
                patrons JOIN copies_patrons ON (patrons.id = copies_patrons.patron_id)
                JOIN copies ON (copies.id = copies_patrons.copy_id)
                WHERE copies.id = {$this->getId()}");
            $patrons = array();
            foreach($returned_patrons as $patron)
            {
                $name = $patron['name'];
                $id = $patron['id'];
                $new_patron = new Patron($name, $id);
                array_push($patrons, $new_patron);
            }
            return $patrons;
        }

        // function deleteAuthor($copy_id)
        // {
        //     $GLOBALS['DB']->exec("DELETE FROM patrons_copys WHERE
        //         copy_id = {$copy_id} AND patron_id = {$this->id}");
        // }

        function deleteAllPatron s()
        {
            $GLOBALS['DB']->exec("DELETE FROM patrons_copys WHERE copy_id = {$this->id}");
        }

        //Update title
        function updateTitle($new_title)
        {
            $GLOBALS['DB']->exec("UPDATE copies SET title = '{$new_title}'
                WHERE id = {$this->getId()}");
            $this->setTitle($new_title);
        }

    }
 ?>
