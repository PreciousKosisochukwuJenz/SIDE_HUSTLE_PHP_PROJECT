<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Items || MARKET PLACE</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  </head>
  <body>

  <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <a class="navbar-brand" href="#">SIDE HUSTLE MARTET PLACE</a>
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

        <?php
        include 'db.php';
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $descriptionValidationMessage = "";
            $titleValidationMessage = "";
            $priceValidationMessage = "";
            
            if(!empty($_POST)){
              //Validate empty form
              if(empty($_POST["description"])){
                $descriptionValidationMessage = "<li> Description field cannot be empty</li>"; 
              } 
              if(empty($_POST["title"])){
                $titleValidationMessage = "<li> Title field cannot be empty</li>"; 
              } 
              if(empty($_POST["price"])){
                $priceValidationMessage = "<li> Price field cannot be empty</li>"; 
              } 
            }
            if($descriptionValidationMessage != "" || $titleValidationMessage != "" || $priceValidationMessage != ""){
                echo '<div class="alert alert-warning"><strong>Warning!</strong>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <ul>
                            <li>'.$titleValidationMessage.'</li>
                            <li>'.$descriptionValidationMessage.'</li>
                            <li>'.$priceValidationMessage.'</li>
                        </ul>
                      </div>';   
              }
              else{
                    if($_POST["action"] == "Add"){
                        // Post variables
                        $title = $_POST["title"];
                        $description = $_POST["description"];
                        $price = $_POST["price"];
                        $authenticatedEmail = (string)$_SESSION["email"];
                        $authenticatedName = (string)$_SESSION["name"];
                            $statement = 'INSERT INTO items (title, description, price,createdby,createdbyName) VALUES(:title, :description, :price, :createdby,:createdbyName)';
                            $sql = $db->prepare($statement);
                            $sql->execute([
                                'title' => $title,
                                'description' => $description,
                                'price' => $price,
                                'createdby' => $authenticatedEmail,
                                'createdbyName' => $authenticatedName
                            ]);
            
                        // Notification
                        echo '<div class="alert alert-success">';
                        echo ' <button type="button" class="close" data-dismiss="alert">&times;</button>';
                        echo '<strong>Success!</strong> Item added successfully.';
                        echo '</div>';  
                    }
                    if($_POST["action"] == "Edit"){
                            try{
                                    // Post variables
                                    $title = $_POST["title"];
                                    $description = $_POST["description"];
                                    $price = $_POST["price"];             
                                    $id = $_POST["Id"];

                                    $run_query = $db->query('UPDATE items SET title="'.$title.'",description="'.$description.'", price="'.$price.'" WHERE Id="'.$id.'"');
                                    
                                        // Notification
                                        echo '<div class="alert alert-success">';
                                        echo ' <button type="button" class="close" data-dismiss="alert">&times;</button>';
                                        echo '<strong>Success!</strong> Item updated successfully.';
                                        echo '</div>';       
                                }
                            catch(PDOException $e){
                                    // Notification
                                    echo '<div class="alert alert-danger">';
                                    echo ' <button type="button" class="close" data-dismiss="alert">&times;</button>';
                                    echo '<strong>Success!</strong> Item update failed.';
                                    echo '</div>';       
                            }
                    }
              }
          }
        ?>
        <div class="card">
                <div class="card-header">
                    <h3>Market items
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#CreateModal">
                        Add New
                        </button>
                    </h3>
                  </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th> Title </th>
                                <th> Description </th>
                                <th> Price </th>
                                <th> Owner </th>
                                <th> Action </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                //Execute a "SHOW DATABASES" SQL query.
                                $run_query = $db->query('SELECT * FROM items');
                                $authEmail = $_SESSION["email"];
                                if($run_query){
                                        while($row = $run_query->fetch(PDO::FETCH_OBJ))
                                        if($row->createdby == (string)$_SESSION["email"]){
                                          echo 
                                          '
                                              <tr>
                                                  <td><a href="#">'. $row->title .'</a></td>
                                                  <td>'. $row->description .'</td>
                                                  <td>₦'. $row->price .'</td>
                                                  <td>'. $row->createdbyName .'</td>
                                                  <td>
                                                      <button class="btn btn-info" onclick="UpdateForm('.$row->Id.')" data-toggle="modal" data-target="#EditModal">Edit</button>
                                                      <button class="btn btn-danger" onclick="Delete('.$row->Id.')">Delete</button>
                                                  </td>
                                              </tr>
                                          ';
                                        }else{
                                          echo 
                                          '
                                              <tr>
                                                  <td><a href="#">'. $row->title .'</a></td>
                                                  <td>'. $row->description .'</td>
                                                  <td>₦'. $row->price .'</td>
                                                  <td>'. $row->createdbyName .'</td>
                                                  <td>
                                                      <button class="btn btn-info" onclick="UpdateForm('.$row->Id.')" data-toggle="modal" data-target="#EditModal" disabled>Edit</button>
                                                      <button class="btn btn-danger" onclick="Delete('.$row->Id.')" disabled>Delete</button>
                                                  </td>
                                              </tr>
                                          ';
                                        }
                                       
                                    }
                                    else{
                                        echo 
                                        '
                                            <tr>
                                                <td colspan="5" class="text-center"> No item added by you yet</td>
                                            </tr>
                                        ';
                                    }
    
                            ?>
                        </tbody>
                    <table>
                </div>
            </div>

   <!-- Add Modal -->
   <div class="modal fade" id="CreateModal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Add New Item</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form class="needs-validation" method="POST">
                    <div class="modal-body">
                    <input type="hidden" name="action" value="Add" />
                            <div class="form-group">
                                <label for="title">Item:</label>
                                <input type="text" class="form-control" id="title" placeholder="Enter title" name="title">
                                <div class="invalid-feedback">Field is required</div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" placeholder="Enter description" name="description" row="0" col="5"></textarea>
                                <div class="invalid-feedback">Field is required</div>
                            </div>
                            <div class="form-group">
                                <label for="price">Price(₦):</label>
                                <input type="text" class="form-control" id="price" placeholder="Enter price" name="price">
                                <div class="invalid-feedback">Field is required</div>
                            </div>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-primary pull-right"> Add </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- Edit modal -->
        <div class="modal fade" id="EditModal">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Edit Item</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form class="needs-validation" method="POST">
                    <div class="modal-body">
                    <input type="hidden" name="Id" id="IdEdit" />
                    <input type="hidden" name="action" value="Edit" />
                    <div class="form-group">
                                <label for="title">Item:</label>
                                <input type="text" class="form-control" id="titleEdit" placeholder="Enter title" name="title">
                                <div class="invalid-feedback">Field is required</div>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="descriptionEdit" placeholder="Enter description" name="description" row="0" col="5"></textarea>
                                <div class="invalid-feedback">Field is required</div>
                            </div>
                            <div class="form-group">
                                <label for="price">Price(₦):</label>
                                <input type="text" class="form-control" id="priceEdit" placeholder="Enter price" name="price">
                                <div class="invalid-feedback">Field is required</div>
                            </div>
                    </div>
                    <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-primary pull-right"> Update changes </button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </form>
              </div>
            </div>
          </div>
  
</div>

<script>
        // Populating to modal
        function UpdateForm(id){
            $.ajax({
                url:"GetForEdit.php?id="+id,
                type:"get",
                success: function(response){
                    var parsedObj = JSON.parse(response);
                    $("#IdEdit").val(parsedObj.Id);
                    $("#titleEdit").val(parsedObj.title);
                    $("#descriptionEdit").val(parsedObj.description);
                    $("#priceEdit").val(parsedObj.price);
                },
                error: function(e){
                    alert(e.ResponseText);
                }
            })
        }

        // Delete
        function Delete(id){
            confirm("Are you sure?");
            $.ajax({
                url:"DeleteItem.php?id="+id,
                type:"get",
                success: function(response){
                   alert(response);
                   window.location.reload(true);
                },
                error: function(e){
                    alert(e.ResponseText);
                }
            })
        }

  </script>
  </body>
</html>
