<?php
require "db_connect.php"; // Database connection

// Fetch user name if logged in
$user_name = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_query = $conn->prepare("SELECT firstName FROM tbl_employee WHERE user_id = ?");
    $user_query->bind_param("i", $user_id);
    $user_query->execute();
    $user_result = $user_query->get_result();
    if ($user_result->num_rows > 0) {
        $user_name = $user_result->fetch_assoc()['firstName'];
    }
    $user_query->close();
}

?>
<!-- NAVBAR -->
<header class="site-navbar mt-3">
      <div class="container-fluid">
        <div class="row align-items-center">
        <div class="site-logo col-6 d-flex align-items-center">
            <a href="index.php" class="d-flex align-items-center text-decoration-none">
                <img src="fortest/images/peso_icons.png" alt="PESO Logo" style="width: 120px; height: auto; margin-right: 10px;">
                <div class="d-flex flex-column">
                    <span>PESO</span>
                    <span style="padding-left: 30px;">Job Hiring</span>
                </div>
            </a>
        </div>
          <nav class="mx-auto site-navigation">
            <ul class="site-menu js-clone-nav d-none d-xl-block ml-0 pl-0">
              <li><a href="index.html" class="nav-link active">Home</a></li>
              <li><a href="#about">About</a></li>
              <li class="has-children">
                <a href="job-listings.html">Job Listings</a>
                <ul class="dropdown">
                  <li><a href="employee/emp_job_list.php">Job Listings</a></li>
                  <li><a href="post-job.html">Post a Job</a></li>
                </ul>
              </li>
              <li class="has-children">
                <a href="services.html">Pages</a>
                <ul class="dropdown">
                  <li><a href="services.html">Services</a></li>
                  <li><a href="service-single.html">Service Single</a></li>
                  <li><a href="blog-single.html">Blog Single</a></li>
                  <li><a href="portfolio.html">Portfolio</a></li>
                  <li><a href="portfolio-single.html">Portfolio Single</a></li>
                  <li><a href="testimonials.html">Testimonials</a></li>
                  <li><a href="faq.html">Frequently Ask Questions</a></li>
                  <li><a href="gallery.html">Gallery</a></li>
                </ul>
              </li>
              
              <li><a href="contact.html">Contact</a></li>
              <?php if (isset($_SESSION['user_id'])): ?>
              <li class="d-lg-none has-children">
                <a href="#"><span class="icon-user"></span> <?= htmlspecialchars($user_name) ?></a>
                <ul class="dropdown">
                  <li><a href="employee/emp_dashboard.php">Profile</a></li>
                  <li><a href="employee/emp_saved_jobs.php">Saved</a></li>
                  <li><a href="includes/employee/emp_logout.php">Logout</a></li>
                </ul>
              </li>
              <?php else: ?>
              <li class="d-lg-none"><a href="company/comp_login.php"><span class="mr-2">+</span> Company Log In</a></li>
              <li class="d-lg-none"><a href="employee/emp_login.php">Log In</a></li>
              <?php endif; ?>
            </ul>
          </nav>
          
          <div class="right-cta-menu text-right d-flex align-items-center col-6">
            <div class="ml-auto">
              <?php if (isset($_SESSION['user_id'])): ?>
                <div class="dropdown d-none d-lg-inline-block">
                  <a href="#" class="btn btn-outline-white border-width-2 dropdown-toggle" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 icon-user"></span><?= htmlspecialchars($user_name) ?>
                  </a>
                  <div class="dropdown-menu" aria-labelledby="profileDropdown">
                    <a class="dropdown-item" href="employee/emp_dashboard.php">Profile</a>
                    <a class="dropdown-item" href="employee/emp_saved_jobs.php">Saved</a>
                    <a class="dropdown-item" href="includes/employee/emp_logout.php">Logout</a>
                  </div>
                </div>
              <?php else: ?>
              <a href="company/comp_login.php" class="btn btn-outline-white border-width-2 d-none d-lg-inline-block"><span class="mr-2 icon-add"></span>Company Log In</a>
              <a href="employee/emp_login.php" class="btn btn-primary border-width-2 d-none d-lg-inline-block"><span class="mr-2 icon-lock_outline"></span>Log In</a>
              <?php endif; ?>
            </div>
            <a href="#" class="site-menu-toggle js-menu-toggle d-inline-block d-xl-none mt-lg-2 ml-3"><span class="icon-menu h3 m-0 p-0 mt-2"></span></a>
          </div>

        </div>
      </div>
    </header>