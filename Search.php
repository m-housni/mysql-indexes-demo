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

$res = Search::name('Laura Kling');

echo $res['result'].' / '.$res['time'];