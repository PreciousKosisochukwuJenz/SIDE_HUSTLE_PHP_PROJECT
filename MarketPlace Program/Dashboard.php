<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Login || MARKET PLACE</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  </head>
  <body>

  <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <a class="navbar-brand" href="#">SIDE HUSTLE MARKET PLACE</a>
    <ul class="navbar-nav">
    <?php
      // Starting session
      session_start();

        if($_SESSION["IsLoggedIn"] == true){
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="Dashboard.php">Dashboard</a>';
            echo '</li>';
            echo '<li class="nav-item">';
            echo '<a class="nav-link" href="Item.php">Items</a>';
            echo '</li>';
          echo '<li class="nav-item">';
          echo '<a class="nav-link" href="Logout.php">Logout</a>';
          echo '</li>';
        }
        else{
          echo '<li class="nav-item">';
          echo '<a class="nav-link" href="Login.php">Login</a>';
          echo '</li>';
          echo '<li class="nav-item">';
          echo '<a class="nav-link" href="Register.php">Register</a>';
          echo '</li>';
        }
      ?>
    </ul>
  </nav>
  <br>
  <div class="container">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
            <?php
                 echo '<h3> WELCOME ' .$_SESSION['name']. '</h3>'; 
            ?>
            </div>
            <div class="card-body">
            <?php
             include 'db.php';

            $authEmail = $_SESSION["email"];
              $run_query = $db->query('SELECT * FROM items WHERE createdby="'.$authEmail.'"');
              if($run_query){
                echo "<h5>Total items owned : ".$run_query->rowCount()." </h5><br><hr><h5>Owned by me</h5>";
                echo "<div class='row'>";
                while($row = $run_query->fetch(PDO::FETCH_OBJ)){
                  echo '
                  <div class="col-md-4">
                    <div class="card">
                      <div class="card-header">
                        <h3>'.$row->title.'</h3>
                        <p class="pull-right">price:₦'.$row->price.'</p>
                      </div>
                      <div class="card-body">
                        <p> '.$row->description.'
                      </div>
                    </div>
                    </div>
                  ';
                }
                echo "</div>";
              }
            ?>
            </div>
        </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>
