<?php include 'hd2.php';?>
  <style>
  .info-img {
    width: 100%;
    max-width: 360px;
    border-radius: 10px;
  }

  /* Right-side fade animation */
  .animated-img {
    animation: slideIn 1.2s ease;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateX(40px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  /* Fade in for sections */
  .fade-in {
    animation: fadeIn 1.5s ease;
  }

  @keyframes fadeIn {
    0% {opacity:0}
    100% {opacity:1}
  }

  /* Mobile responsive */
  @media (max-width: 768px) {
    .info-img {
      margin-top: 20px;
    }
  }
</style>

    
<main role="main" class="main-content">
  <div class="container-fluid">
    <div class="row justify-content-center fade-in">
      <div class="col-12">
        <div class="card shadow-sm p-3">

        <!-- TITLE -->
        <div class="text-center mb-4">
          <h2 class="h3 font-weight-bold">Welcome to the Rental Management System</h2>
          <p class="text-muted">A smart, automated and secure solution for property owners, building managers, and tenants.</p>
        </div>

        <!-- INTRO SECTION -->
        <section class="mb-5 row align-items-center">
          <div class="col-md-7">
            <h4 class="mb-3"><strong>About the System</strong></h4>
            <p class="text-muted">
              Our Rental Management Software automates invoicing, payments, tenant communication,
              and service usage tracking (water, electricity, parking).  
              It simplifies work for building owners and provides transparency for tenants.
            </p>
          </div>
          <div class="col-md-5 text-center">
            <img src="images/smart-tenant-saas-pic-7.png" class="info-img animated-img">
          </div>
        </section>

        <!-- FEATURES -->
        <section class="mb-5 row align-items-center">
          <div class="col-md-7">
            <h4 class="mb-3"><strong>Key Features</strong></h4>
            <ul class="text-muted">
              <li>Automated monthly invoicing for tenants</li>
              <li>Track payments and outstanding balances</li>
              <li>Easy communication between tenants and building managers</li>
              <li>Online rent payment via COGEBANK, Visa, SPENN, MoMoPay & Airtel Money</li>
              <li>Management of additional services: Parking, water, electricity</li>
              <li>Detailed financial reports for building owners</li>
              <li>Tenant portal for checking paid & unpaid invoices</li>
            </ul>
          </div>
          <div class="col-md-5 text-center">
            <img src="images/statistics.png" class="info-img animated-img">
          </div>
        </section>

        <!-- HOW TO USE SYSTEM -->
        <section class="mb-5 row align-items-center">
          <div class="col-md-7">
            <h4 class="mb-3"><strong>How to Use the System</strong></h4>
            <ol class="text-muted">
              <li>Receive your Tenant Code from building manager.</li>
              <li>Visit the login page and enter the provided code.</li>
              <li>View your rent status (paid/unpaid/remaining balance).</li>
              <li>Choose your preferred payment method.</li>
              <li>Make payment and receive confirmation automatically.</li>
              <li>Access your invoices, receipts, and service usage history.</li>
            </ol>
          </div>
          <div class="col-md-5 text-center">
            <img src="images/statements.png" class="info-img animated-img">
          </div>
        </section>

        <!-- PAYMENT METHODS -->
        <section class="mb-5">
          <h4 class="mb-3"><strong>How to Make Payments</strong></h4>

          <ul class="nav nav-pills mb-3" id="pills-tab2" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#p-cogeb">CHIC Finance PLC</a></li>
            <li class="nav-item"><a class="nav-link " data-toggle="pill" href="#p-git">GT Bank</a></li>
            <li class="nav-item"><a class="nav-link " data-toggle="pill" href="#p-equity">Equity Bank</a></li>
            <li class="nav-item"><a class="nav-link " data-toggle="pill" href="#p-bk">Bank Of Kigali</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#p-visa">VISA / MasterCard</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#p-spenn">SPENN</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#p-momo">MoMo Pay</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#p-airtel">Airtel Money</a></li>
          </ul>

          <div class="tab-content text-muted">
            <div class="tab-pane fade show active" id="p-cogeb">
  <div class="row align-items-center">
      
      <div class="col-md-8">
        <h5><strong>CHIC PLC Branch Payment</strong></h5>
        <p>1. Open your invoice and find your unique payment code.</p>
        <p>2. Go to any CHIC PLC branch.</p>
        <p>3. Provide the payment code to cashier and pay.</p>
        <p>4. You will receive a confirmation message instantly.</p>
      </div>

      <div class="col-md-4 text-end">
        <img src="images/finance-landing-page.png" class="info-img animated-img" style="max-width:100%; height:auto;">
      </div>

  </div>
</div>

            <div class="tab-pane fade " id="p-git">
              <div class="row align-items-center">
      
             <div class="col-md-8">
              <h5><strong>GT Bank Branch Payment</strong></h5>
              <p>1. Open your invoice and find your unique payment code.</p>
              <p>2. Go to any CHIC PLC branch.</p>
              <p>3. Provide the payment code to cashier and pay.</p>
              <p>4. You will receive a confirmation message instantly.</p>
              </div>

      <div class="col-md-4 text-end">
        <img src="images/finance-landing-page.png" class="info-img animated-img" style="max-width:100%; height:auto;">
      </div>

  </div>
            </div>
            <div class="tab-pane fade " id="p-equity">
              <div class="row align-items-center">
      
             <div class="col-md-8">
              
              <h5><strong>Equity Branch Payment</strong></h5>
              <p>1. Open your invoice and find your unique payment code.</p>
              <p>2. Go to any CHIC PLC branch.</p>
              <p>3. Provide the payment code to cashier and pay.</p>
              <p>4. You will receive a confirmation message instantly.</p>
              </div>

      <div class="col-md-4 text-end">
        <img src="images/finance-landing-page.png" class="info-img animated-img" style="max-width:100%; height:auto;">
      </div>

  </div>
            </div>
            <div class="tab-pane fade " id="p-bk">
              <div class="row align-items-center">
      
             <div class="col-md-8">
              <h5><strong>BK Branch Payment</strong></h5>
              <p>1. Open your invoice and find your unique payment code.</p>
              <p>2. Go to any CHIC PLC branch.</p>
              <p>3. Provide the payment code to cashier and pay.</p>
              <p>4. You will receive a confirmation message instantly.</p>
              </div>

      <div class="col-md-4 text-end">
        <img src="images/BankofKigali-logo.png" class="info-img animated-img" style="max-width:100%; height:auto;">
      </div>

  </div>
            </div>

            <div class="tab-pane fade" id="p-visa">
              <div class="row align-items-center">
      
             <div class="col-md-8">
              <h5><strong>Visa / MasterCard</strong></h5>
              <p>1. Log in using your tenant code.</p>
              <p>2. Choose Card Payment.</p>
              <p>3. Enter Visa/MasterCard details.</p>
              <p>4. Confirm payment.</p>
              </div>

      <div class="col-md-4 text-end">
        <img src="images/finance-landing-page.png" class="info-img animated-img" style="max-width:100%; height:auto;">
      </div>

  </div>
            </div>

            <div class="tab-pane fade" id="p-spenn">
              <div class="row align-items-center">
      
             <div class="col-md-8">
              <h5><strong>SPENN</strong></h5>
              <p>1. Login to the portal.</p>
              <p>2. Choose SPENN payment.</p>
              <p>3. Enter registered SPENN number.</p>
              <p>4. Approve payment in the SPENN app.</p>
              </div>

      <div class="col-md-4 text-end">
        <img src="images/finance-landing-page.png" class="info-img animated-img" style="max-width:100%; height:auto;">
      </div>

  </div>
            </div>

            <div class="tab-pane fade" id="p-momo">
              <div class="row align-items-center">
      
             <div class="col-md-8">
              <h5><strong>MoMo Pay</strong></h5>
              <p>1. Choose MoMo Pay.</p>
              <p>2. Enter MTN phone number.</p>
              <p>3. Confirm payment on your device.</p>
              </div>

      <div class="col-md-4 text-end">
        <img src="images/momo_image.jpg" class="info-img animated-img" style="max-width:100%; height:auto;">
      </div>

  </div>
            </div>

            <div class="tab-pane fade" id="p-airtel">
              <div class="row align-items-center">
      
             <div class="col-md-8">
              <h5><strong>Airtel Money</strong></h5>
              <p>1. Select Airtel Money.</p>
              <p>2. Enter Airtel number and amount.</p>
              <p>3. Confirm payment on Airtel phone.</p>
              </div>

      <div class="col-md-4 text-end">
        <img src="images/finance-landing-page.png" class="info-img animated-img" style="max-width:100%; height:auto;">
      </div>

  </div>
            </div>
          </div>
        </section>

        <!-- PROPOSED RESULTS -->
         
        <section class="mb-5 row align-items-center">
       
           <div class="col-md-8 text-right">
            <img src="images/Group-4229-1.png" class="info-img animated-img">
          </div>
              <div class="col-md-4">
          <h4 class="mb-3"><strong>Expected Results & Benefits</strong></h4>
          <ul class="text-muted">
            <li>Reduced workload for building managers</li>
            <li>Faster invoicing and payments</li>
            <li>No manual balance tracking</li>
            <li>Automatic receipts & SMS updates</li>
            <li>Transparency for tenants</li>
            <li>Efficient management of services</li>
          </ul>
         </div>
        </section>

        <!-- CTA -->
        <section class="text-center mt-5 fade-in">
          <h4>Ready to Get Started?</h4>
          <p class="text-muted">Click below to sign in or request an account.</p>
          <a href="sign-on" class="btn btn-primary px-4">Proceed to Login</a>
        </section>

      </div>
     </div>
    </div>
  </div>
</main>



<?php include 'ft2.php';?>