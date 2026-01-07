<?php
// =======================
// SEND BILL VIA SMS
// =======================
if (isset($_POST['sendBill'])) {
    try {
        $li_qu_adminMM = $db->prepare("SELECT msg_char FROM tbl_company WHERE cpny_ID = :company");
        $li_qu_adminMM->execute([':company' => $company]);
        $fli_adminMM = $li_qu_adminMM->fetch(PDO::FETCH_ASSOC);

        if (!$fli_adminMM) throw new Exception("Company not found");

        $cp_name_char = $fli_adminMM['msg_char'];
        $link = trim($_POST['linktext']);
        $phone = trim($_POST['phone']);
        $period = trim($_POST['period']);
        $message = "Mukiriya wacu kureba fagitire ya Rent $period ,kanda hano $link";

        $data = [
            "sender" => $cp_name_char,
            "recipients" => $phone,
            "message" => $message,
        ];

        $url = "https://www.intouchsms.co.rw/api/sendsms/.json";
        $username = "twagiramungus";
        $password = "M00dle!!@@";

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_USERPWD => "$username:$password",
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS => http_build_query($data),
        ]);

        $result = curl_exec($ch);
        curl_close($ch);
        ?>
        <script>
            swal("Well done!", "Message Sent Successfully", "success");
        </script>
        <?php
    } catch (Exception $e) {
        echo "<script>swal('Error', '".addslashes($e->getMessage())."', 'error');</script>";
    }
}

// =======================
// UPDATE INVOICE
// =======================
if (isset($_POST['update_invoice'])) {
    try {
        $invoice_id = $_POST['invoice_id'];
        $amount = $_POST['invoice_amount'];
        $invoice_date = $_POST['invoice_date'];
        $invoice_comment = $_POST['invoice_comment'];
        $keyword = $_POST['keyword'];

        $sql = $conn->prepare("
            UPDATE tbl_invoice 
            SET invoice_fees = :amount,
                invoice_date = :invoice_date,
                inv_desc = :comment
            WHERE invoice_id = :invoice_id
        ");
        $sql->execute([
            ':amount' => $amount,
            ':invoice_date' => $invoice_date,
            ':comment' => $invoice_comment,
            ':invoice_id' => $invoice_id
        ]);

        $msg = "Invoice Updated Successfully";
        echo '<meta http-equiv="refresh" content="2;URL=index?file=change-invoice&keyword1='.$keyword.'">';
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// =======================
// CANCEL INVOICE
// =======================
if (isset($_POST['cancel'])) {
    try {
        $inv_id = $_POST['inv_id'];
        $slip = $_POST['invo_no'];
        $keyword = $_POST['keyword'];

        $sqld = $db->prepare("DELETE FROM tbl_invoice WHERE invoice_id = :inv_id");
        $sqld->execute([':inv_id' => $inv_id]);

        $msge = "$slip Invoice Successfully Cancelled";
        echo '<meta http-equiv="refresh" content="2;URL=index?file=change-invoice&keyword1='.$keyword.'">';
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>

<style>
.icon-rtl {
  border-radius: 20px;
  border: 1px solid #2d9fd9;
  color: #2d9fd9;
  width: 100%;
  height: 40px;
  padding-left: 15px;
  background: url("../images/search.png") no-repeat right 10px center;
  background-size: 20px;
}
.icon-rtl:focus {
  outline: none;
  border-color: #a0d18c;
  color: #2d9fd9;
}
</style>

<div class="row">
    <?php if (!empty($msg)): ?>
        <div class="alert alert-success alert-dismissible fade show w-100" role="alert">
            <strong>Well done!</strong> <?= $msg ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php elseif (!empty($msge)): ?>
        <div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
            <span class="fe fe-minus-circle fe-16 mr-2"></span> <?= $msge ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <div class="col-md-12 mt-4">
      <div class="card shadow mb-4">
        <div class="card-body">
          <div class="form-row">
            <div class="col-12">
              <input type="text" class="form-control icon-rtl" id="search-box" placeholder=" Search by Tenant Name ,Phone or shop">
            </div>
          </div>
          <div class="form-row mt-2">
            <div class="col-12" id="suggesstion-box"></div>
          </div>

          <br>
          <div id="resultedOK">
          
          </div>
        </div>
      </div>
    </div>
</div>



<script>
$(function() {
  $("#search-box").keyup(function() {
    $.ajax({
      type: "POST",
      url: "load_slip_search_result2",
      data: { keyword1: $(this).val() },
      success: function(data) {
        $("#suggesstion-box").show().html(data);
        $("#resultedOK").hide();
      }
    });
  });
});
function selecttbl_admission2(val) {
   

    // Remove existing suggestion box if any
  $("#suggesstion-box").hide();
  $("#resultedOK").show();


   $("#resultedOK").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />');
    $.ajax({
        type: "POST",
        url: "../landlord/depositorInfo.php",
        data: { keyword1 : val }, 
        success: function(data) {
           
            $("#resultedOK").html(data);
           
        }
    });
}

</script>
