<?php

class UsersSeeder
{
    public static function run($num)
    {
      // include faker
      require_once 'vendor/autoload.php';
      $faker = Faker\Factory::create();

      // connect to db
      $db = new PDO('mysql:host=localhost;dbname=test', 'root', '');
      
      // reset table
      // $sql = "TRUNCATE TABLE `users`";
      // $db->query($sql);

      // create users
      for ($i = 0; $i < $num; $i++) {
        $name = $faker->name;
        $email = $faker->email;
        
        // prepare query
        $sql = "INSERT INTO `users` (`name`, `email`) VALUES ('{$name}', '{$email}')";

        // execute query
        try{
          $result = $db->query($sql);
        }
        catch(PDOException $e) {
          print $e->getMessage();
        } 
      }

      // close connection
      $db = null;
    }
}

UsersSeeder::run(4000);