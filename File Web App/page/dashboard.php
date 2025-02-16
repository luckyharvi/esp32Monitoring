<?php
  $sql = "SELECT * FROM devices WHERE active='Yes'";
  $result = mysqli_query($conn,$sql);
?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->

  <!-- Main content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
<!-- widget suhu -->
        <div class="col-lg-4">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3><span id="suhu">-</span><sup style="font-size: 20px">°C</sup></h3>
              <p>Suhu</p>
            </div>
            <div class="icon">
              <i class="fas fa-thermometer-half"></i>
            </div>
          </div>
        </div>
<!-- widget kelembapan -->
        <div class="col-lg-4">
          <div class="small-box bg-lightblue">
            <div class="inner">
              <h3><span id="kelembapan">-</span><sup style="font-size: 20px">%</sup></h3>

              <p>Kelembapan</p>
            </div>
            <div class="icon">
              <i class="fas fa-water"></i>
            </div>
          </div>
        </div>
<!-- widget potensiometer -->
        <div class="col-lg-4">
          <div class="small-box bg-secondary">
            <div class="inner">
              <h3><span id="potensiometer">-</span><sup style="font-size: 20px">%</sup></h3>

              <p>Potensiometer</p>
            </div>
            <div class="icon">
              <i class="fas fa-tachometer-alt"></i>
            </div>
          </div>
        </div>
<!-- widget servo -->
        <div class="col-8">
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Servo</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">

              <div class="row margin">
                <div class="col-sm-12">
                  <input id="servo" type="text" value="" onchange="publishServo()">
                </div>
              </div>

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
<!-- widget led -->
        <div class="col-4">
          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title">LED</h3>
            </div>
            <div class="card-body table-responsive pad">

              <!-- /.LED 1 -->
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn btn-primary" id="label-led1on">
                  <input type="radio" name="led1" id="led1on" autocomplete="off" onchange="publishLED1()"> On
                </label>
                <label class="btn btn-primary" id="label-led1off">
                  <input type="radio" name="led1" id="led1off" autocomplete="off" onchange="publishLED1()"> Off
                </label>               
              </div>
              
              <!-- /.LED 2 -->
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
                <label class="btn bg-secondary" id="label-led2on">
                  <input type="radio" name="led2" id="led2on" autocomplete="off" onchange="publishLED2()"> On
                </label>
                <label class="btn bg-secondary" id="label-led2off">
                  <input type="radio" name="led2" id="led2off" autocomplete="off" onchange="publishLED2()"> Off
                </label>
              </div>

            </div>
          </div>

        </div>
<!-- widget status perangkat -->
        <div class="col-12">
          <div class="card card-green">
            <div class="card-header">
              <h3 class="card-title">Device Status</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0" style="height: 300px;">
              <table class="table table-head-fixed text-nowrap">
                <thead>
                  <tr>
                    <th>Serial Number</th>
                    <th>Location</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while ($row = mysqli_fetch_assoc($result)){?>
                    <tr>
                      <td><?php echo $row["serial_number"] ?></td>
                      <td><?php echo $row["location"] ?></td>
                      <td style="color:red;" id="luckyharvi/status/<?php echo $row['serial_number'] ?>">Offline</td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
          </div>

      </div>
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div><!-- /.content -->

<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>

<script>
  const clientId = Math.random().toString(16).substr(2, 8);
  const host = "wss://username.cloud.shiftr.io:443";

  const option = {
    keepalive: 30,
    clientId: clientId,
    username: "",
    password: "",
    protocolId: "MQTT",
    protocolVersion: 4,
    reconnectPeriod: 1000,
    connectTimeout: 30 * 1000,
  };

  console.log("Connecting to Broker");
  const client = mqtt.connect(host, option);

  client.on("connect", ()=>{
    console.log("Connected to Broker. Clientid: " + clientId);
    document.getElementById("status").innerHTML="Connected";
    document.getElementById("status").style.color = "green";

    client.subscribe("luckyharvi/#", {qos: 1});
  });

  client.on("message", function(topic, payload){
    if(topic == "luckyharvi/0123456789/suhu"){
      document.getElementById("suhu").innerHTML = payload;
    } else if(topic == "luckyharvi/0123456789/kelembapan"){
      document.getElementById("kelembapan").innerHTML = payload;
    } else if(topic == "luckyharvi/0123456789/potensiometer"){
      document.getElementById("potensiometer").innerHTML = payload;
    } else if(topic == "luckyharvi/0123456789/servo"){
      let servo1 = $("#servo").data("ionRangeSlider")
      servo1.update({
        from: payload.toString()
      })
    } 

    if(topic == "luckyharvi/0123456789/led/1"){
      if(payload == "on"){
        document.getElementById("label-led1on").classList.add("active");
        document.getElementById("label-led1off").classList.remove("active");
      } else {
        document.getElementById("label-led1on").classList.remove("active");
        document.getElementById("label-led1off").classList.add("active");
      }
    }
    if(topic == "luckyharvi/0123456789/led/2"){
      if(payload == "on"){
        document.getElementById("label-led2on").classList.add("active");
        document.getElementById("label-led2off").classList.remove("active");
      } else {
        document.getElementById("label-led2on").classList.remove("active");
        document.getElementById("label-led2off").classList.add("active");
      }
    }

    if(topic.includes("luckyharvi/status/0123456789")){
      document.getElementById(topic).innerHTML = payload;
      if(payload == "Offline"){
        document.getElementById(topic).style.color = "red";
      } else if(payload == "Online"){
        document.getElementById(topic).style.color = "green";
      }
    }
  });

  function publishServo(){
    data = document.getElementById("servo").value;
    client.publish("luckyharvi/0123456789/servo", data, {qos: 1, retain: true});
  }

  function publishLED1(){
    if(document.getElementById("led1on").checked){
      data = "on";
    } 
    if(document.getElementById("led1off").checked){
      data = "off";
    }
    client.publish("luckyharvi/0123456789/led/1", data, {qos: 1, retain: true});
  }

  function publishLED2(){
    if(document.getElementById("led2on").checked){
      data = "on";
    }
    if(document.getElementById("led2off").checked){
      data = "off";
    }
    client.publish("luckyharvi/0123456789/led/2", data, {qos: 1, retain: true});
  }
</script>
