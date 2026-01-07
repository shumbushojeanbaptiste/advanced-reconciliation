


<?php 
 require_once ("../include/initialize.php");
 $floor_code = $_REQUEST['floor_code'];
 $CPSN = $_REQUEST['CPSN'];
 $comb = $CPSN."".$floor_code;
 
 

$maxArea = $db->prepare("SELECT SUM(room_area) AS area FROM tbl_rooms where tbl_rooms.company_id = '".$_REQUEST['c_id']."' ");
$maxArea->execute(array());
$rowArea = $maxArea->fetch(PDO::FETCH_ASSOC);


$maxTotalArea = $db->prepare("SELECT * FROM tbl_company where cpny_ID = '".$_REQUEST['c_id']."' ");
$maxTotalArea->execute(array());
$rowTotalArea = $maxTotalArea->fetch(PDO::FETCH_ASSOC);

$usedAreaTotal=$rowTotalArea['space'];
$usedArea=$rowArea['area'];

$Diffrent=$usedAreaTotal-$usedArea;


  
  
  
  $max = $db->prepare("SELECT room_id,room_no FROM tbl_rooms where tbl_rooms.company_id = '".$_REQUEST['c_id']."' AND auto_code=1  ORDER BY room_id DESC LIMIT 1");
        $max->execute(array()); 
        $data100=$max->rowCount();
        
        if($data100>0){
        $row1 = $max->fetch(PDO::FETCH_ASSOC);
        $room_no = $row1['room_no']; 
        
        $compNumber=strlen($CPSN);           
        $compcode=strlen($room_no);             
        $codeFinal=substr("$room_no",$compNumber,$compcode);
        
        $codeC=$CPSN.$codeFinal;
        $code=$codeFinal+1;
          if($code <10)
              {
                $c="00"; 
                $codeC=$CPSN."-1000".$code;
              }
            
              else if($code >=10 AND $code <100)
              {
                $c="0";
                 $codeC=$CPSN."00".$code;
              }
              else if($code >=100 AND $code <1000){
                $c="" ; 
                 $codeC=$CPSN."0".$code;
              }
              else{
                  $codeC=$CPSN.$code;
              }
        
        } 
        else{
         $codeC=$CPSN."0001";
            
        }
        
        
        
        
        
        
  
  
 
 ?>	

 <br/>	
 
                    <div class="row">
                    <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label for="simpleinput">CODE <span class="help-inline " style="color:red;" id="loader">*</span></label>
                    </div>
                    <div class="form-group mb-10">
                    <?php  if($_REQUEST['auto_code']==1){ ?>
                    <input type="hidden" name="auto_code" value="<?php echo $_REQUEST['auto_code'] ?>">
                     <input type="text" name="rm_code" id="rm_code"  class="form-control" value="<?php echo $codeC;  ?>" required readonly>
                    <?php  } else{ ?>
                     <input type="hidden" name="auto_code" value="<?php echo $_REQUEST['auto_code'] ?>">
                    <input type="text" name="rm_code" id="rm_code" placeholder="Write code..." class="form-control"  required >
                    <?php  }?>
                    </div>
                    </div>
                    </div>
	<!--<div class="control-group">-->
	<!--	<label class="control-label" for="basicinput">CODE</label>-->
	<!--	<div class="controls">-->
	<!--		<input type="text" name="rm_code" id="rm_code" placeholder="SNO..." class="span8" value="<?php echo $CPSN."".$floor_code;?>" required readonly>-->
	<!--		<span class="help-inline">*</span>-->
	<!--	</div>-->
	<!--</div>-->
	           <div class="row">
                    <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label for="simpleinput">Price <span class="help-inline " style="color:red;" id="loader">*</span></label>
                    </div>
                    <div class="form-group mb-10">
                    
                     <input type="text" name="price" id="price" placeholder="0.00" class="form-control" required>
                    </div>
                    </div>
                    </div>

                 <div class="row">
                    <div class="col-md-12">
                    <div class="form-group mb-2">
                         <label for="simpleinput">Area [m<sup>2</sup>]</label>
                    </div>
                    <div class="form-group mb-10">
                        
                        <input type="hidden" name="iTem" id="yrmaxArea" value="<?php echo $Diffrent;?>">
					   <input type="text" name="room_area" id="room_area" placeholder="0.0" class="form-control"  
					   
					   pattern="^[0-9]+(\.[0-9]{1,2})?$" onblur="if(parseFloat(this.value)>parseFloat(document.getElementById('yrmaxArea').value)){this.style.border = '1px solid #ff0000';alert('The Area is greater than remain ');document.getElementById('ckit_<?php echo $assign_idStock;?>').checked=false;this.value='';this.focus();return false;}else{return true;}" 
					   >    
					         
                   
                     <!--<input type="text" name="room_area" id="room_area" placeholder="0.00" class="form-control">-->
                    </div>
                    </div>
	</div>
	
	
                 <div class="row">
                    <div class="col-md-12">
                    <div class="form-group mb-2">
                        <label for="simpleinput">Descriptions</label>
                    </div>
                    <div class="form-group mb-10">
                    
                     <textarea class="form-control" name="descr" id="descr" class="form-control" rows="2"></textarea>
                    </div>
                    </div>
	                 </div>
