<?php
$token = "are_".md5(uniqid("are_")).uniqid(); 
echo $token;
echo "<br>";
echo strlen($token);