<?php
session_start();
session_unset(); // Sterge toate variabilele din sesiune
session_destroy(); // Distruge sesiunea

header("Location: auth.php");
exit();
?>