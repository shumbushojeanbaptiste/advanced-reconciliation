<?php include 'hd2.php';?>
 <style>
  .info-img {
    width: 100%;
    max-width: 220px;
    border-radius: 10px;
  }

  .animated-img {
    animation: slideIn 1.2s ease;
  }

  @keyframes slideIn {
    from { opacity: 0; transform: translateX(40px); }
    to { opacity: 1; transform: translateX(0); }
  }

  .fade-in {
    animation: fadeIn 1.5s ease;
  }

  @keyframes fadeIn {
    from { opacity:0 }
    to { opacity:1 }
  }

  @media (max-width: 768px) {
    .info-img { margin-top: 20px; }
  }
</style>

<main role="main" class="main-content">
  <div class="container-fluid">
    <div class="row justify-content-center fade-in">
      <div class="col-12">
        <div class="card shadow-sm p-4">

        <!-- TITLE -->
        <div class="text-center mb-4">
          <h2 class="h3 font-weight-bold">Welcome to Building Financial Management System</h2>
          <p class="text-muted">
            A complete solution to manage income, expenses, and account reconciliation
            for your building — accurately, transparently, and in real time.
          </p>
        </div>

        <!-- INTRO -->
        <section class="mb-5 row align-items-center">
          <div class="col-md-7">
            <h4><strong>About the System</strong></h4>
            <p class="text-muted">
              This financial system is designed to help building owners and managers
              track all sources of income and expenses, reconcile bank transactions,
              and generate reliable financial reports.
            </p>
            <p class="text-muted">
              Whether your building earns from rent, parking, utilities, or services,
              all transactions are recorded, categorized, and reconciled automatically.
            </p>
          </div>
          <div class="col-md-5 text-center">
            <img src="images/download.png" class="info-img animated-img">
          </div>
        </section>

        <!-- CORE MODULES -->
        <section class="mb-5 row align-items-center">
          <div class="col-md-7">
            <h4><strong>Core Financial Modules</strong></h4>
            <ul class="text-muted">
              <li><strong>Income Management</strong> – Rent, parking, utilities, services</li>
              <li><strong>Expense Management</strong> – Maintenance, salaries, utilities, taxes</li>
              <li><strong>Bank Transactions</strong> – Import bank statements (CSV)</li>
              <li><strong>Account Reconciliation</strong> – Match system records with bank data</li>
              <li><strong>Chart of Accounts</strong> – Debit & Credit account structure</li>
              <li><strong>Audit Trail</strong> – Track all financial changes</li>
            </ul>
          </div>
          <div class="col-md-5 text-center">
           
          </div>
        </section>

        <!-- HOW IT WORKS -->
        <section class="mb-5 row align-items-center">
          <div class="col-md-7">
            <h4><strong>How the Financial Flow Works</strong></h4>
            <ol class="text-muted">
              <li>Record income and expense transactions.</li>
              <li>Import bank statements (CSV or direct feed).</li>
              <li>System matches transactions by amount, date, and reference.</li>
              <li>Unmatched records are flagged for review.</li>
              <li>Approve reconciliation to finalize balances.</li>
              <li>Generate accurate financial reports.</li>
            </ol>
          </div>
          <div class="col-md-5 text-center">
            <img src="images/icons.png" class="info-img animated-img">
          </div>
        </section>

        <!-- RECONCILIATION -->
        <section class="mb-5">
          <h4><strong>Account Reconciliation</strong></h4>
          <p class="text-muted">
            Reconciliation ensures that your internal records match actual bank activity.
            This prevents errors, fraud, and missing transactions.
          </p>
          <ul class="text-muted">
            <li>Automatic matching using transaction codes and amounts</li>
            <li>Manual approval for exceptions</li>
            <li>Status tracking: Pending, Reconciled, Adjusted</li>
            <li>Clear reconciliation history for audits</li>
          </ul>
        </section>

        <!-- REPORTS -->
        <section class="mb-5 row align-items-center">
          <div class="col-md-8 text-right">
            <img src="images/down2.png" class="info-img animated-img">
          </div>
          <div class="col-md-4">
            <h4><strong>Financial Reports</strong></h4>
            <ul class="text-muted">
              <li>Income vs Expense Summary</li>
              <li>Cash Book</li>
              <li>Profit & Loss Statement</li>
              <li>Balance Sheet</li>
              <li>Reconciliation Reports</li>
              <li>Audit & Adjustment Logs</li>
            </ul>
          </div>
        </section>

        <!-- BENEFITS -->
        <section class="mb-5">
          <h4><strong>Benefits</strong></h4>
          <ul class="text-muted">
            <li>Accurate financial records</li>
            <li>Reduced manual errors</li>
            <li>Transparent cash flow tracking</li>
            <li>Easy audits and compliance</li>
            <li>Real-time financial visibility</li>
          </ul>
        </section>

        <!-- CTA -->
        <section class="text-center mt-5 fade-in">
          <h4>Manage Your Building Finances with Confidence</h4>
          <p class="text-muted">Start recording, reconciling, and reporting today.</p>
          <a href="sign-on" class="btn btn-primary px-4">Go to Financial Dashboard</a>
        </section>

      </div>
     </div>
    </div>
  </div>
</main>



<?php include 'ft2.php';?>