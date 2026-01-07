<?php include 'hd2.php'; ?>
<div class="wrapper vh-80">
  <main class="d-flex w-100 h-100" id="access-space">
    <div class="container d-flex flex-column">
      <div class="row vh-100">
        <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
          <div class="d-table-cell align-middle">
            

            <div class="card">
              <div class="card-body">
                <div class="m-sm-3">
                  <p class="lead text-center">Sign in to your account to continue</p>

                  <div class="row align-items-center mb-3">
                    <div class="col">
                      <hr>
                    </div>
                    <div class="col-auto text-center" >
                      <a class="navbar-brand mx-auto" href="#">
                        <img src="img/xode3.png" width="30%">
                      </a>
                    </div>
                    <div class="col">
                      <hr>
                    </div>
                  </div>

                  <!-- ✅ Login Form -->
                  <form id="rentaccess" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                      <label for="email" class="form-label">Email</label>
                      <input type="email" class="form-control form-control-lg"
                             name="login-email" id="email"
                             placeholder="Enter your email" required>
                    </div>

                    <div class="mb-3">
                      <label for="password" class="form-label">Password</label>
                      <input type="password" class="form-control form-control-lg"
                             name="login-password" id="password"
                             placeholder="Enter your password" required>
                      <small>
                        <a href="#">Forgot password?</a>
                      </small>
                    </div>

                    <div class="form-check align-items-center">
                      <input type="checkbox" class="form-check-input"
                             id="customControlInline"
                             name="login-remember-me"
                             value="true" checked>
                      <label class="form-check-label text-small"
                             for="customControlInline">
                        Remember me
                      </label>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                      <button type="submit"
                              class="btn btn-lg btn-primary btn-block"
                              id="loginbutton">
                        <span id="spinner"></span>
                        <span id="indicator">Sign in</span>
                      </button>
                    </div>
                  </form>

                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </main>
</div>

<?php include 'ft.php'; ?>


<!-- ✅ Notification libraries -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

<script>
// ✅ Toastr global configuration
toastr.options = {
  positionClass: 'toast-top-center',
  timeOut: 3000,
  closeButton: true,
  progressBar: true,
};

// ✅ Notification helpers
function login_success(message = 'Login successful') {
  toastr.success(message, 'Account credentials correct');
}

function login_fail(message = 'Incorrect credentials') {
  toastr.error(message, 'Login failed');
}

function pop_wrong(feedback) {
  iziToast.warning({
    title: 'Fail',
    message: feedback,
    position: 'topCenter'
  });
}

function pop_up_success(feedback) {
  iziToast.success({
    title: 'Welcome to Xode (Rent and Utilites Management software)', 
    message: feedback,
    position: 'topCenter'
  });
}

// ✅ Form submit handler
$(document).ready(function () {
  $("#rentaccess").submit(function (e) {
    e.preventDefault();

    // Disable inputs during authentication
    $("#loginbutton, #password, #email").prop("disabled", true);
    $("#spinner").html("<img src='./images/ajax_loader.gif' width='15'>").fadeIn('fast');
    $("#indicator").html("Authenticating...");

   var formData = {
    login_email: $("#email").val(),
    login_password: $("#password").val(),
    login_remember_me: $("#customControlInline").is(":checked") ? "true" : "false"
};



    

    $.ajax({
      url: "check-in.php",
      type: "POST",
      data: formData,
      success: function (response) {
        $("#spinner").fadeOut('fast');
        $("#indicator").html("Verifying...");

        let data;
		
        try {
          data = JSON.parse(response);
        } catch (err) {
          pop_wrong("Invalid response from server: " + response);
          $("#indicator").html("Sign in");
          $("#loginbutton, #password, #email").prop("disabled", false);
          return;
        }

        console.log("Server response:", data);

        if (data.status === "success") {
          login_success("Login successful!");

          const formData2 = {
            email: $("#email").val(),
            password: $("#password").val(),
            token: data.token || "",
			phone: data.phone || "",
			role: data.role || "",
			side_value: data.side_value || "",
          };

          $.ajax({
            url: 'reset-pwd.php',
            type: "POST",
            data: formData2,
            success: function (content) {
              $("#access-space").html(content);
              //pop_up_success("Logged in successfully");
            },
            error: function () {
              pop_wrong("Something went wrong while loading the dashboard.");
            }
          });

        } else if (data.status === "error") {
          login_fail(data.message);
          $("#indicator").html("Sign in");
        } else {
          pop_wrong("Unexpected server response: " + data.message);
          $("#indicator").html("Try again");
        }

        $("#loginbutton, #password, #email").prop("disabled", false);
      },

      error: function () {
        $('#spinner').fadeOut('fast');
        $("#indicator").html("Login");
        pop_wrong("Network error! Please try again.");
        $("#loginbutton, #password, #email").prop("disabled", false);
      }
    });
  });
});
</script>
