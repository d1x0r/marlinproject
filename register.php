<?php
require_once 'datebase.php';
require_once 'reg.php';
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
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Register</div>

                            <div class="card-body">
                                <form method="POST" action="">

                                  <div class="form-group row">
                                      <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                                      <div class="col-md-6">
                                      <?if(isset($_POST['button'])):?>
                                        <!-- Если поле логина пустое -->
                                        <?if($name == ""):?>
                                          <input value="<?echo $name;?>" name="name" id="name" type="name" class="form-control  @error('name') is-invalid @enderror" name="name" autofocus>
                                            <span class="invalid-feedback" role="alert">
                                                <strong>Пожалуйста,введите логин !</strong>
                                            </span>
                                        <?else:?>
                                          <!-- Если логин меньше 3 символов -->
                                          <?if(strlen($name) <= 3):?>
                                            <input value="<?echo $name;?>" name="name" id="name" type="name" class="form-control  @error('name') is-invalid @enderror" name="name" autofocus>
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>Пожалуйста,введите логин длиннее !</strong>
                                              </span>
                                          <!-- Иначе вывести форму-->
                                          <?else:?>
                                            <?if(count($ds) >= 1):?>
                                              <input value="<?echo $name;?>" name="name" id="name" type="name" class="form-control  @error('name') is-invalid @enderror" name="name" autofocus>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Извините,но введенный логин занят,введите другой !</strong>
                                                </span>
                                            <?else:?>
                                              <input value="<?php
                                              if(empty($errors))
                                              {
                                              }
                                              else
                                              {
                                                echo $name;
                                              }
                                              ?>" name="name" id="name" type="name" class="form-control" name="name" >
                                            <?endif;?>
                                          <? endif;?>

                                        <?endif;?>

                                      <?else:?>

                                        <input name="name" id="name" type="name" class="form-control" name="name" >

                                      <?endif;?>
                                      </div>
                                  </div>

                                    <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail Address</label>

                                        <div class="col-md-6">
                                        <?if(isset($_POST['button'])):?>
                                            <?if($email == ""):?>
                                              <input value="<? echo $email;?>" name="email" id="email" type="email" class="form-control  @error('name') is-invalid @enderror" name="email" autofocus>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Пожалуйста,введите email !</strong>
                                                </span>
                                            <?else:?>

                                              <?if(count($mail) >=1):?>
                                                <input value="" name="email" id="email" type="email" class="form-control  @error('name') is-invalid @enderror" name="email" autofocus>
                                                  <span class="invalid-feedback" role="alert">
                                                      <strong>Извините,но введенная почта занята,введите другую !</strong>
                                                  </span>

                                              <?else:?>
                                              <input value="<?php
                                              if(empty($errors))
                                              {

                                              }
                                              else
                                              {
                                                echo $email;
                                              }
                                              ?>" name="email" id="email" type="email" class="form-control" name="email" >

                                              <?endif;?>
                                              
                                            <?endif;?>
                                        <?else:?>
                                          <input name="email" id="email" type="email" class="form-control" name="email" >
                                        <?endif;?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                        <div class="col-md-6">
                                          <?if(isset($_POST['button'])):?>
                                            <!-- Если поле логина пустое -->
                                            <?if($password == ""):?>
                                              <input name="password" id="password" type="password" class="form-control  @error('name') is-invalid @enderror" name="password" autofocus>
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>Пожалуйста,введите пароль !</strong>
                                                </span>
                                            <?else:?>
                                              <?if($password != $passwordconfirm):?>
                                                  <input name="passwordconfirm" id="password-confirm" type="password" class="form-control  @error('name') is-invalid @enderror" autofocus>
                                                  <span class="invalid-feedback" role="alert">
                                                      <strong>Введенные пароли не совпадают !</strong>
                                                  </span>
                                              <?else:?>
                                                <input name="passwordconfirm" id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                                              <?endif;?>
                                            <?endif;?>
                                          <?else:?>
                                            <input name="password" id="password" type="password" class="form-control " name="password"  autocomplete="new-password">
                                          <?endif?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

                                        <div class="col-md-6">
                                        <?if(isset($_POST['button'])):?>
                                          <!-- Если поле логина пустое -->
                                          <?if($password != $passwordconfirm):?>
                                              <input name="passwordconfirm" id="password-confirm" type="password" class="form-control  @error('name') is-invalid @enderror" autofocus>
                                              <span class="invalid-feedback" role="alert">
                                                  <strong>Введенные пароли не совпадают !</strong>
                                              </span>
                                          <?else:?>
                                            <input name="passwordconfirm" id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                                          <?endif;?>
                                        <?else:?>
                                          <input name="passwordconfirm" id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                                        <?endif;?>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button name="button" type="submit" class="btn btn-primary">
                                                Register
                                            </button>
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
