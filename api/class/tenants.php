<?php
    class Tenant{
        // Connection
        private $conn;
        // Table
        private $tenTable = "tbl_tenants";
        private $payTable = "tbl_payment";
        // Columns
        public $ten_id;
        public $ten_ref_no;
        public $te_names;
        public $tin_no;
        public $ten_phone;
        public $leave_stts;
        
        //PAY Column
        public $pay_id;
        public $trans_code;
        public $pay_ref_no;
        public $slip_no;
        public $pay_due_date;
        public $pay_amount;
        public $pay_description;
        public $pay_trm;
        public $status;
        public $c_value;
        public $api_bank;
        
        // Db connection
        public function __construct($db){
            $this->conn = $db;
        }
        // GET ALL
        public function getTenants(){
            $sqlQuery = "SELECT ten_id, ten_ref_no, te_names, tin_no, ten_phone, leave_stts FROM " . $this->tenTable . " WHERE ten_staff=9";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->execute();
            return $stmt;
        }
        // CREATE
        public function createPayment(){
            $sqlQuery = "INSERT INTO
                        ". $this->payTable ."
                    SET
                        trans_code = :trans_code, 
                        pay_ref_no = :pay_ref_no, 
                        bank_id = :bank_id,
                        slip_no = :slip_no, 
                        pay_due_date = :pay_due_date, 
                        rent_date = :rent_date, 
                        pay_amount = :pay_amount,
                        pay_description = :pay_description,
                        pay_trm = :pay_trm,
                        pay_user = :pay_user,
                        status = :status,
                        c_value = :c_value,
                        api_bank = :api_bank";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->trans_code=htmlspecialchars(strip_tags($this->trans_code));
            $this->pay_ref_no=htmlspecialchars(strip_tags($this->pay_ref_no));
            $this->bank_id=htmlspecialchars(strip_tags($this->bank_id));
            
            $this->slip_no=htmlspecialchars(strip_tags($this->slip_no));
            $this->pay_due_date=htmlspecialchars(strip_tags($this->pay_due_date));
            $this->rent_date=htmlspecialchars(strip_tags($this->rent_date));
            $this->pay_amount=htmlspecialchars(strip_tags($this->pay_amount));
            $this->pay_description=htmlspecialchars(strip_tags($this->pay_description));
            $this->pay_trm=htmlspecialchars(strip_tags($this->pay_trm));
            $this->pay_user=htmlspecialchars(strip_tags($this->pay_user));
            $this->status=htmlspecialchars(strip_tags($this->status));
            $this->c_value=htmlspecialchars(strip_tags($this->c_value));
            $this->api_bank=htmlspecialchars(strip_tags($this->api_bank));
        
            // bind data
            $stmt->bindParam(":trans_code", $this->trans_code);
            $stmt->bindParam(":pay_ref_no", $this->pay_ref_no);
            $stmt->bindParam(":bank_id", $this->bank_id);
            $stmt->bindParam(":slip_no", $this->slip_no);
            $stmt->bindParam(":pay_due_date", $this->pay_due_date);
            $stmt->bindParam(":rent_date", $this->rent_date);
            $stmt->bindParam(":pay_amount", $this->pay_amount);
            $stmt->bindParam(":pay_description", $this->pay_description);
            $stmt->bindParam(":pay_trm", $this->pay_trm);
            $stmt->bindParam(":pay_user", $this->pay_user);
            $stmt->bindParam(":status", $this->status);
            $stmt->bindParam(":c_value", $this->c_value);
            $stmt->bindParam(":api_bank", $this->api_bank);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        // READ single
        public function getSingleTenant(){
            $sqlQuery = "SELECT
                        ten_id, 
                        ten_ref_no, 
                        te_names, 
                        tin_no, 
                        ten_phone, 
                        leave_stts
                      FROM
                        ". $this->tenTable ."
                    WHERE 
                       ten_ref_no = ?
                    LIMIT 0,1";
                $stmt = $this->conn->prepare($sqlQuery);
                $stmt->bindParam(1, $this->id);
                $stmt->execute();
                $dataRow = $stmt->fetch(PDO::FETCH_ASSOC);
            
                
                 $sql2 = $this->conn->prepare("SELECT SUM(invoice_fees) AS totalinv FROM `tbl_invoice` WHERE inv_ref_no = '".$dataRow['ten_ref_no']."'");
                 $sql2->execute(array()); // execute the query with parameter $code...
                 if ( $rowcount = $sql2->rowCount() > 0)// if there is data for that payment number...
                    {
                     $row1 = $sql2->fetch(PDO::FETCH_ASSOC);
                     $totinv2 = $row1['totalinv'];
                    }
                     else{
                        $totinv2 = 0;
                     }
                $sql4 = $this->conn->prepare("SELECT SUM(pay_amount) AS totalpay FROM `tbl_payment` WHERE pay_ref_no = '".$dataRow['ten_ref_no']."' ");
                $sql4->execute(array()); // execute the query with parameter $code...
                    if($rowcount = $sql4->rowCount() > 0)// if there is data for that payment number...
                    {
                    $row3 = $sql4->fetch(PDO::FETCH_ASSOC);
                    $payment = $row3['totalpay'];
                     }
                     else{
                      $payment = 0;
                     }    
                     
                $Amount =  number_format($totinv2-$payment);  
                
                $this->ten_id = $dataRow['ten_id'];
                $this->ten_ref_no = $dataRow['ten_ref_no'];
                $this->te_names = $dataRow['te_names'];
                $this->tin_no = $dataRow['tin_no'];
                $this->ten_phone = $dataRow['ten_phone'];
                $this->leave_stts = $dataRow['leave_stts'];
                $this->Amount = $Amount;
            
        }        
       
    }
?>