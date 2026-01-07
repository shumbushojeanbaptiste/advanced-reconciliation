<?php include 'hd.php';?>
    <div class="wrapper vh-100">
      <div class="row align-items-center h-100">
        
        <form action="login_user" method="post" id="form-login" class="col-lg-3 col-md-4 col-10 mx-auto text-center">
        <?php
           if(isset($_REQUEST['msg'])){
        ?>
            <div class="alert alert-danger" role="alert">
              <span class="fe fe-minus-circle fe-16 mr-2"></span> <?php echo $_REQUEST['msg'];?> 
            </div>    
        <?php
         }
        ?>
        <div style="background-color: rgba(249, 250, 252, 0.7);border-radius: 5px;">
            <div style=" margin: 50px;">
          <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="">
          <img src="img/xode3.png" width="30%">
          </a>
          <h1 class="h6 mb-3">Sign in</h1>
          <div class="form-group">
            <label for="inputEmail" class="sr-only">Username</label>
            <input type="text" id="login-email" class="form-control form-control-lg" placeholder="Username" name="login-email" value="<?php if(isset($_COOKIE["login-email"])) { echo $_COOKIE["login-email"]; } ?>" required="" autofocus="">
          </div>
          <div class="form-group">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="login-password" class="form-control form-control-lg" placeholder="Password" name="login-password" value="<?php if(isset($_COOKIE["login-password"])) { echo $_COOKIE["login-password"]; } ?>" required="">
          </div>
          <div class="checkbox mb-3">
            <label>
              <input type="checkbox" id="login-remember-me" name="login-remember-me" value="remember-me"> Stay logged in </label>
          </div>
          <button class="btn btn-lg btn-primary btn-block" type="submit" name="loginbtn">Login</button>
          <div class="mt-2">
              
          <label for="download" class="md">Download </label> <a class="text-primary text-lg" href="https://xode.rw/expenses/apps/XodeNew.apk">app</a>
          </div>
          <p class="mt-5 mb-3">
          <!--<a href="" style="color: #4d4542;font-weight: bold;">Android App </a> &nbsp;-->
          © <script type="text/javascript">var year = new Date();document.write(year.getFullYear());</script>
          </p>
          </div>
          </div>
        </form>
      </div>
    </div>

    
   
<?php include 'ft.php';?>