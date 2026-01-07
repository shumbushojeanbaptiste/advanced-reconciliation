<?php
	 // Delete ....
     if(ISSET($_GET['delete_block'])){
		$sql = $db->prepare("DELETE from `tbl_blocks` WHERE `id`='".$_GET['delete_block']."' ");
		$sql->execute();
	
		$msge="Block <b>".$_GET['block_name']."</b> is successfully Removed";
        echo '<meta http-equiv="refresh"'.'content="2;URL=?file=view_blocks">';
	}
	
    // for activate Subject
    if(isset($_GET['acid']))
    {

        try{
    			$change_status = 1;
    			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    			$sql = "UPDATE `tbl_blocks` SET `status` = '".$change_status."' WHERE `id` = '".$_GET['acid']."'";
    			$acidq = $db->prepare($sql);
    			$acidq->execute();
    			$msg="Block Activated successfully";
                echo '<meta http-equiv="refresh"'.'content="2;URL=?file=view_blocks">';
    		}catch(PDOException $e){
    			echo $e->getMessage();
    		}
    		
      }
    
     // for Deactivate Subject
        else if(isset($_GET['did']))
          {
     try{
    			$change_status = 0;
    			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    			$sql = "UPDATE `tbl_blocks` SET `status` = '".$change_status."' WHERE `id` = '".$_GET['did']."'";
    			$didq = $db->prepare($sql);
    			$didq->execute();
    			$msge="Block Deactivated successfully";
                echo '<meta http-equiv="refresh"'.'content="2;URL=?file=view_blocks">';
    		}catch(PDOException $e){
    			echo $e->getMessage();
    		}
    		
    	}
?>
<div class="span9">
    <div class="content">
		<div class="module">
            <div class="module-head">
                <h3>
                    All Blocks(<?php echo $fetch_bl['total_blocks']; ?>)</h3>
            </div>
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
			<div class="module-body table">
				<table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display" width="100%">
					<thead>
						<tr>
							<th>No.</th>
							<th>Block Name</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
                        <?php
                            $sql_blocks = $db->prepare("SELECT * FROM tbl_blocks");
                            $sql_blocks->execute();
                            $i = 0;
                            while($fetblocks = $sql_blocks->fetch()) {
                                $bl_id = $fetblocks['id'];
                                $i += 1;
                        ?>
						<tr class="gradeU">
							<td><?php echo $i; ?></td>
							<td><?php echo $fetblocks['name']; ?></td>
							<td>
        						<?php if($fetblocks['status'] == 0)
                                  { ?>
                                    <a href="?file=view_blocks&&acid=<?php echo $bl_id;?>" class="btn btn-danger" onclick="if(!confirm('Do you really want to Activate This Block?'))return false;else return true;">Occupied</a><?php } else {?>
        
                                    <a href="?file=view_blocks&&did=<?php echo $bl_id;?>" class="btn btn-success" onclick="if(!confirm('Do you really want to Deactivate This Block?'))return false;else return true;">Available</a>
                                 <?php }?>
							</td>
                            <td class="cell-icon text-center">
                                <a href="#?delete_block=<?php echo $bl_id;?>&&block_name=<?php echo $fetblocks['name'];?>" onclick="if(!confirm('Do you really want to Delete This Block?'))return false;else return true;" title="Delete block"><i class="icon-trash"></i> delete</a> |
                                <a href="#"><i class="icon-edit"></i> edit</a>
                            </td>
						</tr>
						<?php
                            }
						?>
					</tbody>
					<tfoot>
						<tr>
							<th>No</th>
							<th>Name</th>
							<th>Status</th>
							<th>Action</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div><!--/.module-->
    </div>
    <!--/.content-->
</div>
<!--/.span9-->