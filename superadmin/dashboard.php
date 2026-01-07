        <div class="container-fluid">
          <div class="row justify-content-center">
            <div class="col-12">
              <div class="row align-items-center mb-2">
                <div class="col">
                  <h2 class="h5 page-title">Welcome!</h2>
                </div>
                <div class="col-auto">
                  <form class="form-inline">
                    <div class="form-group d-none d-lg-inline">
                      <label for="reportrange" class="sr-only">Date Ranges  200</label>
                      <div id="reportrange" class="px-2 py-2 text-muted">
                        <span class="small"></span>
                      </div>
                    </div>
                    <div class="form-group">
                      <button type="button" class="btn btn-sm"><span class="fe fe-refresh-ccw fe-16 text-muted"></span></button>
                      <button type="button" class="btn btn-sm mr-2"><span class="fe fe-filter fe-16 text-muted"></span></button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="row my-4">
                <div class="col-md-4">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                            <?php
                        // number of users
                        $sql_us = $db->prepare("SELECT count(*) as total_users FROM tbl_admins");
                        $sql_us->execute();
                        $fetch_us = $sql_us->fetch();
                    ?>
                       <a href="?file=rooms" ><div class="row align-items-center">
                        <div class="col">
                           
                          <small class="text-muted mb-1">Number of Users</small>
                          <h2 class="card-title mb-0"><?php echo $fetch_us['total_users']; ?></h2>
                          <p class="small text-muted mb-0"></span></p>
                        </div>
                        <!--<div class="col-4 text-right">-->
                        <!--  <span class="sparkline inlineline"></span>-->
                        <!--</div>-->
                      </div> <!-- /. row -->
                      </a>
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                <div class="col-md-4">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                      <div class="row align-items-center">
                        <div class="col">
                       
                          <small class="text-muted mb-1">Number of Tenants</small>
                          <h3 class="card-title mb-0"><?php echo $fetch_tnts['total_tenants']; ?></h3>
                          <p class="small text-muted mb-0"></p>
                        </div>
                        <!--<div class="col-4 text-right">-->
                        <!--  <span class="sparkline inlinepie"></span>-->
                        <!--</div>-->
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
                <div class="col-md-4">
                  <div class="card shadow mb-4">
                    <div class="card-body">
                         
                      <div class="row align-items-center">
                        <div class="col">
                          <small class="text-muted mb-1">Number Of Rooms</small>
                          <h3 class="card-title mb-0"><?php echo $fetch_rm['total_rooms'] ?></h3>
                          <p class="small text-muted mb-0"></p>
                        
                        </div>
                        <!--<div class="col-4 text-right">-->
                        <!--  <span class="sparkline inlinebar"></span>-->
                        <!--</div>-->
                      </div> <!-- /. row -->
                    </div> <!-- /. card-body -->
                  </div> <!-- /. card -->
                </div> <!-- /. col -->
              </div><!-- .row -->
            </div> <!-- .col-12 -->
          </div> <!-- .row -->
        </div> <!-- .container-fluid -->