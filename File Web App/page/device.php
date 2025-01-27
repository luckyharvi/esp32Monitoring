<?php
$page = $_GET["page"];
$insert = false;

if(isset($_POST["editData"])) { //menampilkan form edit data
  $old_id = $_POST["editData"];

  $serialNumber = $_POST["serial_number"];
  $mcuType = $_POST["mcu_type"];
  $location = $_POST["location"];
  $active = $_POST["active"];

  $sql_edit = "UPDATE devices SET serial_number='$serialNumber', mcu_type='$mcuType', location='$location', active='$active' WHERE serial_number= '$old_id'";
  mysqli_query($conn,$sql_edit);

} else if(isset($_POST["serial_number"])){ //menampilkan form tambah data
  $serialNumber = $_POST["serial_number"];
  $mcuType = $_POST["mcu_type"];
  $location = $_POST["location"];

  $sql_insert = "INSERT INTO devices (serial_number, mcu_type, location) VALUES ('$serialNumber', '$mcuType', '$location')";
  mysqli_query($conn, $sql_insert);
  $insert = true;
}

if(isset($_GET["edit"])){
  $id = $_GET["edit"];
  $sql_select_data = "SELECT * FROM devices WHERE serial_number = '$id' LIMIT 1";

  $result = mysqli_query($conn, $sql_select_data);
  $data = mysqli_fetch_assoc($result);
}

$sql = "SELECT * FROM devices";
$result = mysqli_query($conn, $sql);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Devices</h1>
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
                <h3 class="card-title">Registered Devices</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Serial Number</th>
                    <th>MCU Type</th>
                    <th>Location</th>
                    <th>Created Time</th>
                    <th>Active</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)){ ?>
                      <tr>
                        <td><?php echo $row["serial_number"] ?></td>
                        <td><?php echo $row["mcu_type"] ?></td>
                        <td><?php echo $row["location"] ?></td>
                        <td><?php echo $row["created_time"] ?></td>
                        <td><?php echo $row["active"] ?></td>
                        <td><a href="?page=<?php echo $page ?>&edit=<?php echo $row["serial_number"] ?>"><i class="fas fa-edit">Edit</i></a></td>
                      </tr>
                    <?php } ?>
                  </tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            
            <?php if(!isset($_GET["edit"])) { ?>

              <!-- /.add-device -->
              <div class="card card-warning">
              <div class="card-header">
                <h3 class="card-title">Add Device</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="?page=<?php echo "$page" ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label>Serial Number*</label>
                    <input type="text" class="form-control" placeholder="Serial Number can't be same" name="serial_number" required>
                  </div>
                  <div class="form-group">
                    <label>MCU Type*</label>
                    <input type="text" class="form-control" name="mcu_type" required>
                  </div>
                  <div class="form-group">
                    <label>Location*</label>
                    <div class="input-group">
                      <input type="text" class="form-control" name="location" required>
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
            
            <!-- /.edit-device -->
            <div class="card card-danger">
              <div class="card-header">
                <h3 class="card-title">Edit Device</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="?page=<?php echo "$page" ?>">
                <div class="card-body">
                  <div class="form-group">
                    <label>Serial Number*</label>
                    <input type="hidden" name="editData" value="<?php echo $data['serial_number'] ?>">
                    <input value="<?php echo $data['serial_number'] ?>" type="text" class="form-control" placeholder="Serial Number can't be same" name="serial_number" required>
                  </div>
                  <div class="form-group">
                    <label>MCU Type*</label>
                    <input value="<?php echo $data['mcu_type'] ?>" type="text" class="form-control" name="mcu_type" required>
                  </div>
                  <div class="form-group">
                    <label>Location*</label>
                    <div class="input-group">
                      <input value="<?php echo $data['location'] ?>" type="text" class="form-control" name="location" required>
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