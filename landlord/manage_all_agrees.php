<?php
    if(ISSET($_POST['upload'])){
        $id1=$_POST['id1'];
        // Set image placement folder
        $target_dir = "../img/contract_docs/";
        // Get file path
        $target_file = $target_dir . basename($_FILES["contract_doc"]["name"]);
        // Get file extension
        $imageExt = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (!file_exists($_FILES["contract_doc"]["tmp_name"])) {
            echo '<script language="javascript">
                  alert("Select new image to upload. ");
                  window.location.href = "?file=manage-agree";
                  </script>';
        }
        else if ($_FILES["contract_doc"]["size"] > 2097152) {
		    $msge = "File is too large. File size should be less than 2 megabytes.  ";
            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=manage-agree">';
        } else {
            if (move_uploaded_file($_FILES["contract_doc"]["tmp_name"], $target_file)) {
                $sql1 = "UPDATE tbl_contracts set contract_path = '$target_file' where contr_id='$id1'";
                $stmt1 = $conn->prepare($sql1);
                 if($stmt1->execute()){
		            $msg = "Contract Modified successfully. ";
            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=manage-agree">';
                 }
                
            } else {
		        $msge = "Image coudn\'t be uploaded. ";
            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=manage-agree">';
            }
        }

    }
?>

<style>
    .modal { display:none; }
    .modal-title {color: #111;}
</style>
<div class="span9">
  <div class="content">

        <?php
            if(!empty($msg)) {?>
    	<div class="alert alert-success">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Well done!</strong> <?php echo $msg; ?> 
    	</div>

        <?php
          }
           else if(!empty($msge)) {
        ?>
    	<div class="alert alert-error">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Oh snap!</strong>  <?php echo $msge; ?> 
    	</div>
        <?php
           }
        ?>
<div class="module">
 <ul class="nav nav-tabs">
    <li class="active"><a href="#view_inv" data-toggle="tab">Manage Contracts</a></li>
</ul>

 
</div><!--/.module-->
 <div class="module">
 <!-- <div class="module-head">-->
 <!--       <h3>Manage Contracts</h3>-->
 <!--</div>-->
 <div class="row">
     
     
<div class="col-md-12"><br><br>
  <div class="col-auto" align="rigth">
                  <button type="button" class="btn btn-lg btn-primary" name="add" id="add" data-toggle="modal" data-target="#tenantsModal"><span class="fe fe-plus fe-16 mr-3"></span>Add New</button>
                </div>
  <div class="card shadow mb-4">
     
    <div class="card-body">
        <br><br>
      <div class="table-responsive">
   
	<table class="table datatables table-hover" id="dataTable-1">
		
		<thead>
			<tr>
				<th>#</th>
				<th>Room Details</th>
				<th>Tenant Info.</th>
				<th>Due day</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php
                $sql_agrees = $conn->prepare("SELECT tc.*, tr.room_name, tr.room_no, tr.price FROM `tbl_contracts` tc INNER JOIN `tbl_rooms` tr ON tc.room_id = tr.room_id where tr.company_id = '$profile' order by tc.starting_date desc");
                $sql_agrees->execute();
                $i = 0;
                while($feagrees = $sql_agrees->fetch()) {
                    $cont_id = $feagrees['contr_id'];
                    $tnt_id = $feagrees['tenant_id'];
                    $rmid = $feagrees['room_id'];
                    $i += 1;
                    
                    $sql_tenant = $conn->prepare("SELECT * FROM tbl_tenants where id = '$tnt_id'");
                    $sql_tenant->execute();
                    $fetenant = $sql_tenant->fetch();
            ?>
			<tr class="gradeA">
				<td class="center"><?php echo $i; ?></td>
				<td class="center"> <strong>Name: </strong><?php echo $fetenant['first_name'].' '.$fetenant['last_name']; ?><br>
				
				 <strong>Phone: </strong><?php echo $fetenant['phone']?><br>
		         <strong>Email: </strong><?php echo $fetenant['email']?> 
		        </td>
		        
				<td class="center">
				   <strong>Room: </strong><?php echo $feagrees['room_name']; ?><br>
				   <strong>CODE: </strong><?php echo $feagrees['room_no']; ?><br>
				   <strong>Rental cost: </strong><?php echo $feagrees['price'].".00"; ?>
				    
				</td>
				<td class="center">
				   <strong>On</strong> <?php echo substr($feagrees['starting_date'], -2); ?>th
				</td>
				<td class="center"><?php 
				    if(!empty($feagrees['contract_path'])) {
				        ?>
				        <span class="text-success"><a href="<?php echo $feagrees['contract_path']; ?>" target="_blank"><i class="fa fa-eye"></i> View</a>
				        | 
				        <a href="#contractUploadModal" data-target="#contractUploadModal" name="add" class="contract_edit" id="<?php echo $cont_id; ?>"> <i class="icon-edit"></i> Update</a></span>
				    <?php
				    }
				    else {
				?>
                        <button type="button" class="btn btn-info" name="add" id="add" data-toggle="modal" data-target="#contractUploadModal">Upload</button>
                    <?php
				        }
                    ?>
				</td>
			</tr>
			<?php
                }
			?>
		 </tbody>
	</table>
     
    </div>
      
  <div name='display_specs' id='display_specs'>
    </div> <!-- /.card-body -->
  </div> <!-- /.card -->
</div> <!-- /.col -->

        </div>
</div> <!-- end section -->
</div><!--/.module-->

</div><!--/.content-->
</div><!--/.span9-->

<!-- Form modal -->
<div class="modal fade" id="contractUploadModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="contractUploadModalTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">  
        <div class="modal-header">  
             <button type="button" class="close" data-dismiss="modal">&times;</button>  
             <h3 class="modal-title">Upload Contract</h3><span id="file_desc" style="display: none;"></span>  
        </div>
      <div class="modal-body">
        <form class="form-horizontal row-fluid" id="add_contract" method="post" action="" enctype="multipart/form-data">
            <div class="stream-attachment photo">
                <div class="responsive-photo">
                    <div class="thumbnail">
                        <div class="image view view-first">
                            <img src="../img/contract_docs/pdf_icon.png" alt="" id="profileDisplay"/>
    		                <input type="hidden" id="cont_id" name="id1" class="span8" required>
                            <input type="file" name="contract_doc" onChange="displayImage(this)" id="profileImage" class="form-control" style="display: none;">
                            <div class="mask">
                              <div class="tools tools-bottom">
                                <i class="fa icon-pencil" onClick="triggerClick()"></i>
                              </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="upload" id="upload" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /form modal -->