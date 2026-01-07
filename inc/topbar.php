


 <nav class="topnav navbar navbar-light">
    <button type="button" class="navbar-toggler text-muted mt-2 p-0 mr-3 collapseSidebar">
      <i class="fe fe-menu navbar-toggler-icon" style="color: #000000"></i>
    </button>
    <ul class="nav">
      <li class="nav-item">
        <a class="nav-link text-muted my-2" href="#" id="modeSwitcher" data-mode="dark">
          <i class="fe fe-sun fe-16" style="color: #fff"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle text-muted pr-0" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="avatar avatar-sm mt-2">
            <img src="<?php echo empty($profile_pic)? '../img/profile_pics/avatar-img.png' : $profile_pic; ?>" alt="..." class="avatar-img rounded-circle">
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="<?php if($role == '2') {echo '?file=profile';} else {echo '#';}?>"><i class="fe fe-user fe-16"></i> Profile</a>
         
          <a class="dropdown-item" href="../logout"><i class="fe fe-power fe-16"> </i> Logout</a>
        </div>
      </li>
    </ul>
  </nav>
 
  