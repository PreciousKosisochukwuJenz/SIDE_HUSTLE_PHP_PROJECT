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
                <h3>Login</h3>
            </div>
            <div class="card-body">
            <?php
                include 'db.php';
              if(isset($_POST['submit'])){
                $emailValidationMessage = "";
                $passwordValidationMessage = "";

                if(empty($_POST['email'])){
                  $emailValidationMessage = "<li> Email must not be empty </li>"; 
                }
                if(empty($_POST['password'])){
                  $passwordValidationMessage = "<li> Password must not be empty </li>"; 
                }

                if($emailValidationMessage != "" || $passwordValidationMessage != ""){
                  echo '<div class="alert alert-warning">';
                  echo '<ul>';
                  echo $emailValidationMessage . $passwordValidationMessage;
                  echo '</ul>';
                  echo '</div>';   
                }
                else{
                    // Post variables
                    $email = $_POST['email'];
                    $password = $_POST['password'];

                    $run_query = $db->query('SELECT * FROM users WHERE email = "'.$email.'" AND password = "'.$password.'"');

                    // Validate email and password
                    if($run_query){
                       $data =  $run_query->fetch(PDO::FETCH_OBJ);
                       if($data != null){
                        $_SESSION["IsLoggedIn"] = true;
                        $_SESSION["name"] = $data->name;
                        $_SESSION["email"] = $data->email;
                        // Redirect to login
                          header("Location: Dashboard.php");
                          exit();
                       }
                       else{
                        echo '<div class="alert alert-danger">';
                        echo '<strong>Error!</strong> Username and password doesn\'t exist';
                        echo '</div>';
                      }
                     }
                   
                }
              }
            ?>
              <form class="needs-validation" method="POST">
                <div class="form-group">
                    <label for="uname">Email:</label>
                    <input type="text" class="form-control" id="uname" placeholder="Enter email" name="email">
                    <div class="invalid-feedback">Email is required</div>
                  </div>
                  <div class="form-group">
                    <label for="pwd">Password:</label>
                    <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password">
                    <div class="invalid-feedback">Password is required</div>
                  </div>
                  <div class="form-group form-check">
                    <label class="form-check-label">
                      <a href="ForgetPassword.php"> Forget password? </a>
                    </label>
                  </div>
                  <button type="submit" name="submit" class="btn btn-primary"> Login </button>
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
