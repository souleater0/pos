<?php
  require 'session_checker.php'; // Assuming this file initializes the session and checks user state
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inventory System - Login</title>
  <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
  <link rel="stylesheet" href="../assets/css/styles.min.css" />
  <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../assets/libs/toastr/css/toastr.min.css">
  <script src="../assets/libs/toastr/js/toastr.min.js"></script>
  <script src="../assets/js/all.js"></script>
</head>
<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a class="text-nowrap logo-img text-center d-block py-3 w-100">
                  <img src="../assets/images/logos/takome_logo.png" alt="" width="200">
                </a>
                <p class="text-center fw-bold">Point of Sale / Inventory System</p>
                <form id="login_form">
                  <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" aria-describedby="emailHelp">
                  </div>
                  <div class="mb-4">
                    <label for="userPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="userPassword" name="password">
                  </div>
                  <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check">
                      <input class="form-check-input primary" type="checkbox" value="" id="flexCheckChecked" checked>
                      <label class="form-check-label text-dark" for="flexCheckChecked">
                        Remember this Device
                      </label>
                    </div>
                  </div>
                  <button type="button" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" id="submit_login">Sign In</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script>
    $(document).ready(function(){
      // Handle form submission
      $('#userPassword').keypress(function (event) {
          if (event.keyCode === 13) { // Enter key
              event.preventDefault(); // Prevent form submission
              loginForm();
          }
      });
      $('#submit_login').click(function(){
        loginForm();
      });

      function loginForm(){
        var formData = $('#login_form').serialize(); // Include csrf_token in the serialized data
        $.ajax({
            url: "process/admin_action.php",
            method: "POST",
            data: formData + "&action=login", // Append action to the form data
            dataType: "json",
            success: function(response) {
                if(response.success == true) {
                    toastr.success(response.message);
                    setTimeout(function() {
                        window.location.href = response.redirectUrl;
                    }, 2000);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function() {
                toastr.error("An error occurred, please try again.");
            }
        });
      }
    });
  </script>
</body>
</html>
