<?php
 require_once ("../include/initialize.php");
$sql = $conn->prepare("SELECT *FROM tbl_tenants WHERE ten_id='".$_REQUEST['ten_id']."' ");
$sql->execute();
$tenant = $sql->fetch();
$ten_id=$tenant['ten_ref_no'];

?>


<style>
 #topinfo{}
 #topinfo td{padding-left:40px;height:30px;font-size:15px;font-style:italic;}
</style>

<div id="modal_popupeditassigns"></div>
 <input type="hidden" name="" value="<?php echo $get ?>"/>
 <center>
 <?php
 echo"<h2><strong> ".$tenant['te_names'].' ['.$tenant['ten_phone'].']'."</strong></h2>";
 ?>
 </center>
        <button class="btn btn-default pull-right" onclick="window.location.href='?file=tenmgnt'" style="margin:10px;">BACK</button>

<form id="editmodassignslev" action="editmodassignslev.php" method="post" class="form-horizontal " enctype="multipart/form-data">
    <div class="table-responsive">
        <?php 
        $i= 0 ;
        $ten_stmt = $conn->prepare("SELECT * FROM tbl_assign 
        INNER JOIN tbl_rooms ON tbl_assign.room_id=tbl_rooms.room_id WHERE tbl_assign.tent_id='".$_REQUEST['ten_id']."'");
        $ten_stmt->execute();
        if($ten_stmt->rowCount() > 0 ){
        ?>
        <br> 
       <table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	display" width="100%" style="font-size:11px;margin-left:10px;margin-right:10px;">
         
		<thead>
			<tr>
			    <th>#</th>
				<th>Room No.</th>
				<th>Rent Date</th>
				<th>Amount</th>
				<th>Action</th>
			</tr>
		</thead>
            <tbody> 
           <?php
		   	    
                while($fetch_ten = $ten_stmt->fetch()) {
                    $t_id = $fetch_ten['assgn_id'];
                    $i += 1;
             ?>
             <tr class="gradeA">
			    <td class="center">
				    <?php echo $i; ?>
				</td>
				<td class="center">
				    <?php echo $fetch_ten['room_no']; ?>
				</td>
				
				<td class="center">
				    <?php echo $fetch_ten['rent_date']; ?>
				</td>
				<td class="center">
                    <?php echo $fetch_ten['price']; ?>
				</td>
                <td class="cell-icon text-center">
                    <a href="?file=tenmgnt&remove_room=<?php echo $fetch_ten['assgn_id'];?>&&ROOM=<?php echo $fetch_ten['room_id'];?>&&ten_id=<?php echo $ten_id  ?>" 
                    onclick="if(!confirm('Do you really want to remove room from Tenant: <?php echo $tenant['te_names']; ?> ?'))return false;else return true;" 
                    title="Remove Room"><span style="color: #2e2e2e;"><i class="icon-trash"></i> Remove</span></a>
                   
                </td>
			</tr>
			<?php
		                  
                }
			?>
            </tbody>
            
        </table>
        
        <?php 
        // echo '<meta http-equiv="refresh"'.'content="5;URL=?file=tenmgnt">';
        }else{
            ?>
            
            <div class="alert alert-danger">
    		<button type="button" class="close" data-dismiss="alert">×</button>
    		<strong>Ooooh Snap!</strong> no record Found
    		
    		 
    	</div>
         <?php 
         echo '<meta http-equiv="refresh"'.'content="1;URL=?file=tenmgnt">';
        }
        ?>
    </div>
 <!-- /.table-responsive -->
 
    </form>