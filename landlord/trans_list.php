<?php
   
  //Check Company
   $stmt = $db->prepare("SELECT * FROM tbl_admins INNER JOIN tbl_users_login ON tbl_admins.adm_id=tbl_users_login.profile_id  WHERE  tbl_users_login.role_id = '2' AND tbl_users_login.profile_id = ?");
   $stmt->execute(array($profile));
   $rows = $stmt->fetch();
   $comp = $rows['company_id'];
   
   
    $stmt = $db->query('SELECT *FROM tbl_company WHERE cpny_ID="'.$comp.'" ORDER BY cpny_ID ASC');
    try
    {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $cmp_ID =$row['cpny_ID'];
            $short =$row['short_name'];
            
             }
        }
        catch (PDOException $ex) {
        //Something went wrong rollback!
        echo $ex->getMessage();
     }
    
    // fetch 
    $sql = $db->prepare("SELECT * FROM tbl_assign 
    INNER JOIN tbl_rooms ON tbl_assign.room_id=tbl_rooms.room_id
    INNER JOIN tbl_tenants ON tbl_assign.tent_id=tbl_tenants.ten_id
    WHERE tbl_rooms.company_id='".$cmp_ID."'");
    $sql->execute();
    
     while($fetch = $sql->fetch()) {
         //date
        $date = date('Y-m-d');
        $date_time = date('Y-m-d H:i:s');
        $yes = "yes";
        
        //Invoice No.
        $year =  date('Y');
        $year = substr( $year, -2);
        $li_qu = $db->prepare("SELECT * FROM tbl_invoice ORDER BY invoice_id DESC LIMIT 1");
        $li_qu->execute();
        $fli = $li_qu->fetch();
        $li = str_pad($fli['invoice_id'], 6, "0", STR_PAD_LEFT);
        $invoice = $year.'-'.$li;
        #########################################
        
        //Check dateTime
        $inv_date = date("Y-m-d", strtotime("+1 month", strtotime($fetch['rent_date'])));
        $f_days = date('Y-m-d', strtotime('-5 days', strtotime($date)));
        $due_day =  date("d", strtotime('-5 days', strtotime($fetch['rent_date'])));
        $today =  date("d", strtotime($date));
        $due_month =  date("m", strtotime('-5 days', strtotime($fetch['rent_date'])));
        $to_month =  date("m", strtotime($date));
        #########################################
        
        // echo $to_month;
        
        // if($today >= $due_day and $due_month !=$to_month){
        if($today >= $due_day){
             try
          {
            
        // $check_inv = $db->prepare("SELECT * FROM tbl_invoice WHERE inv_ref_no='".$fetch['ten_ref_no']."' AND auto='".$yes."' AND invoice_date != '$date' ORDER BY invoice_id DESC LIMIT 1");
        // $check_inv->execute();
        
        // if($check_inv->rowCount() == 0) {

            $inv_insert_qu = $db->prepare("INSERT into tbl_invoice(`invoice`, `invoice_fees`, `inv_desc`, `invoice_date`, `inv_ref_no`, `cpny_ID`, `auto`,`inv_date`)
            VALUES ('".$invoice."', '".$fetch['amount']."', 'rent', '$date', '".$fetch['ten_ref_no']."', '$cmp_ID', 'yes', '$date_time')");
            $inv_insert_qu->execute(); 
            
        // }// Count()
         
         }
        catch (PDOException $ex) {
        //Something went wrong rollback!
        echo $ex->getMessage();
       }
            
        }//If End
        
     }//While End
    
?>