<?php
session_start();
$url228 = "http://myproject/index.php";
#Соединение
$connection = new mysqli("localhost","d1x0r","d1x0rlminecraft","practice");
$username = $_SESSION['name'];
$passwords = $_SESSION['password'];
$emails = $_SESSION['email'];
#Создается запрос с пользователем,у кого id=8
$im = $connection->query( "SELECT * FROM `accounts` WHERE `id` = '8'");
#Массив
$image = $im->fetch_assoc();
if($image['id'] == 8)
{
  #Создается массив если есть такой пользователь
  $administrator = $connection->query( "SELECT * FROM `accounts` WHERE `id` = '8'");
  $admin = $administrator->fetch_assoc();
}
else
{
  #Если нет,то очистить массив admin
  unset($admin);
}
if(isset($_GET['logout']))
{
  #Если нажата logout,то удалить сессию и выйти на главную страницу
  header("Location:".$url228);
  session_destroy();
}
?>
