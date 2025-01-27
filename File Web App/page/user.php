<?php

if($_SESSION["role"] != "Admin"){
  echo "<script> location.href='index.php' </script>";
}

$page = $_GET["page"];
$insert = false;

if(isset($_POST["editData"])) { //menampilkan form edit data
  $old_id = $_POST["editData"];

  $userName = $_POST["username"];
  $fullName = $_POST["fullname"];
  $role = $_POST["role"];
  $active = $_POST["active"];

  if($_POST["password"] == ""){
    $sql_edit = "UPDATE user SET username='$userName', fullname='$fullName', role='$role', active='$active' WHERE username= '$old_id'";
  } else {
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $sql_edit = "UPDATE user SET username='$userName', password='$password', fullname='$fullName', role='$role', active='$active' WHERE username= '$old_id'";
  }

  mysqli_query($conn,$sql_edit);

} else if(isset($_POST["username"])){ //menampilkan form tambah data
  $userName = $_POST["username"];
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
  $fullName = $_POST["fullname"];
  $role = $_POST["role"];

  $sql_insert = "INSERT INTO user (username, password, fullname, role) VALUES ('$userName', '$password', '$fullName', '$role')";
  mysqli_query($conn, $sql_insert);
  $insert = true;
}

if(isset($_GET["edit"])){
  $id = $_GET["edit"];
  $sql_select_data = "SELECT * FROM user WHERE username = '$id' LIMIT 1";

  $result = mysqli_query($conn, $sql_select_data);
  $data = mysqli_fetch_assoc($result);
}

$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Users</h1>
          </div><!-- /.col -->         
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <?php
        if($insert == true){
          alertSuccess("Data Success added");
        }
      ?>
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-12">
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Registered Users</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Username</th>
                    <th>Fullname</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)){ ?>
                      <tr>
                        <td><?php echo $row["username"] ?></td>
                        <td><?php echo $row["fullname"] ?></td>
                        <td><?php echo $row["role"] ?></td>
                        <td><?php echo $row["active"] ?></td>
                        <td><a href="?page=<?php echo $page ?>&edit=<?php echo $row["username"] ?>"><i class="fas fa-edit">Edit</i></a></td>
                      </tr>
                    <?php } ?>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            
            <?php if(!isset($_GET["edit"])) { ?>

              <!-- /.add-user -->
              <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Add User</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="?page=<?php echo "$page" ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label>Username*</label>
                    <input type="text" class="form-control" placeholder="Username can't be same" name="username" required>
                  </div>
                  <div class="form-group">
                    <label>Password*</label>
                    <input type="password" class="form-control" name="password" required>
                  </div>
                  <div class="form-group">
                    <label>Fullname*</label>
                    <input type="text" class="form-control" name="fullname" required>
                  </div>
                  <div class="form-group">
                    <label>Role*</label>
                    <div class="input-group">
                      <select class="form-control" name="role">
                        <option value="User">User</option>
                        <option value="Admin">Admin</option>
                      </select>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-warning">Add</button>
                </div>
              </form>
              </div>

            <?php } else { ?>
            
            <!-- /.edit-user -->
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Edit User</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="?page=<?php echo "$page" ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label>Username*</label>
                    <input type="hidden" name="editData" value="<?php echo $data['username'] ?>">
                    <input value="<?php echo $data['username'] ?>" type="text" class="form-control" placeholder="Username can't be same" name="username" required>
                  </div>
                  <div class="form-group">
                    <label>Password*</label>
                    <input type="password" class="form-control" placeholder="Keep empty if no change" name="password">
                  </div>
                  <div class="form-group">
                    <label>Fullname*</label>
                    <input value="<?php echo $data['fullname'] ?>" type="text" class="form-control" name="fullname" required>
                  </div>
                  <div class="form-group">
                    <label>Role*</label>
                    <div class="input-group">
                      <select class="form-control" name="role">
                        <?php if($data['role'] == 'Admin') { ?>
                          <option value="Admin">Admin</option>
                          <option value="User">User</option>
                        <?php } else { ?>
                          <option value="User">User</option>
                          <option value="Admin">Admin</option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Status*</label>
                    <div class="input-group">
                        <select class="form-control" name="active">
                          <?php if($data['active'] == 'Yes') { ?>
                            <option value="Yes">Yes</option>
                            <option value="No">No</option>
                          <?php } else { ?>
                            <option value="No">No</option>
                            <option value="Yes">Yes</option>
                          <?php } ?>
                        </select>
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-danger">Add</button>
                </div>
              </form>
            </div>

            <?php } ?>

            

          </div>
          
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->