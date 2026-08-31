<?php
session_start();
session_destroy();
header("Location: ../login.php"); // arahkan ke folder utama
exit;
