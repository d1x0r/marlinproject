<?php
require_once "datebase.php";
$url = "index.php";
$time = date("H:i:s");
$date = date("d/m/Y");
$login = $_SESSION['name'];
$name = trim($_POST['name']);
$createacc = "[Гость] ".$name;
$main = trim($_POST['text']);
$dust = $connection->query("SELECT `name` FROM `comments` WHERE `name`= '$name' ");
$mine = $dust->fetch_assoc();
if(isset($_POST['submit123']))
{
  if(!isset($_SESSION['name']))
  {
    if($name == "")
    {
      $errors[] = "Пожалуйста,введите имя или авторизируйтесь !";
    }
    else
    {
      if($main == "")
      {
        $errors[] = "Пожалуйста,текст сообщения !";
      }
      else
      {
        if($mine >= 1)
        {
          $errors[] = "Пожалуйста,введите другое имя ! Введенный логин занят";
        }
        else
        {
          setcookie("acces",'New',time() + 3600 * 24 * 365);
          header("Location:".$url);
          $connection->query("INSERT INTO `comments` (`main`,`name`,`datecreate`,`timecreate`,`image`) VALUES ('$main','$createacc','$date','$time','unknown.png')");
          unset($_POST);
        }
      }
    }
  }
  else
  {
    if($main == "")
    {
      $errors[] = "Пожалуйста,текст сообщения !";
    }
    else
    {
      setcookie("acces",'New',time() + 3600 * 24 * 365);
      header("Location:".$url);
      $connection->query("INSERT INTO `comments` (`main`,`name`,`datecreate`,`timecreate`) VALUES ('$main','$login','$date','$time')");
      unset($_POST);
    }
  }
}
?>
