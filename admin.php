<?php
require_once "datebase.php";
$username = $_SESSION['name'];
$passwords = $_SESSION['password'];
$emails = $_SESSION['email'];
$user = $connection->query("SELECT * FROM `comments` ORDER BY `datecreate` DESC,`timecreate` DESC");
$url = "http://myproject/admin.php";
$url2 = "http://myproject/admin.php";
if(isset($_GET['logout']))
{
  session_destroy();
}
if(isset($_GET['decline']))
{
  header("Location:".$url);
  $get = (int)$_GET['decline'];
  $connection->query( "UPDATE `comments`SET `status` = 'de-active' WHERE `id` = '$get'");
}
if(isset($_GET['accept']))
{
  header("Location:".$url);
  $acc = (int)$_GET['accept'];
  $connection->query( "UPDATE `comments`SET `status` = 'active' WHERE `id` = '$acc'");
}
if(isset($_GET['delete']))
{
  header("Location:".$url);
  $acc = (int)$_GET['delete'];
  $connection->query( "DELETE FROM `comments` WHERE `id` = '$acc'");
}
if(isset($_GET['deleteall']))
{
  header("Location:".$url);
  $acc = (int)$_GET['deleteall'];
  $connection->query( "TRUNCATE TABLE `comments`");
}
if(!isset($_SESSION['name']))
{
  header("Location: http://myproject/index.php");
}
 ?>
<!DOCTYPE html>

<?if(count($admin) != NULL):?>
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
                            <div class="card-header"><h3>Админ панель</h3></div>

                            <div class="card-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Аватар</th>
                                            <th>Имя</th>
                                            <th>Дата</th>
                                            <th>Комментарий</th>
                                            <th>Действия</th>
                                            <th><a href="http://myproject/admin.php?deleteall" onclick="return confirm('Вы точно хотите удалить все комментарии ?')" class="btn btn-danger">Удалить всё</a></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                      <?while($result = $user->fetch_assoc()):?>
                                        <tr>
                                            <td>
                                                <img src="img/<? echo $result['image'];?>" alt="" class="img-fluid" width="64" height="64">
                                            </td>
                                            <td><?echo $result['name'];?></td>
                                            <td><?echo $result['datecreate']." ".$result['timecreate'];?></td>
                                            <td><?echo $result['main'];?></td>
                                            <td>
                                                  <?if($result['status'] == "de-active"):?>
                                                    <a href="http://myproject/admin.php?accept=<?echo $result['id'];?>" class="btn btn-success">Разрешить</a>
                                                  <?else:?>
                                                    <a href="http://myproject/admin.php?decline=<?echo $result['id'];?>" class="btn btn-warning">Запретить</a>
                                                  <?endif;?>
                                                <a href="http://myproject/admin.php?delete=<?echo $result['id'];?>" onclick="return confirm('Вы точно хотите удалить комментарий ?')" class="btn btn-danger">Удалить</a>
                                            </td>
                                        </tr>
                                      <?endwhile;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?else:?>
  <?header("Location: http://myproject/index.php");?>
<?endif;?>
