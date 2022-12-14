# Demo mysql database search with/out index

## 1. Create database

```sql
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `test`;
```
## 2. Create table

```sql
CREATE TABLE IF NOT EXISTS `test`.`test` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
);
```

## 3. Insert data 10M rows (UsersSeeder.php)

``` php
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
      $sql = "TRUNCATE TABLE `users`";
      $db->query($sql);

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

// UsersSeeder::run(10000000);
```

## 4. Search

```php
<?php

class Search {
  public static function name($name) {

    // milliseconds since 1/1/1970
    $start = round(microtime(true) * 1000);

    $db = new PDO('mysql:host=localhost;dbname=test', 'root', '');
    $sql = "SELECT * FROM `users` WHERE `name` = '{$name}'";
    $result = count($db->query($sql)->fetchAll(PDO::FETCH_ASSOC));
    $db = null;
    return array(
      'result' => $result,
      'time' => round(microtime(true) * 1000) - $start
    );
  }
}

$res = Search::name('Darion');

echo $res['result'].' / '.$res['time'];
```

## 5. Search without index

```
Search::name('Laura Kling'); 
// Output: 8 results / 2385 ms
```

## 6. Create index
  
```sql
CREATE INDEX name_idx ON users(`name`);
```

## 7. Search with index
```
Search::name('Laura Kling'); 
// Output: 8 results / 8 ms
```

## 8. Conclusion
By creating an index on the `name` column, the search time is reduced from **2385 ms** to **8 ms**. This is a 99.7% improvement. This is a huge improvement for a simple search. Imagine the improvement for a complex search. 



