      
        
    <div class="row justify-content-center">
    <div class="col-12">
        <!-- Dashboard Header -->
        <div class="row align-items-center mb-4">
            <div class="col">
                <h3 style="cursor:pointer;" onclick="allInvopayment()">
                    <strong>Analytics</strong> & Visualization Dashboard
                </h3>
            </div>
        </div>

        <!-- Room & Tenant Info Card -->
        <div class="row mb-4 d-flex">
            <div class="col-12">
                <div class="card shadow-sm p-3 text-center">
                    <span id="room_and_tenant" class="fw-bold"></span>
                </div>
            </div>
        </div>
    </div>
</div>

         


           
           
           
			  

          <?php  include'paiddetailsInvo.php'; ?>
            <?php  include'invodetailsInvo.php'; ?>
            
        
             
            
           
            
           


          <!-- FIRST ROW: Two Cards -->
<div class="row g-4 mb-4">
    <!-- Card 1: Financial Statistics -->
   <div class="col-12 col-md-6 d-flex">
    <div class="card flex-fill w-100">
        <!-- Card Header -->
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="card-title mb-0">
                <span id="year_selected"></span> Montly Paid Invoice 
            </h5>
            <div class="card-actions">
                <div class="dropdown">
                    <a href="#" data-bs-toggle="dropdown">
                        <i class="align-middle" data-feather="more-horizontal"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body d-flex">
            <div class="align-self-center chart chart-lg w-100">
                <canvas id="chartjs-dashboard-bar" width="500" height="500"></canvas>
            </div>
        </div>
    </div>
</div>


    <!-- Card 2: Recent Invoice -->
    <div class="col-12 col-md-6 d-flex">
        <div class="card flex-fill w-100">
            <div class="card-header">
                <div class="float-end">
                    <form class="row g-2">
                        <div class="col-auto">
                            <select name="year_id" id="invo_year_id" class="form-control select2 form-select-sm bg-light border-0" onchange="mylang12(this.value)">
                                <option></option>
                                <?php
                                $year = 2020;
                                $current_year = date('Y');
                                while($year <= $current_year){
                                    echo "<option value='$year'".($year == $current_year ? " selected" : "").">$year</option>";
                                    $year++;
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-auto">
                            <input type="text" class="form-control form-control-sm bg-light rounded-2 border-0" style="width: 100px;" placeholder="Search..">
                        </div>
                    </form>
                </div>
                <h5 class="card-title mb-0" onclick="barInvoice(2025)">Recent Invoice</h5>
            </div>
            <div class="card-body pt-2 pb-3">
                <div class="chart chart-sm">
                    <canvas id="chartjs-dashboard-line" width="500" height="500"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

 <!-- old moddle -->

<div class="row g-4 mb-4">

    <!-- LEFT COLUMN (8) -->
    <div class="col-12 col-md-8 d-flex">

         

        <!-- Invoices & Payments Card -->
        <div class="card shadow eq-card flex-fill w-100" id="monthlOverview">

            <div class="card-header">
                <strong class="card-title">Invoices & Payments</strong>

                <div class="float-end d-flex" >

                    <!-- YEAR SELECT -->
                    <select name="year_id" id="year_id"
                            class="form-control select2"
                            onchange="mylang12(this.value)">
                        <option></option>
                        <?php
                        $year = 2020;
                        $current_year = date('Y');
                        while($year <= $current_year){
                            echo "<option value='$year'".($year == $current_year ? " selected" : "").">$year</option>";
                            $year++;
                        }
                        ?>
                    </select>

                    <!-- MONTH SELECT -->
                    <select name="month_Id" id="month_Id"
                            class="form-control select2">
                        <option></option>
                        <?php
                        for($i=1; $i<=12; $i++){
                            $month_value = str_pad($i,2,'0', STR_PAD_LEFT);
                            $selected = (date('m') == $month_value) ? "selected" : "";
                            echo "<option value='$month_value' $selected>" . date('F', mktime(0,0,0,$i,1)) . "</option>";
                        }
                        ?>
                    </select>

                </div>
            </div>

            <!-- <div class="card-body"> -->
                <!-- <div id="montly_overview"></div> -->
            <!-- </div> -->

			 <!-- Monthly pie Card -->

               <div class="card flex-fill w-100">
            <div class="card-header">
                <div class="card-actions float-end">
                    <div class="dropdown position-relative">
                        <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                            <i class="align-middle" data-feather="more-horizontal"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">Print</a>
                            <a class="dropdown-item" href="#">Download</a>
                        </div>
                    </div>
                </div>
                <h5 class="card-title mb-0">Financial Statistics</h5>
            </div>
            <div class="card-body d-flex">
                <div class="align-self-center w-100">
                    <div class="py-3">
                        <div class="chart chart-xs">
                            <canvas id="chartjs-dashboard-pie" ></canvas>
                        </div>
                    </div>
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <td><i class="fas fa-circle text-primary fa-fw"></i> Deposit</td>
                                <td class="text-end"><span id="deposit_data"></span></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-circle text-warning fa-fw"></i> Invoice</td>
                                <td class="text-end"><span id="invoice_data"></span></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-circle text-danger fa-fw"></i> Paid Invoice</td>
                                <td class="text-end"><span id="paid_data"></span></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-circle text-dark fa-fw"></i> Unpaid balance</td>
                                <td class="text-end"><span id="unpaid_data"></span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
       
            
      

        <!-- </div> -->

        </div><!-- END card -->


		

    </div><!-- END col-md-8 -->


    <!-- RIGHT COLUMN (4) -->
    <div class="col-md-4">

        <div class="card shadow eq-card timeline">
            <div class="card-header">
                <strong class="card-title">Clients Don't Pay (Unpaid Balance)</strong>
            </div>

            <div class="card-body" data-simplebar style="height: 360px; overflow-y:auto;">

                      <?php
                
                			$date_year = date('Y');
                             $sql_us11 = $db->prepare("SELECT *,SUM(pay_amount) as sm FROM `tbl_payment`
                             INNER JOIN tbl_tenants ON tbl_tenants.ten_ref_no =tbl_payment.pay_ref_no
                             INNER JOIN tbl_assign ON tbl_assign.tent_id=tbl_tenants.ten_id
                             WHERE tbl_tenants.ten_staff = $company AND tbl_payment.status!=0 
                             and tbl_tenants.ten_id not in(SELECT tent_id FROM tbl_assign where assgn_status=1) group by pay_ref_no order by sm asc "); 
                            $sql_us11->execute();
                            // $count = $sql_us11->rowCount();
                            $differTotal=0;
                            if($sql_us11->rowCount() > 0){
                              $a = 0;
                            while($fetch_us11 = $sql_us11->fetch()){
                                
                                $sql_us134 = $db->prepare("SELECT SUM(invoice_fees) as sm FROM `tbl_invoice` WHERE inv_ref_no= '".$fetch_us11['pay_ref_no']."'");
                                $sql_us134->execute();
                                $fetch_us134 = $sql_us134->fetch();
                                
                                // $sql_usRoom = $db->prepare("SELECT room_id FROM tbl_assign where assgn_status=1 and tent_id='".$fetch_us11['pay_ref_no']."'");
                                // $sql_usRoom->execute();
                                // $fetch_usRoom = $sql_usRoom->fetch();
                                $differ=$fetch_us134['sm']-$fetch_us11['sm'];
                                if($differ>0){
                                    $differTotal=$differTotal+$differ;
                            $a++; 
                        ?>
                      <div class="pb-3 timeline-item" style="cursor: pointer;" title="show details" data-toggle="modal" data-target="#modal-paydetails" onClick="selectclient_details('<?php  echo $fetch_us11['ten_ref_no'] ?>')">
                          <span class="circle circle-sm bg-warning h3 mb-0"><?php echo $a; ?></span>
                        <!--<div class="pl-5">-->
                          
                          
                          <div  class="mb-1 small"><strong><?php echo $fetch_us11['te_names']; ?></strong><span class="text-muted mx-2"> 
                            
                              <p class="small text-muted">Invoiced: <span class="badge badge-light"><?php echo number_format($fetch_us134['sm']); ?></span>
                          </p>
                             Payment: </span><strong><?php echo number_format($fetch_us11['sm']); ?></strong></div>
                          
                           <p class="small text-muted">Balance: <span class="text-danger"><?php echo number_format($differ); ?></span>
                          </p>
                        <!--</div>-->
                      </div>
                     
                      <?php
					   }
                      }
					}
                      ?>
                         <div class="pb-3 timeline-item" >
                          
                        <!--<div class="pl-5">-->
                          
                          
                         <p class="small text-muted" align="right">Total: <span class="text-danger"><?php echo number_format($differTotal); ?></span>
                          </p>
				</div>

            </div>
        </div>

    </div><!-- END col-md-4 -->

</div><!-- END row -->

<!-- SECOND ROW: Full Width Column Chart -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
             <div class="card-header">
                <h5 class="card-title">Rent Overview based on Invoice, Paid Invoice, and Balance</h5>
                <h6 class="card-subtitle text-muted">
                    This column chart shows invoices, paid invoices, and their remaining balance per month.
                    It helps visualize rent payments and outstanding amounts.
                </h6>
            </div>
            <div class="card-body">
                <div class="chart w-100">
                    <div id="apexcharts-column"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
        <div class="col-12">
        <!-- <div class="card"> -->
         <div class="col text-center">
            <span id="all_invo_pay"></span>
         </div>
        </div>
         <!-- </div> -->
</div>

          
          </div> <!-- .container-fluid -->

          
        
    

<script>
$(document).ready(function () {

roomAndTenant();


    });
function mylang12(data12){
		

		barPayment(data12);
         barInvoice(data12);
         barInvoicePayBalance(data12);
		const ajaxreq =new XMLHttpRequest();
		ajaxreq.open('GET','checkajax?monthvalue='+data12,'TRUE');
		ajaxreq.send();
		ajaxreq.onreadystatechange= function(){

			if (ajaxreq.readyState== 4 && ajaxreq.status == 200) {

				document.getElementById('month_Id').
				innerHTML = ajaxreq.responseText;


			}
		}

	}
    $(document).ready(function () {
		var year_id = $('#year_id').val();
        var company = <?php echo $company ?>;
		var montly =  $("#month_Id").val();
		 monthlyOverView(year_id,montly,company);
		 pieData(year_id,montly,company);
		barPayment(year_id);
        allInvopayment();
        barInvoice(year_id);
        barInvoicePayBalance(year_id);


  $("#month_Id").change(function () {
        var year_id = $('#year_id').val();
        var company = <?php echo $company ?>;
		 monthlyOverView(year_id,$(this).val(),company);
		 pieData(year_id,$(this).val(),company);
        
		
		 
    });

});
</script>
<script>
function monthlyOverView(year_id, month_id,company) {

    // SHOW LOADER
    $("#montly_overview").html(
        '<div class="text-center py-3">' +
        '<img src="../images/ajax_loader.gif" width="35" height="35">' +
        '</div>'
    );

    $.ajax({
        url: "load-inv_pay-mnthly.php",
        type: "GET",
        data: {
            month_Id: month_id,
            year_id: year_id,
            company: company
        },
        success: function (data) {
            $("#montly_overview").html(data);
        },
        error: function (xhr, status, error) {
            $("#montly_overview").html(
                '<div class="alert alert-danger">Failed to load data!</div>'
            );
            console.error("AJAX Error:", error);
        }
    });
}


 function selectclient_details(val1) {
    //alert(val1);

     $('#fetching').html("<img src='../images/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.get('clientdetailUS.php?ten=' + val1 , function (data22) {
      
         $('#datacomes').html(data22)
          $('#fetching').html("#" + val1).fadeIn('fast');
        
            });
}
function roomAndTenant(){
	 $("#room_and_tenant").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();
  $("#monthlOverview").hide();
    $.ajax({
    type: "POST",
    url: "../statistics/dashboardController.php",
    data: { action: "room-count" },
    success: function(data) {

        $("#room_and_tenant")
            .html(data)
            .fadeIn();  
			 $("#monthlOverview").show("");// ✅ SHOW DIV


        // Or: $("#room_and_tenant")[0].hidden = false;
    },
    error: function(xhr, status, error) {
        $("#room_and_tenant").html('<span class="text-danger">Something went wrong</span>');
        console.error(error);
    }
});

}

function allInvopayment(){
	 $("#all_invo_pay").html('<img src="../images/ajax_loader.gif" alt="loading...." width="30" height="30" />').fadeIn();

    $.ajax({
        type: "POST",
        url: "../statistics/dashboardController.php",
        data: {action: "all-invo-pay" }, // action as string
        success: function(data) {
         
            $("#all_invo_pay").html(data).fadeIn();
            
        },
        error: function(xhr, status, error) {
            $("#all_invo_pay").html('<span class="text-danger">Something Went wrong</span>');
            console.error(error);
        }
    });

}
 function invoice_details(val1,val2) {
    $('#Statementdatacomes').html("");
    $('#ststype').html("#" + val2).fadeIn('fast');
     $('#fetching2').html("<img src='../images/ajax_loader.gif' width='15'>").fadeIn('fast');
        $.get('statementsDetails.php?month=' + val1 + '&type=' + val2 , function (data22) {
        $('#Statementdatacomes').html(data22)
        $('#fetching2').html("#" + val1).fadeIn('fast');
        
        
        
            });
            
    
}

</script>

           <script src="../js2/app.js"></script>

	<script>
		
	</script>
	<script>

function pieData(year_id, month_id, company) {

    $.ajax({
        url: "../statistics/dashboardController.php",
        type: "POST",
        data: {
            month_Id: month_id,
            year_id: year_id,
            company: company,
            action: "pie-chart"
        },
        dataType: "json",
        success: function (data) {

            // ✅ Build the pie chart using returned data
            updatePieChart(
                data.total_deposit,
                data.total_invoice,
                data.unpaid_balance,
                data.total_payment
            );
			$("#deposit_data").html(data.total_deposit).fadeIn();
			$("#invoice_data").html(data.total_invoice).fadeIn();
			$("#paid_data").html(data.total_payment).fadeIn();
			$("#unpaid_data").html(data.unpaid_balance).fadeIn();

        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });

}

var pieChart = null;

function updatePieChart(deposit, invoice, balance, payment) {

    if (pieChart !== null) {
        pieChart.destroy(); // ✅ Avoid duplicate charts
    }

    var ctx = document.getElementById("chartjs-dashboard-pie").getContext("2d");

    pieChart = new Chart(ctx, {
        type: "pie",
        data: {
            labels: ["Deposit", "Invoice", "Balance", "Payment"],
            datasets: [{
                data: [deposit, invoice, balance, payment],
                backgroundColor: [
                    window.theme.primary,
                    window.theme.warning,
                    window.theme.danger,
                    "#E8EAED"
                ],
                borderWidth: 5,
                borderColor: window.theme.white
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            cutoutPercentage: 70
        }
    });
}

function barPayment(year) {
	 $("#year_selected").html(year);
	
    $.ajax({
        url: "../statistics/dashboardController.php",
        type: "POST",
        data: {
            year: year,
            action: "bar-chart-pay"
        },
        dataType: "json",
        success: function (data) {
			
            updateBarChart(data.monthly_payments);
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
}


let barChartInstance = null;

function updateBarChart(monthlyPayments) {
    const ctx = document.getElementById("chartjs-dashboard-bar").getContext("2d");

    // Destroy existing chart if it exists
    if (barChartInstance) {
        barChartInstance.destroy();
    }

    barChartInstance = new Chart(ctx, {
        type: "bar",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: " Cummulative Payments",
                backgroundColor: window.theme.primary,
                borderColor: window.theme.primary,
                hoverBackgroundColor: window.theme.primary,
                hoverBorderColor: window.theme.primary,
                data: monthlyPayments,
                barPercentage: 0.75,
                categoryPercentage: 0.5
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        // Automatically adjust step size
                        callback: function(value) {
                            // Format large numbers with commas
                            return value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: { color: "transparent" }
                }
            }
        }
    });
}

function barInvoice(year) {
    $.ajax({
        url: "../statistics/dashboardController.php",
        type: "POST",
        data: {
            year: year,
            action: "bar-chart-invo"
        },
        dataType: "json",
        success: function (data) {
            updateInvoiveBarChart(data.monthly_invoice);
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
}

let barInvoChartInstance = null;

function updateInvoiveBarChart(monthlyPayments) {

    var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");

    var gradientLight = ctx.createLinearGradient(0, 0, 0, 225);
    gradientLight.addColorStop(0, "rgba(215, 227, 244, 1)");
    gradientLight.addColorStop(1, "rgba(215, 227, 244, 0)");

    var gradientDark = ctx.createLinearGradient(0, 0, 0, 225);
    gradientDark.addColorStop(0, "rgba(51, 66, 84, 1)");
    gradientDark.addColorStop(1, "rgba(51, 66, 84, 0)");

    // Destroy existing chart
    if (barInvoChartInstance) {
        barInvoChartInstance.destroy();
    }

    barInvoChartInstance = new Chart(ctx, {
        type: "line",
        data: {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            datasets: [{
                label: " Cummulative Invoice",
                fill: true,
                backgroundColor: window.theme.id === "light" ? gradientLight : gradientDark,
                borderColor: window.theme.primary,
                data: monthlyPayments 
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                filler: { propagate: false },
                legend: { display: false }
            },
            scales: {
                x: {
                    grid: { display: false }
                },
                y: {
                    ticks: { stepSize: 1000000 },
                    grid: { display: false }
                }
            }
        }
    });
}


function barInvoicePayBalance(year) {
    $.ajax({
        url: "../statistics/dashboardController.php",
        type: "POST",
        data: {
            year: year,
            action: "bar-chart-invoicePaymentBalance"
        },
        dataType: "json",
        success: function (data) {
           

            updateInvPayBalanceBarChart(
                data.invoice,
                data.payment,
                data.balance
            );

        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", error);
        }
    });
}

let barInvoPayBalanceChartInstance = null;

function updateInvPayBalanceBarChart(invoice, payment, balance) {

    const el = document.querySelector("#apexcharts-column");
    if (!el) {
        console.error("Chart container not found");
        return;
    }

    // Destroy previous chart
    if (barInvoPayBalanceChartInstance) {
        barInvoPayBalanceChartInstance.destroy();
    }

    var options = {
        chart: {
            height: 350,
            type: "bar",
        },
        plotOptions: {
            bar: {
                horizontal: false,
                endingShape: "rounded",
                columnWidth: "55%",
            },
        },
        dataLabels: { enabled: false },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"]
        },

        // ✅ DYNAMIC DATA
        series: [
            {
                name: "Invoice (Frw)",
                data: invoice
            },
            {
                name: "Payment (Frw)",
                data: payment
            },
            {
                name: "Balance (Frw)",
                data: balance
            }
        ],

        // Month labels
        xaxis: {
            categories: [
                "Jan","Feb","Mar","Apr","May","Jun",
                "Jul","Aug","Sep","Oct","Nov","Dec"
            ],
        },

        yaxis: {
            title: { text: "Total Amount (Frw)" }
        },

        fill: { opacity: 1 },

        tooltip: {
            y: {
                formatter: function(v) {
                    return "Total " + v + " Frw";
                }
            }
        }
    };

    // render new chart
    barInvoPayBalanceChartInstance = new ApexCharts(el, options);
    barInvoPayBalanceChartInstance.render();
}


		
	</script>
	
	
	
	
	

