<?php
require "includes/db_connect.php"; // Database connection

// Fetch counts from the database
$employee_count = $conn->query("SELECT COUNT(*) AS count FROM tbl_employee")->fetch_assoc()['count'];
$jobs_posted_count = $conn->query("SELECT COUNT(*) AS count FROM tbl_job_listing")->fetch_assoc()['count'];
$jobs_filled_count = $conn->query("SELECT COUNT(*) AS count FROM tbl_job_listing WHERE status = 'filled'")->fetch_assoc()['count'];
$companies_count = $conn->query("SELECT COUNT(*) AS count FROM tbl_company")->fetch_assoc()['count'];

// Fetch job categories from the database
$categories_result = $conn->query("SELECT category_id, category_name FROM tbl_job_category");
$categories = [];
while ($row = $categories_result->fetch_assoc()) {
    $categories[] = $row;
}

// Handle search
$search_title = isset($_POST['search_title']) ? $_POST['search_title'] : '';
$search_category = isset($_POST['search_category']) ? $_POST['search_category'] : [];
$search_type = isset($_POST['search_type']) ? $_POST['search_type'] : '';

$query = "SELECT jl.title, jl.description, jl.requirements, jl.employment_type, jl.location, jl.salary_min, jl.salary_max, jl.currency, jl.expiry_date, c.companyName, jc.category_name 
          FROM tbl_job_listing jl 
          JOIN tbl_company c ON jl.employer_id = c.company_id 
          JOIN tbl_job_category jc ON jl.category_id = jc.category_id 
          WHERE jl.status = 'active'";

if ($search_title) {
    $query .= " AND (jl.title LIKE '%$search_title%' OR c.companyName LIKE '%$search_title%')";
}

if (!empty($search_category)) {
    $category_ids = implode(',', array_map('intval', $search_category));
    $query .= " AND jl.category_id IN ($category_ids)";
}

if ($search_type) {
    $query .= " AND jl.employment_type = '$search_type'";
}

$jobs = $conn->query($query);

$conn->close();
?>
<!doctype html>
<html lang="en">
  <head>
    <title>PESO &mdash; Website Template by Colorlib</title>
    <link rel="stylesheet" href="fortest/style2/custom-bs.css">
    <link rel="stylesheet" href="fortest/style2/jquery.fancybox.min.css">
    <link rel="stylesheet" href="fortest/style2/bootstrap-select.min.css">
    <link rel="stylesheet" href="fortest/fonts/icomoon/style.css">
    <link rel="stylesheet" href="fortest/fonts/line-icons/style.css">
    <link rel="stylesheet" href="fortest/style2/owl.carousel.min.css">
    <link rel="stylesheet" href="fortest/style2/animate.min.css">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="fortest/style2/style.css">    
    <style>
      .job-box {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 5px;
        background-color: #f9f9f9;
      }
      .job-title {
        font-size: 1.5em;
        font-weight: bold;
      }
      .job-details {
        margin-top: 10px;
      }
    </style>
  </head>
  <body id="top">

  <div id="overlayer"></div>
  <div class="loader">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
    

<div class="site-wrap">

    <div class="site-mobile-menu site-navbar-target">
      <div class="site-mobile-menu-header">
        <div class="site-mobile-menu-close mt-3">
          <span class="icon-close2 js-menu-toggle"></span>
        </div>
      </div>
      <div class="site-mobile-menu-body"></div>
    </div> <!-- .site-mobile-menu -->
    

    <!-- NAVBAR -->
    <header class="site-navbar mt-3">
      <div class="container-fluid">
        <div class="row align-items-center">
          <div class="site-logo col-6"><a href="index.php">JobBoard</a></div>

          <nav class="mx-auto site-navigation">
            <ul class="site-menu js-clone-nav d-none d-xl-block ml-0 pl-0">
              <li><a href="index.html" class="nav-link active">Home</a></li>
              <li><a href="about.html">About</a></li>
              <li class="has-children">
                <a href="job-listings.html">Job Listings</a>
                <ul class="dropdown">
                  <li><a href="job-single.html">Job Single</a></li>
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
              <li><a href="blog.html">Blog</a></li>
              <li><a href="contact.html">Contact</a></li>
              <li class="d-lg-none"><a href="company/comp_login.php"><span class="mr-2">+</span> Company Log In</a></li>
              <li class="d-lg-none"><a href="employee/emp_login.php">Log In</a></li>
            </ul>
          </nav>
          
          <div class="right-cta-menu text-right d-flex aligin-items-center col-6">
            <div class="ml-auto">
              <a href="company/comp_login.php" class="btn btn-outline-white border-width-2 d-none d-lg-inline-block"><span class="mr-2 icon-add"></span>Company Log In</a>
              <a href="employee/emp_login.php" class="btn btn-primary border-width-2 d-none d-lg-inline-block"><span class="mr-2 icon-lock_outline"></span>Log In</a>
            </div>
            <a href="#" class="site-menu-toggle js-menu-toggle d-inline-block d-xl-none mt-lg-2 ml-3"><span class="icon-menu h3 m-0 p-0 mt-2"></span></a>
          </div>

        </div>
      </div>
    </header>

    <!-- HOME -->
    <section class="home-section section-hero overlay bg-image" style="background-image: url('fortest/images/hero_1.jpg');" id="home-section">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-12">
            <div class="mb-5 text-center">
              <h1 class="text-white font-weight-bold">The Easiest Way To Get Your Dream Job</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate est, consequuntur perferendis.</p>
            </div>
            <form method="post" class="search-jobs-form">
              <div class="row mb-5">
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                  <input type="text" class="form-control form-control-lg" name="search_title" placeholder="Job title, Company...">
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                  <select class="selectpicker" name="search_category[]" data-style="btn-white btn-lg" data-width="100%" data-live-search="true" title="Select Category" multiple>
                    <?php foreach ($categories as $category): ?>
                      <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                  <select class="selectpicker" name="search_type" data-style="btn-white btn-lg" data-width="100%" data-live-search="true" title="Select Job Type">
                    <option value="">All</option>
                    <option value="Part Time">Part Time</option>
                    <option value="Full Time">Full Time</option>
                    <option value="Contract">Contract</option>
                    <option value="Temporary">Temporary</option>
                    <option value="Internship">Internship</option>
                  </select>
                </div>
                <div class="col-12 col-sm-6 col-md-6 col-lg-3 mb-4 mb-lg-0">
                  <button type="submit" class="btn btn-primary btn-lg btn-block text-white btn-search"><span class="icon-search icon mr-2"></span>Search Job</button>
                </div>
              </div>
              
            </form>
          </div>
        </div>
      </div>

      <a href="#next" class="scroll-button smoothscroll">
        <span class=" icon-keyboard_arrow_down"></span>
      </a>

    </section>

    <!-- job preview -->
    <section class="site-section">
      <div class="container">
        <h2 class="text-center mb-4">Current Job Listings</h2>
        <div class="row" style="max-height: 400px; overflow-y: auto;">
          <?php while ($job = $jobs->fetch_assoc()): ?>
            <div class="col-md-6">
              <div class="job-box">
                <div class="job-title"><?= htmlspecialchars($job['title']) ?></div>
                <div class="job-details">
                  <p><strong>Company:</strong> <?= htmlspecialchars($job['companyName']) ?></p>
                  <p><strong>Description:</strong> <?= htmlspecialchars($job['description']) ?></p>
                  <p><strong>Requirements:</strong> <?= htmlspecialchars($job['requirements']) ?></p>
                  <p><strong>Employment Type:</strong> <?= htmlspecialchars($job['employment_type']) ?></p>
                  <p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
                  <p><strong>Salary:</strong> <?= htmlspecialchars($job['salary_min']) ?> - <?= htmlspecialchars($job['salary_max']) ?> <?= htmlspecialchars($job['currency']) ?></p>
                  <p><strong>Category:</strong> <?= htmlspecialchars($job['category_name']) ?></p>
                  <p><strong>Expiry Date:</strong> <?= htmlspecialchars($job['expiry_date']) ?></p>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </section>
    

    <!-- site stats -->
    <section class="py-5 bg-image overlay-primary fixed overlay" id="next" style="background-image: url('fortest/images/hero_1.jpg');">
      <div class="container">
        <div class="row mb-5 justify-content-center">
          <div class="col-md-7 text-center">
            <h2 class="section-title mb-2 text-white">JobBoard Site Stats</h2>
            <p class="lead text-white">Lorem ipsum dolor sit amet consectetur adipisicing elit. Expedita unde officiis recusandae sequi excepturi corrupti.</p>
          </div>
        </div>
        <div class="row pb-0 block__19738 section-counter">

          <div class="col-6 col-md-6 col-lg-3 mb-5 mb-lg-0">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <strong class="number" data-number="<?php echo $employee_count; ?>">0</strong>
            </div>
            <span class="caption">Applicants</span>
          </div>

          <div class="col-6 col-md-6 col-lg-3 mb-5 mb-lg-0">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <strong class="number" data-number="<?php echo $jobs_posted_count; ?>">0</strong>
            </div>
            <span class="caption">Jobs Posted</span>
          </div>

          <div class="col-6 col-md-6 col-lg-3 mb-5 mb-lg-0">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <strong class="number" data-number="<?php echo $jobs_filled_count; ?>">0</strong>
            </div>
            <span class="caption">Jobs Filled</span>
          </div>

          <div class="col-6 col-md-6 col-lg-3 mb-5 mb-lg-0">
            <div class="d-flex align-items-center justify-content-center mb-2">
              <strong class="number" data-number="<?php echo $companies_count; ?>">0</strong>
            </div>
            <span class="caption">Companies</span>
          </div>
            
            
        </div>
      </div>
    </section>
        
    

    <section class="site-section">
      
    </section>

    <section class="py-5 bg-image overlay-primary fixed overlay" style="background-image: url('fortest/images/hero_1.jpg');">
    <div class="container text-center py-5">
        <h2 class="section-title">Apply Process</h2>
        <h1 class="display-4 font-weight-bold mt-2">How it works</h1>
    </div>
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="https://storage.googleapis.com/a1aa/image/-B1FBR_Shx4RkCN2nVEy4ksyaapLS-I4yXDpLzecEc4.jpg" class="card-img-top mx-auto" alt="Icon representing job search" style="width: 64px; height: 64px;">
                    <div class="card-body">
                        <h5 class="card-title">1. Search a job</h5>
                        <p class="card-text">Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod tempor incididunt ut laborea.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="https://storage.googleapis.com/a1aa/image/YIYYs-dEqsNdyJqJEfJLDAe1cON3DEbtamQpaM3pE04.jpg" class="card-img-top mx-auto" alt="Icon representing job application" style="width: 64px; height: 64px;">
                    <div class="card-body">
                        <h5 class="card-title">2. Apply for job</h5>
                        <p class="card-text">Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod tempor incididunt ut laborea.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="https://storage.googleapis.com/a1aa/image/SPg_obwdygMCcsDWmQUBKKDLkSV4aF42IDTrT0c9R9g.jpg" class="card-img-top mx-auto" alt="Icon representing getting a job" style="width: 64px; height: 64px;">
                    <div class="card-body">
                        <h5 class="card-title">3. Get your job</h5>
                        <p class="card-text">Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod tempor incididunt ut laborea.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

    
    <section class="site-section py-4">
      
      
    </section>


    <section class="bg-light pt-5 testimony-full">
        
        

    </section>

    <section class="pt-5 bg-image overlay-primary fixed overlay" style="background-image: url('fortest/images/hero_1.jpg');">
      
    </section>
    
    <footer class="site-footer">

      <a href="#top" class="smoothscroll scroll-top">
        <span class="icon-keyboard_arrow_up"></span>
      </a>

      <div class="container">
        <div class="row mb-5">
          <div class="col-6 col-md-3 mb-4 mb-md-0">
            <h3>Search Trending</h3>
            <ul class="list-unstyled">
              <li><a href="#">Web Design</a></li>
              <li><a href="#">Graphic Design</a></li>
              <li><a href="#">Web Developers</a></li>
              <li><a href="#">Python</a></li>
              <li><a href="#">HTML5</a></li>
              <li><a href="#">CSS3</a></li>
            </ul>
          </div>
          <div class="col-6 col-md-3 mb-4 mb-md-0">
            <h3>Company</h3>
            <ul class="list-unstyled">
              <li><a href="#">About Us</a></li>
              <li><a href="#">Career</a></li>
              <li><a href="#">Blog</a></li>
              <li><a href="#">Resources</a></li>
            </ul>
          </div>
          <div class="col-6 col-md-3 mb-4 mb-md-0">
            <h3>Support</h3>
            <ul class="list-unstyled">
              <li><a href="#">Support</a></li>
              <li><a href="#">Privacy</a></li>
              <li><a href="#">Terms of Service</a></li>
            </ul>
          </div>
          <div class="col-6 col-md-3 mb-4 mb-md-0">
            <h3>Contact Us</h3>
            <div class="footer-social">
              <a href="#"><span class="icon-facebook"></span></a>
              <a href="#"><span class="icon-twitter"></span></a>
              <a href="#"><span class="icon-instagram"></span></a>
              <a href="#"><span class="icon-linkedin"></span></a>
            </div>
          </div>
        </div>

        <div class="row text-center">
          <div class="col-12">
            <p class="copyright"><small>
              <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart text-danger" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" >Colorlib</a>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></small></p>
          </div>
        </div>
      </div>
    </footer>
  
  </div>

    <!-- SCRIPTS -->
    <script src="fortest/js/jquery.min.js"></script>
    <script src="fortest/js/bootstrap.bundle.min.js"></script>
    <script src="fortest/js/isotope.pkgd.min.js"></script>
    <script src="fortest/js/stickyfill.min.js"></script>
    <script src="fortest/js/jquery.fancybox.min.js"></script>
    <script src="fortest/js/jquery.easing.1.3.js"></script>
    
    <script src="fortest/js/jquery.waypoints.min.js"></script>
    <script src="fortest/js/jquery.animateNumber.min.js"></script>
    <script src="fortest/js/owl.carousel.min.js"></script>
    
    <script src="fortest/js/bootstrap-select.min.js"></script>
    
    <script src="fortest/js/custom.js"></script>

     
  </body>
</html>