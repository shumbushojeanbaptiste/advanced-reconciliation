      
        
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
                    <span id="income_and_expense" class="fw-bold"></span>
                </div>
            </div>
        </div>
    </div>
</div>

         


           
           
           
			  

            
        
             
            
           
            
           





    
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
	
	
	
	
	

