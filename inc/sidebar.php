<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
  <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3" data-toggle="toggle">
    <i class="fe fe-x"></i>
  </a>

  <nav class="vertnav navbar navbar-light">

    <!-- Logo -->
    <div class="w-100 mb-3 d-flex">
      <a class="navbar-brand mx-auto mt-2 text-center" href="?file=dashboard">
        <img src="../img/xode.png" width="30%">
      </a>
    </div>

    <h6 class="text-center mb-3">
      <small>(<?= htmlspecialchars($fname); ?>)</small>
    </h6>

    <?php if ($role == '2') { ?>

    <ul class="navbar-nav flex-fill w-100">

      <!-- Dashboard -->
      <li class="nav-item">
        <a class="nav-link" href="home">
          <i class="fe fe-home fe-16"></i>
          <span class="ml-3">Dashboard</span>
        </a>
      </li>

      <!-- client -->
        <!-- Statements -->
      <li class="nav-item">
        <a class="nav-link" href="stakeholders">
          <i class="fe fe-users fe-16"></i>
          <span class="ml-3">Stakeholders</span> 
        </a>
      </li>

      <!-- General Settings -->
      <li class="nav-item dropdown">
        <a href="#generalSettings" data-toggle="collapse" class="dropdown-toggle nav-link">
          <i class="fe fe-credit-card fe-16"></i>
          <span class="ml-3">General Settings</span>
        </a>
        <ul class="collapse list-unstyled pl-4" id="generalSettings">
          <li class="nav-item">
            <a class="nav-link" href="Wallets">Wallets Type</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="wallet-account">Wallets Accounts</a>
          </li>
          
           <li class="nav-item">
            <a class="nav-link" href="initialization">Reconciliation init </a>
          </li>
        </ul>
      </li>

      <!-- Financial Settings -->
      <li class="nav-item dropdown">
        <a href="#financialSettings" data-toggle="collapse" class="dropdown-toggle nav-link">
          <i class="fe fe-archive fe-16"></i>
          <span class="ml-3">Financial Settings</span>
        </a>
        <ul class="collapse list-unstyled pl-4" id="financialSettings">
          <li class="nav-item">
            <a class="nav-link" href="income-type">Income Type</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="expense-type">Expense Type</a>
          </li>
        </ul>
      </li>

      <!-- Debit / Credit -->
      <li class="nav-item dropdown">
        <a href="#debitCredit" data-toggle="collapse" class="dropdown-toggle nav-link">
          <i class="fe fe-briefcase fe-16"></i>
          <span class="ml-3">Debit & Credit A/c</span>
        </a>
        <ul class="collapse list-unstyled pl-4" id="debitCredit">
          <li class="nav-item">
            <a class="nav-link" href="credited">Account Credited</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="debited">Account Debited</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="reconciliation">Reconciliation & Process</a>
          </li>
       
        </ul>
      </li>

      <!-- Statements -->
      <li class="nav-item">
        <a class="nav-link" href="statements">
          <i class="fe fe-file fe-16"></i>
          <span class="ml-3">Financial Statements</span>
        </a>
      </li>

      <!-- Services -->
      <li class="nav-item dropdown">
        <a href="#services" data-toggle="collapse" class="dropdown-toggle nav-link">
          <i class="fe fe-clock fe-16"></i>
          <span class="ml-3">Service Tracking</span>
        </a>
        <ul class="collapse list-unstyled pl-4" id="services">
          <li class="nav-item">
            <a class="nav-link" href="rental">Rental</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="utility">Utilities</a>
          </li>
        </ul>
      </li>

      <!-- Reports -->
      <li class="nav-item dropdown">
        <a href="#reports" data-toggle="collapse" class="dropdown-toggle nav-link">
          <i class="fe fe-bar-chart fe-16"></i>
          <span class="ml-3">Reports & Analytics</span>
        </a>
        <ul class="collapse list-unstyled pl-4" id="reports">
          <li class="nav-item"><a class="nav-link" href="statistics">Statistics</a></li>
          <li class="nav-item"><a class="nav-link" href="audit-history">Audit History</a></li>
          <li class="nav-item"><a class="nav-link" href="journal">Journals</a></li>
          <li class="nav-item"><a class="nav-link" href="balance-sheet">Balance Sheet</a></li>
          <li class="nav-item"><a class="nav-link" href="cash-book-sheet">Cash Book</a></li>
        </ul>
      </li>

      <!-- Logout -->
      <li class="nav-item">
        <a class="nav-link text-danger" href="../logout">
          <i class="fe fe-power fe-16"></i>
          <span class="ml-3">Logout</span>
        </a>
      </li>

    </ul>
    <?php } ?>

  </nav>
</aside>
