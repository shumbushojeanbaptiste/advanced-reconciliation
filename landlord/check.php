<?php 

       
    if(ISSET($_POST['insert11'])){
        $auto_code=$_POST['auto_code'];
        $cpid = $_POST['cpid'];
        $floor = $_POST['floor'];
        $rm_code = trim($_POST['rm_code']);
        $num_room=strlen($cmp_sn);
        $rmlen=strlen($rm_code);
        $max = $db->prepare("SELECT room_id,room_no FROM tbl_rooms where tbl_rooms.company_id = '".$company."'  ORDER BY room_id DESC LIMIT 1");
        $max->execute(array());
        
        // execute the query with parameter $code...
        $rowcount = $max->rowCount();
        
        if ($rowcount > 0)  // if there is data for that reg. number...
        {
        $row1 = $max->fetch(PDO::FETCH_ASSOC);
        // Verify the rooms...
        $room_no = $row1['room_no']; 
        
         
        }
        else{
           $room_no = $rm_code."0";  
        }
        $fromdb=strlen($room_no);
         $new = substr("$room_no",$rmlen, $fromdb);                            
            $new=$new +1; 
                        
            if($new <10)
              {
                $c="00"; 
              }
            
              elseif($new >=10 AND $new <100)
              {
                $c="0";
              }
              else{
                $c="" ; 
              }
             $room_num =$rm_code.$c.$new; 
             
         
        $sql_check = $db->prepare("SELECT * FROM tbl_rooms WHERE room_no = ?");
        try {
        
        $price = $_POST['price'];
        $descr = $_POST['descr'];
        $room_area = $_POST['room_area'];
        
        $sql_check->execute(array($room_num));
        $row_count_check = $sql_check->rowCount();
                if ($row_count_check >= 1)
                {
                $msge="This Room is Already Registered!";
                   
                }
                else{
                    
            //for user..
            $date = date("Y-m-d");
            $time = date("H:i:s");
            try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO `tbl_rooms` ( `room_no`, `price`,`descr`, `room_area`,`company_id`,`r_floor`, `rm_status`,`auto_code`) 
            VALUES ('$rm_code', '$price', '$descr', '$room_area', '$cpid','$floor', 1,'".$auto_code."')";
            $conn->exec($sql);
            }catch(PDOException $e){
                echo $e->getMessage();
            }

            $msg="Room with Number: <b>".$room_num."</b> has been registered Successfully!";
            echo '<meta http-equiv="refresh"'.'content="2;URL=?file=rooms">';
                }
            }catch(PDOException $e){
                echo $e->getMessage();
            }
         }
?>