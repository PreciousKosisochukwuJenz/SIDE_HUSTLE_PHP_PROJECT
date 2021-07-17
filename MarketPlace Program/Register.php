<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Register || MARKET PLACE</title>
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
                <h3>Registration</h3>
            </div>
            <div class="card-body">
            <?php
                include 'db.php';
                  if($_SERVER["REQUEST_METHOD"] == "POST"){
                    $nameValidationMessage = "";
                    $emailValidationMessage = "";
                    $passwordValidationMessage = "";
                    $passwordSaltValidationMessage = "";
                    $matchingValidationMessage = "";
                    if(!empty($_POST)){
                      //Validate empty form
                      if(empty($_POST["name"])){
                        $nameValidationMessage = "<li> Name must not be empty </li>"; 
                      } 
                      if(empty($_POST["email"])){
                        $emailValidationMessage = "<li> Email must not be empty </li>"; 
                      }
                      if(empty($_POST["password"])){
                        $passwordValidationMessage = "<li> Password must not be empty </li>"; 
                      }
                      if(empty($_POST["passwordSalt"])){
                        $passwordSaltValidationMessage = "<li> Password Salt must not be empty </li>"; 
                      }
                      if($_POST["password"] !== $_POST["passwordSalt"]){
                        $matchingValidationMessage = "<li> 'Password Salt' must match 'Password' </li>"; 
                      }
                    }

                    if($nameValidationMessage != "" || $emailValidationMessage != "" || $passwordValidationMessage != "" || $passwordSaltValidationMessage != "" || $matchingValidationMessage != ""){
                      echo '<div class="alert alert-warning">';
                      echo '<ul>';
                      echo $nameValidationMessage . $emailValidationMessage . $passwordValidationMessage . $passwordSaltValidationMessage . $matchingValidationMessage;
                      echo '</ul>';
                      echo '</div>';   
                      }else{
                        // Post variables
                        $name = $_POST["name"];
                        $email = $_POST["email"];
                        $password = $_POST["password"];

                        $statement = 'INSERT INTO users (name, email, password) VALUES(:name, :email, :password)';
                        $sql = $db->prepare($statement);
                        $sql->execute([
                            'name' => $name,
                            'email' => $email,
                            'password' => $password
                        ]);

                        // Notification
                        echo '<div class="alert alert-success">';
                        echo '<strong>Success!</strong> Registration successfull, <a href="login.php">Log in now</a>';
                        echo '</div>';   
                      }
                   }
            ?>
              <form method="POST">
                <div class="form-group">
                    <label for="uname">Name:</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name">
                  </div>
                <div class="form-group">
                    <label for="uname">Email:</label>
                    <input type="text" class="form-control" id="email" placeholder="Enter Email" name="email">
                  </div>
                  <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password">
                  </div>
                  <div class="form-group">
                    <label for="pwd">Confirm Password:</label>
                    <input type="password" class="form-control" id="passwordSalt" placeholder="Re-enter Password" name="passwordSalt" >
                  </div>
                  <button type="submit" name="submit" class="btn btn-primary"> Submit </button>
              </form>
            </div>
        </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </body>
</html>

