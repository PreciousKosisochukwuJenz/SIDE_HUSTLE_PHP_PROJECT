<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Index || To DO List</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  </head>
  <body>

<br><br>
  <div class="container">
    <div class="container-fluid">

    <?php
    include 'db.php';
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $descriptionValidationMessage = "";
        if(!empty($_POST)){
          //Validate empty form
          if(empty($_POST["description"])){
            $descriptionValidationMessage = "<li> Description field cannot be empty</li>"; 
          } 
        }
        if($descriptionValidationMessage != ""){
            echo '<div class="alert alert-warning"><strong>Warning!</strong>
                     <button type="button" class="close" data-dismiss="alert">&times;</button>
                     <ul>'
                         .$descriptionValidationMessage.'
                     </ul>
                  </div>';   
          }
          else{
                if($_POST["action"] == "Add"){
                    // Post variables
                    $description = $_POST["description"];
                
                    $statement = 'INSERT INTO list (item, isDone) VALUES(:item, :isDone)';
                    $sql = $db->prepare($statement);
\                    $sql->execute([
                        'item' => $description,
                        'isDone' => true
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
                                $description = $_POST["description"];
                                $id = $_POST["Id"];

                                $run_query = $db->query('UPDATE list SET item="'.$description.'"WHERE Id="'.$id.'"');
                                
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
                <h3>SIDE HUSTLE TODO LIST PROGRAM
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#CreateModal">
                    Add New
                    </button>
                </h3>
              </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="pChk"></th>
                            <th> Description </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            //Execute a "SHOW DATABASES" SQL query.
                            $run_query = $db->query('SELECT * FROM list');

                            if($run_query){
                                    while($row = $run_query->fetch(PDO::FETCH_OBJ))
                                    echo 
                                    '
                                        <tr>
                                            <td><input type="checkbox" class="chkbox" value="'. $row->Id .'" /></td>
                                            <td>'. $row->item .'</td>
                                            <td>
                                                <button class="btn btn-info" onclick="UpdateForm('.$row->Id.')" data-toggle="modal" data-target="#EditModal">Edit</button>
                                                <button class="btn btn-danger" onclick="Delete('.$row->Id.')">Delete</button>
                                            </td>
                                        </tr>
                                    ';
                                }
                                else{
                                    echo 
                                    '
                                        <tr>
                                            <td colspan="3" class="text-center"> No task found</td>
                                        </tr>
                                    ';
                                }
 
                        ?>
                    </tbody>
            </div>
        </div>
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
                    <div class="form-group">
                        <label for="uname">Description:</label>
                        <input type="text" class="form-control" id="description" placeholder="Type here..." name="description">
                        <input type="hidden" name="action" value="Add" />
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
                    <div class="form-group">
                        <label for="uname">Description:</label>
                        <input type="text" class="form-control" id="descriptionEdit" placeholder="Type here..." name="description">
                        <input type="hidden" name="Id" id="IdEdit" />
                        <input type="hidden" name="action" value="Edit" />
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

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
        // Populating to modal
        function UpdateForm(id){
            $.ajax({
                url:"GetForEdit.php?id="+id,
                type:"get",
                success: function(response){
                    var parsedObj = JSON.parse(response);
                    $("#descriptionEdit").val(parsedObj.item);
                    $("#IdEdit").val(parsedObj.Id);
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
                url:"Delete.php?id="+id,
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

        // Checkbox control toggle strikeout
        $("#pChk").click(function(){
            let chks = $(".chkbox");
            if(this.checked == true){
                $.each(chks,function(index,chk){
                    chk.checked = true;
                    $(this).closest('tr').find('td:eq(1)').css("text-decoration", "line-through")
                })
            }else{
                $.each(chks,function(index,chk){
                    chk.checked = false;
                    $(this).closest('tr').find('td:eq(1)').css("text-decoration", "none")
                })
            }
        });

        // Single checkbox toggle 
        $(".chkbox").click(function(){
            if(this.checked == true){
                $(this).closest('tr').find('td:eq(1)').css("text-decoration", "line-through");
            }
            else{
                $(this).closest('tr').find('td:eq(1)').css("text-decoration", "none")
            }            
        });
  </script>
  </body>
</html>
