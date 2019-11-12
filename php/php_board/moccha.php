<!DOCTYPE html>
<html lang="ko" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
      $connect = mysqli_connect('localhost', 'root', 'hwkang1021', 'board') or die ("connect fail");
      $query = "select * from board order by number desc";
      $result = $connect -> query($query);
      $total = mysqli_
     ?>
  </body>
</html>
