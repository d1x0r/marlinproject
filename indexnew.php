<?php
  session_start();
  SetCookie("acces","");
  require_once "datebase.php";
  require_once "main.php";
  $user = $connection->query("SELECT * FROM `comments` ORDER BY `datecreate` DESC,`timecreate` DESC LIMIT 4");
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
                                                      User
                                                  </a>
                                                  <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                      <a class="dropdown-item" href="profile.php">Профиль</a>
                                                      <a class="dropdown-item" href="logout.php">Выход</a>
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
                            <div class="card-header"><h3>Комментарии</h3></div>

                            <div class="card-body">
                            <?if(isset($_POST['submit123'])):?>
                              <?if(empty($errors)):?>
                              <div class="alert alert-success" role="alert">
                                Комментарий успешно добавлен
                              </div>
                              <?else:?>
                                <div class="alert alert-danger" role="alert">
                                  <? echo $errors[0]; ?>
                                </div>
                              <?endif;?>
                            <?endif;?>
                            <?if(isset($_COOKIE['acces'])):?>
                            <div class="alert alert-success" role="alert">
                              Комментарий успешно добавлен
                            </div>
                            <?endif;?>
                              <?while($result=$user->fetch_assoc()):?>
                                <div class="media">
                                  <img src="img/no-user.jpg" class="mr-3" alt="..." width="64" height="64">
                                  <div class="media-body">
                                    <h5 class="mt-0"><? echo $result['name']?></h5>
                                    <span><small><? echo $result['timecreate'].' '.$result['datecreate']?></small></span>
                                    <p>
                                        <? echo $result['main']?>
                                    </p>
                                  </div>
                                </div>
                              <? endwhile; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12" style="margin-top: 20px;">
                        <div class="card">
                            <div class="card-header"><h3>Оставить комментарий</h3></div>

                            <div class="card-body">
                                <form action="" method="POST">
                                  <?if(!isset($_SESSION['name'])):?>
                                    <div class="form-group">
                                      <label for="exampleFormControlTextarea1">Имя</label>
                                      <input name="name" class="form-control" id="exampleFormControlTextarea1" />
                                    </div>
                                  <?endif?>
                                    <div class="form-group">
                                      <label for="exampleFormControlTextarea1">Сообщение</label>
                                      <textarea name="text" class="form-control" id="exampleFormControlTextarea1" rows="3" value="<? echo $main;?>"></textarea>
                                    </div>
                                    <button name="submit123" type="submit" class="btn btn-success">Отправить</button>
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
