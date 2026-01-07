<?php include'../dao/data.php';?>
  
									    
								
								<?php
									
    if(isset($_GET['disvalue'])){
    	$a=$_REQUEST['disvalue'];
                                $sql1 = "SELECT * FROM districts where provincecode=$a and districtcode in (select district_id from tbl_con_sites group by district_id)";
                					 $stmt1 = $conn->query($sql1);
                					$data_location1 = $stmt1->rowCount();
                					if($data_location1>0){?>
                                <?php
                                
                					while ($row1 = $stmt1->fetch()) { ?>
                					
                                    <option value="<?php echo $row1['districtcode'];?>">
                                    <?php echo $row1['namedistrict'];?>
                                     </option>
                                     <?php  
                					}}
                                        }
			
			else if(isset($_GET['district'])){
			     $a=$_GET['district'];
        $sql= "SELECT * FROM sectors WHERE districtcode=$a";
			 	$stmt = $conn->query($sql);
			$data_location4 = $stmt->rowCount();
			//$id=0;
			if($data_location4>0){?>
		
        <?php
        
			while ($row = $stmt->fetch()) { ?>
			
            <option value="<?php echo $row['sectorcode'];?>">
            <?php echo $row['namesector'];?>
             </option>
             <?php 
			    
			 
			}   
		
			
			}
			    
			    
			    
			}
			else if(isset($_GET['secvalue'])){
			    
			    	     $a=$_GET['secvalue'];
        $sql= "SELECT * FROM sectors WHERE districtcode=$a";
			 	$stmt = $conn->query($sql);
			$data_location4 = $stmt->rowCount();
			//$id=0;
			if($data_location4>0){?>
		
        <?php
        
			while ($row = $stmt->fetch()) { ?>
			
            <option value="<?php echo $row['sectorcode'];?>">
            <?php echo $row['namesector'];?>
             </option>
             <?php 
			    
			 
			}   
		
			
			}
			    
			    
			}
			else if(isset($_GET['cellvalue'])){
			    
			    	     $a=$_GET['cellvalue'];
        $sql= "SELECT * FROM cells WHERE sectorcode=$a";
			 	$stmt = $conn->query($sql);
			$data_location4 = $stmt->rowCount();
			//$id=0;
			if($data_location4>0){?>
		
        <?php
        
			while ($row = $stmt->fetch()) { ?>
			
            <option value="<?php echo $row['codecell'];?>">
            <?php echo $row['nameCell'];?>
             </option>
             <?php 
			    
			 
			}   
		
			
			}
			    
			    
			}
			
			else if(isset($_GET['villvalue'])){
			    
			    	$a=$_GET['villvalue'];
        $sql= "SELECT * FROM vilages WHERE codecell=$a";
			 	$stmt = $conn->query($sql);
			$data_location4 = $stmt->rowCount();
			//$id=0;
			if($data_location4>0){?>
		
        <?php
        
			while ($row = $stmt->fetch()) { ?>
			
            <option value="<?php echo $row['CodeVillage'];?>">
            <?php echo $row['VillageName'];?>
             </option>
             <?php 
			    
			 
			}   
		
			
			}
			    
			    
			}
			
				else if(isset($_GET['cite'])){
			    
			    	    	     $a=$_GET['cite'];
        $sql= "SELECT * FROM tbl_cite";
			 	$stmt = $conn->query($sql);
			$data_location4 = $stmt->rowCount();
			//$id=0;
			if($data_location4>0){?>
			
			<option value="0">--Select Cite--</option>
        
		
        <?php
        
        
			while ($row = $stmt->fetch()) { ?>
			
            <option value="<?php echo $row['cite_id'];?>">
            <?php echo $row['cite_Name'];?>
             </option>
             <?php 
			    
			 
			}   
		
			
			}
			    
			    
			}
				else if(isset($_GET['sitevalue'])){
			    
			    	    	     $a=$_GET['sitevalue'];
        $sql= "SELECT * FROM tbl_sites
        INNER JOIN tbl_con_sites ON tbl_con_sites.site_id = tbl_sites.site_id
        WHERE tbl_con_sites.sector_id=$a";
			 	$stmt = $conn->query($sql);
			$data_location4 = $stmt->rowCount();
			//$id=0;
			if($data_location4>0){?>
			
			<option value="0">--Select Cite--</option>
        
		
        <?php
        
        
			while ($row = $stmt->fetch()) { ?>
			
            <option value="<?php echo $row['site_id'];?>">
            <?php echo $row['site_name'];?>
             </option>
             <?php 
			    
			 
			}   
		
			
			}else{ ?> <option value="0">--No  Cite available--</option> <?php  }
			    
			    
			}
				else if(isset($_GET['intakvalue'])){
			    
			    	    	     $a=$_GET['intakvalue'];
        $sql= "SELECT * FROM tbl_intake
        INNER JOIN tbl_acad_cycle ON tbl_acad_cycle.acad_cycle_id =tbl_intake.acad_cycle_id WHERE tbl_intake.acad_cycle_id=$a";
			 	$stmt = $conn->query($sql);
			$data_location4 = $stmt->rowCount();
			//$id=0;
			if($data_location4>0){?>
			
			<option ></option>
        
		
        <?php
        
        
			while ($row = $stmt->fetch()) { ?>
			
            <option value="<?php echo $row['intake_id'];?>">
            <?php echo $row['intake_month']." ".$row['acad_year'];?>
             </option>
             <?php 
			    
			 
			}   
		
			
			}
			    
			    
			}
				else if(isset($_GET['projvalue'])){
			    
			    	    	     $a=$_GET['projvalue'];
			    	    	     
        $sql1= "SELECT * FROM tbl_intake WHERE intake_id=$a";
			 	$stmt1 = $conn->query($sql1);
			 	$rows = $stmt1->fetch();
			 	//$dat = DATE_FORMAT(project_start_date, '%Y-%m-%d')
			 	
			 	
        $sql= "SELECT * FROM tbl_project WHERE  DATE_FORMAT(project_start_date, '%Y-%m-%d') BETWEEN '".$rows['intake_start']."' AND '".$rows['intake_end']."'";
			 	$stmt = $conn->query($sql);
			$data_location4 = $stmt->rowCount();
			//$id=0;
			if($data_location4>0){?>
			
			<option ></option>
        
		
        <?php
        
        
			while ($row = $stmt->fetch()) { ?>
			
            <option value="<?php echo $row['project_id'];?>">
            <?php echo $row['project_name'];?>
             </option>
             <?php 
			    
			 
			}   
		
			
			}
			    
			    
			}
				else if(isset($_GET['monthvalue'])){
			    
			    	    	     $a=$_GET['monthvalue'];
			    	    	     
                            $i = 1;
    						$month = 12;
    						$date_month = date('m');
    						$date_year = date('Y');
    						//$date = date('Y-m-d');
    						?>
    					   <option></option>
    						<?php
    						if($date_year==$a){
    							while ($i <= $month)
    						{
    						$i = str_pad($i, 2, 0, STR_PAD_LEFT);
    							if($date_month==$i)
    								{
    								?>
    								<option value="<?php echo $date_month;?>" selected><?php echo date('F', mktime(0, 0, 0, $i, 1))?></option>
    								
    								<?php
    								break;
    								}
    								else
    								
    								{
    								?>
    								<option value="<?php echo $i;?>"><?php echo date('F', mktime(0, 0, 0, $i, 1))?></option>
    								
    								<?php
    								
    								}
    							$i++;				
    						}
			    
			    
			}else{ $i = str_pad($i, 2, 0, STR_PAD_LEFT); while ($i <=$month){ ?>
			    <option value="<?php echo $i;?>"><?php echo date('F', mktime(0, 0, 0, $i, 1))?></option>
    			   <?php 
			    $i++;
			}
			}}
			
		
                 ?>


