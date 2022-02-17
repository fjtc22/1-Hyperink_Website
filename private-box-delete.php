<?php
  include 'private/functions.php';

  $stmt = $pdo->prepare('DELETE FROM contenuto WHERE id = ?');
  $stmt->execute([$_GET['id']]);

  header("Location: private-area.php");
?>
