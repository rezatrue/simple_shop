<?php

// Include the Database class
require './data/Database.php'; // Adjust the path as necessary


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['Username']))
      $user = $_POST['Username'];
  if (isset($_POST['Password']))
      $password = $_POST['Password'];
  if(isset($_SESSION))
      unset($_SESSION['user']);
  if (isset($_POST['Username']) && isset($_POST['Password'])){
    $db = new Database();
    $userDetails = $db->login($user, $password);
    $db->close();  
    if($userDetails){
      $_SESSION['user']['id'] = $userDetails['id'];
      $_SESSION['user']['name'] = $userDetails['name'];
      $_SESSION['user']['role'] = $userDetails['role'];
    }

  }

  if(isset($_SESSION['user']['id'])){
    header("Location: order_list.php");
    exit();
  }

}


?>

<!-- general form elements -->

  <div class="row">
    <div class="col-12 col-sm-6">
      <div class="card card-primary">
          <div class="card-header">
              <h3 class="card-title"> User Login</h3>
          </div>
          <!-- login form start -->
          <form action="admin.php" method="post">
            <div class="card-body">
              <div class="form-group">
                <label for="Username">Username</label>
                <input type="text" class="form-control" id="Username" name="Username" placeholder="Enter Username">
              </div>
              <div class="form-group">
                <label for="Password">Password</label>
                <input type="password" class="form-control" id="Password" name="Password" placeholder="Enter Password">
              </div>
              <div class="form-group">
                <input type="submit" value="Login">
              </div>            
            </div>
          </form>  
          <!-- login form ends -->
      </div>
    </div>
    <div class="col-12 col-sm-6">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"> User Registration</h3>
        </div>
        <!-- registration form start -->
        <form action="" method="post">
            <div class="card-body">
              <div class="form-group">
                <label for="Username">Username</label>
                <input type="text" class="form-control" id="Username" name="Username" placeholder="Enter Username" required>
              </div>
              <div class="row">
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="Password">Password</label>
                    <input type="password" class="form-control" id="Password" name="Password" placeholder="Enter Password" required>
                  </div>
                </div>
                <div class="col-12 col-sm-6">
                  <div class="form-group">
                    <label for="Repassword">Repassword</label>
                    <input type="password" class="form-control" id="Repassword" name="Repassword" placeholder="Enter Password">
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label for="FullName">Full Name</label>
                <input type="text" class="form-control" id="FullName" name="FullName" placeholder="Enter Full Name">
              </div>
              <div class="form-group">
                <label for="Phone">Phone</label>
                <input type="text" class="form-control" id="Phone" name="Phone" placeholder="Enter Phone Number">
              </div>
              <div class="form-group">
                <label for="Email">Email</label>
                <input type="text" class="form-control" id="Email" name="Email" placeholder="Enter Email Address">
              </div>
              <div class="form-group">
                <label for="Address">Address</label>
                <input type="text" class="form-control" id="Address" name="Address" placeholder="Enter Full Address">
              </div>
              <div class="form-group">
                <input type="submit" value="Submit">
              </div>            
            </div>
          </form>
        <!-- registration form ends -->
    </div>  
  </div>    
