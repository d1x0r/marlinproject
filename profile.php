<?php
session_start();
#Удаляется куки сообщающее о том,что изменение основного было удачно
SetCookie("acces1",'');
#Удаляется куки сообщающее о том,что изменение безопасности было удачно
SetCookie("acces2","");
require_once "datebase.php";
$url = "http://myproject/profile.php";
#Создается переменная с именем добавленной аватарки
$file_name = $_FILES['image']['name'];
#Создается переменная с временным хранением аватарки
$tmp_name = $_FILES['image']['tmp_name'];
#Создается переменная с введенным логином
$name = trim($_POST['name']);
#Создается переменная с введенной почтой
$email = trim($_POST['email']);
#Создаются переменные с получением значений из $_SESSION
$EmailUser = $_SESSION['email'];
$PasswordUser = $_SESSION['password'];
$LoginUser = $_SESSION['name'];
#Создается запрос на аккаунт
$QueryUser = $connection->query("SELECT * FROM `accounts` WHERE `login` = '$LoginUser' AND `password` = '$PasswordUser' AND `email` = '$EmailUser'");
#Массив
$QueryUserArray = $QueryUser->fetch_assoc();
#Переменные со значением из массива запроса
$Image = $QueryUserArray['image'];
$Login = $QueryUserArray['login'];
$mai = $QueryUserArray['email'];
$ActualPassword = $QueryUserArray['password'];
#Введенный старый пароль в MD5 формате
$CurrentPassword = md5($_POST['current']);
#Переменные с введенными новыми паролями
$CurrentPasswordrdNew = $_POST['password'];
$PasswordNewConfirmation = $_POST['password_confirmation'];
$PasswordNewMD5 = md5($CurrentPasswordrdNew);
#Если нажата кнопка изменить
if(!isset($_SESSION['name']))
{
  header("Location: http://myproject/index.php");
}
if(isset($_POST['edit']))
{
  #Если размер файла не равен 0 байт
  if($_FILES['image']['size'] != 0)
  {
    #Проверка на размер
    if($_FILES['image']['size'] >= 1024*1024*1024*2)
    {
      $errors[] = "Изображение должно быть не больше 2Мб";
    }
    else
    {
      #Переадресация для того,чтобы сразу отобразилась аватарка
      header("Location:".$url);
      #Из старой директории переносится в новую
      move_uploaded_file($tmp_name,"img/".$file_name);
      #В бд добавляется название аватарки
      $connection->query("UPDATE `accounts` SET `image` = '$file_name' WHERE `login` = '$LoginUser' AND `password` = '$PasswordUser' AND `email` = '$EmailUser'");
    }
  }
  #Запрос на то,чтобы выбрались те элементы,где поле login = новому паролю
  $leepsay = $connection->query("SELECT * FROM `accounts` WHERE `login` = '$name'");
  #Создается массив с полученными элементами
  $names = $leepsay->fetch_assoc();
  #Если поле логин было изменено
  if($name != $Login)
  {
    #Если поле логин пустое
    if($name == "")
    {
      $errors[] = "Пожалуйста,введите имя";
    }
    else
    {
      #Проверка на количество элементов в массиве,в котором находятся элементы с введенным именем
      if(count($names) >= 1)
      {
        $errors[] = "Извините,но данный логин уже занят";
      }
      else
      {
        #Переадресация на страницу,чтобы сразу отобразилось имя в профиле
        header("Location:".$url);
        #Если все удачно,то создается куки
        SetCookie("acces1","13",time() + 3600*24*365);
        #Логин меняется в бд accounts и comments
        $connection->query("UPDATE `accounts` SET `login` = '$name' WHERE `login` = '$LoginUser' AND `password` = '$PasswordUser' AND `email` = '$EmailUser'");
        $connection->query("UPDATE `comments` SET `name` = '$name' WHERE `name` = '$LoginUser'");
        $_SESSION['name'] = $name;
      }
    }
  }
  #Запрос с email
  $ad = $connection->query("SELECT * FROM `accounts` WHERE `email` = '$email'");
  $mails = $ad->fetch_assoc();
  if($email != $mai)
  {
    if($email == "")
    {
      $errors[] = "Пожалуйста,введите почту !";
    }
    else
    {
      if(count($mails) >= 1)
      {
        $errors[] = "Извините,но данная почта уже занята !";
      }
      else
      {
        #То же самое,что и с изменением пароля
        header("Location:".$url);
        SetCookie("acces1","13",time() + 3600*24*365);
        $connection->query("UPDATE `accounts` SET `email` = '$email' WHERE `login` = '$LoginUser' AND `password` = '$PasswordUser' AND `email` = '$EmailUser'");
        $_SESSION['email'] = $email;
      }
    }
  }
}
if(isset($_POST['editpass']))
{
  #Если старый введенный пароль пустой
  if($CurrentPassword != "")
  {
    #Если введенный пароль совпадает с нынешним
    if($CurrentPassword == $ActualPassword)
    {
      #Если новый пароль не равен пустоте
      if($CurrentPasswordrdNew != '')
      {
        if($PasswordNewConfirmation !='')
        {
          if($CurrentPassword != $CurrentPasswordrdNew)
          {
            if($CurrentPassword != $PasswordNewConfirmation)
            {
              if($CurrentPasswordrdNew == $PasswordNewConfirmation)
              {
                header("Location:".$url);
                SetCookie("acces2","123",time() + 3600*24*365);
                $connection->query("UPDATE `accounts` SET `password` = '$PasswordNewMD5' WHERE `login` = '$LoginUser' AND `password` = '$PasswordUser' AND `email` = '$EmailUser'");
                $_SESSION['password'] = $PasswordNewMD5;
              }
            }
          }
        }
      }
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Comments</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="css/app.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    Project
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        <?if(!isset($_SESSION['name'])):?>
                                <li class="nav-item">
                                    <a class="nav-link" href="login.php">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="register.php">Register</a>
                                </li>
                          <?else:?>
                          <li class="nav-item dropdown">
                                                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                          <?php echo $_SESSION['name']; ?>
                                                      </a>
                                                      <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                        <form method="POST">
                                                            <a name="profile" class="dropdown-item" href="profile.php">Профиль</a>
                                                            <!-- Если есть такой пользователь является админом-->
                                                            <?if(count($admin) >= 1 ):?>
                                                              <a name="profile" class="dropdown-item" href="admin.php">Админ-панель</a>
                                                            <?endif;?>
                                                            <a class="dropdown-item" onclick="<? ?>" href="index.php?logout">Выход</a>
                                                        </form>
                                                      </div>
                                                  </li>
                        <? endif;?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
          <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header"><h3>Профиль пользователя</h3></div>
                        <div class="card-body">
                          <!--Если есть куки,то сообщение-->
                          <?if(isset($_COOKIE['acces1'])):?>
                            <div class="alert alert-success" role="alert">
                              Профиль успешно обновлен
                            </div>
                          <?endif;?>
                          <?if(!empty($errors)): ?>
                              <div class="alert alert-danger" role="alert">
                                <?echo $errors[0];?>
                              </div>
                          <?endif;?>
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Login</label>
                                            <!-- Изменение логина -->
                                            <?if(isset($_POST['edit'])):?>
                                              <?if($name != $Login):?>
                                                <?if($name ==""):?>
                                                  <input type="text" class="form-control is-invalid" name="name" id="exampleFormControlInput1">
                                                <?else:?>
                                                  <?if(count($names) >= 1):?>
                                                    <input type="text" class="form-control is-invalid" name="name" id="exampleFormControlInput1">
                                                  <?else:?>
                                                    <input type="text" class="form-control" name="name" id="exampleFormControlInput1" value="<?php echo $_SESSION['name']; ?>">
                                                  <?endif;?>
                                                <?endif;?>
                                              <?else:?>
                                                <input type="text" class="form-control" name="name" id="exampleFormControlInput1" value="<?php echo $_SESSION['name']; ?>">
                                              <?endif;?>
                                            <?else:?>
                                              <input type="text" class="form-control" name="name" id="exampleFormControlInput1" value="<?php echo $_SESSION['name']; ?>">
                                            <?endif;?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Email</label>
                                            <!-- Изменение почты -->
                                            <?if(isset($_POST['edit'])):?>
                                              <?if($email != $mai):?>
                                                <?if($email == ""):?>
                                                  <input type="email" class="form-control is-invalid" name="email" id="exampleFormControlInput1">
                                                <?else:?>
                                                  <?if(count($mails) >= 1):?>
                                                    <input type="email" class="form-control is-invalid" name="email" id="exampleFormControlInput1">
                                                  <?else:?>
                                                    <input type="email" class="form-control" name="email" id="exampleFormControlInput1" value="<?php echo $EmailUser; ?>">
                                                  <?endif;?>
                                                <?endif;?>
                                              <?else:?>
                                                <input type="email" class="form-control" name="email" id="exampleFormControlInput1" value="<?php echo $EmailUser; ?>">
                                              <?endif;?>
                                            <?else:?>
                                              <input type="email" class="form-control" name="email" id="exampleFormControlInput1" value="<?php echo $EmailUser; ?>">
                                            <?endif;?>
                                            <span class="text text-danger">
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Аватар</label>
                                            <input type="file" class="form-control" name="image" id="exampleFormControlInput1">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                      <img src="img/<?php echo $Image; ?>" alt="" class="img-fluid">
                                    </div>

                                    <div class="col-md-12">
                                      <form method="post">
                                        <button name="edit" class="btn btn-success">Сохранить изменения</button>
                                      </form>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-12" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-header"><h3>Безопасность</h3></div>

                        <div class="card-body">
                          <?if(isset($_COOKIE['acces2'])):?>
                            <div class="alert alert-success" role="alert">
                                Пароль успешно обновлен
                            </div>
                          <?endif;?>
                          <!-- Изменение пароля -->
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Старый пароль</label>
                                        <?if(isset($_POST['editpass'])):?>
                                          <?if($CurrentPassword != ""):?>
                                            <?if($CurrentPassword == $ActualPassword):?>
                                              <input type="password" name="current" class="form-control" id="exampleFormControlInput1">
                                            <?else:?>
                                                <input type="password" name="current" class="form-control is-invalid" id="exampleFormControlInput1">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Пароль введен неверно</strong>
                                                </span>
                                            <?endif;?>
                                          <?else:?>
                                            <input type="password" name="current" class="form-control is-invalid" id="exampleFormControlInput1">
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Пожалуйста,введите пароль</strong>
                                            </span>
                                          <?endif;?>
                                        <?else:?>
                                          <input type="password" name="current" class="form-control" id="exampleFormControlInput1">
                                        <?endif;?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Новый пароль</label>

                                        <?if(isset($_POST['editpass'])):?>
                                            <?if($CurrentPasswordrdNew != ''):?>
                                              <?if($PasswordNewConfirmation !=''):?>
                                                <?if($CurrentPassword != $CurrentPasswordrdNew):?>
                                                  <?if($CurrentPassword != $PasswordNewConfirmation):?>
                                                    <?if($CurrentPasswordrdNew == $PasswordNewConfirmation):?>
                                                      <input type="password" name="password" class="form-control" id="exampleFormControlInput1">
                                                    <?else:?>
                                                      <input type="password" name="password" class="form-control is-invalid" id="exampleFormControlInput1">
                                                      <span class="invalid-feedback" role="alert">
                                                          <strong>Пароли не совпадают</strong>
                                                      </span>
                                                    <?endif;?>
                                                  <?else:?>
                                                    <input type="password" name="password" class="form-control is-invalid" id="exampleFormControlInput1">
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>Пожалуйста,введите пароль отличающийся от старого</strong>
                                                    </span>
                                                  <?endif;?>
                                                <?else:?>
                                                  <input type="password" name="password" class="form-control is-invalid" id="exampleFormControlInput1">
                                                  <span class="invalid-feedback" role="alert">
                                                      <strong>Пожалуйста,введите пароль отличающийся от старого</strong>
                                                  </span>
                                                <?endif;?>
                                              <?else:?>
                                                <input type="password" name="password" class="form-control is-invalid" id="exampleFormControlInput1">
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Пожалуйста,введите пароль</strong>
                                                </span>
                                              <?endif;?>
                                            <?else:?>
                                              <input type="password" name="password" class="form-control is-invalid" id="exampleFormControlInput1">
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>Пожалуйста,введите пароль</strong>
                                              </span>
                                            <?endif;?>
                                          <?else:?>
                                            <input type="password" name="password" class="form-control" id="exampleFormControlInput1">
                                          <?endif;?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleFormControlInput1">Подтверждение нового пароля</label>
                                            <?if(isset($_POST['editpass'])):?>
                                                <?if($CurrentPasswordrdNew != ''):?>
                                                  <?if($PasswordNewConfirmation !=''):?>
                                                    <?if($CurrentPassword != $CurrentPasswordrdNew):?>
                                                      <?if($CurrentPassword != $PasswordNewConfirmation):?>
                                                        <?if($CurrentPasswordrdNew == $PasswordNewConfirmation):?>
                                                          <input type="password" name="password_confirmation" class="form-control" id="exampleFormControlInput1">
                                                        <?else:?>
                                                          <input type="password" name="password_confirmation" class="form-control is-invalid" id="exampleFormControlInput1">
                                                          <span class="invalid-feedback" role="alert">
                                                              <strong>Пароли не совпадают</strong>
                                                          </span>
                                                        <?endif;?>
                                                      <?else:?>
                                                        <input type="password" name="password_confirmation" class="form-control is-invalid" id="exampleFormControlInput1">
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>Пожалуйста,введите пароль отличающийся от старого</strong>
                                                        </span>
                                                      <?endif;?>
                                                    <?else:?>
                                                      <input type="password" name="password_confirmation" class="form-control is-invalid" id="exampleFormControlInput1">
                                                      <span class="invalid-feedback" role="alert">
                                                          <strong>Пожалуйста,введите пароль отличающийся от старого</strong>
                                                      </span>
                                                    <?endif;?>
                                                  <?else:?>
                                                    <input type="password" name="password_confirmation" class="form-control is-invalid" id="exampleFormControlInput1">
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>Пожалуйста,введите пароль</strong>
                                                    </span>
                                                  <?endif;?>
                                                <?else:?>
                                                  <input type="password" name="password_confirmation" class="form-control is-invalid" id="exampleFormControlInput1">
                                                  <span class="invalid-feedback" role="alert">
                                                      <strong>Пожалуйста,введите пароль</strong>
                                                  </span>
                                                <?endif;?>
                                              <?else:?>
                                                <input type="password" name="password_confirmation" class="form-control" id="exampleFormControlInput1">
                                              <?endif;?>
                                          </div>
                                        <button name="editpass" class="btn btn-success">Изменить</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </main>
    </div>
</body>
</html>
