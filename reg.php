<?php
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$pass = md5($password);
$passwordconfirm = $_POST['passwordconfirm'];
$as = $connection->query("SELECT * FROM `accounts` WHERE `login` = '$name'");
$ds = $as->fetch_assoc();
$em = $connection->query(" SELECT * FROM `accounts` WHERE `email` = '$email' ");
$mail = $em->fetch_assoc();
var_dump($mail);
if(isset($_POST['button']))
{
    if($name == "")
    {
      $errors[] = "Пожалуйста,введите логин !";
    }
    else
    {
      if($email == "")
      {
        $errors[] = "Пожалуйста,введите email !";
      }
      else
      {
        if(count($ds) >= 1)
        {
          $errors[] = "Извините,но введенный логин занят,введите другой !";
        }
        else
        {
          if(count($mail) >=1)
          {
            $errors[] = "Извините,но введенная почта занята,введите другую !";
          }
          else
          {
            if($password == "")
            {
              $errors[] = "Пожалуйста,введите пароль !";
            }
            else
            {
              if(strlen($name) <= 3)
              {
                $errors[] = "Пожалуйста,введите логин длиннее !";
              }
              else
              {
                if($password != $passwordconfirm)
                {
                  $errors[] = "Введенные пароли не совпадают";
                }
                else
                {
                  $connection->query("INSERT INTO `accounts`(`login`,`password`,`email`) VALUES ('$name','$pass','$email')");
                }
              }
            }
          }
        }
      }
    }
}
?>
