<?php
session_start();
require "includes/db_connect.php"; // Database connection
require "includes/nav_index.php"; // Database connection

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

$query = "SELECT jl.job_id, jl.title, jl.description, jl.requirements, jl.employment_type, jl.location, jl.salary_min, jl.salary_max, jl.currency, jl.expiry_date, c.companyName, jc.category_name 
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

// Fetch saved jobs for the logged-in user
$saved_jobs = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $saved_jobs_query = $conn->prepare("SELECT job_id FROM tbl_emp_saved_jobs WHERE user_id = ?");
    $saved_jobs_query->bind_param("i", $user_id);
    $saved_jobs_query->execute();
    $saved_jobs_result = $saved_jobs_query->get_result();
    while ($row = $saved_jobs_result->fetch_assoc()) {
        $saved_jobs[] = $row['job_id'];
    }
    $saved_jobs_query->close();
}

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

$conn->close();
?>
<!doctype html>
<html lang="en">
  <head>
    <title>Public Employment Service Office</title>
    <link rel="stylesheet" href="fortest/style2/custom-bs.css">
    <link rel="stylesheet" href="fortest/style2/jquery.fancybox.min.css">
    <link rel="stylesheet" href="fortest/style2/bootstrap-select.min.css">
    <link rel="stylesheet" href="fortest/fonts/icomoon/style.css">
    <link rel="stylesheet" href="fortest/fonts/line-icons/style.css">
    <link rel="stylesheet" href="fortest/style2/owl.carousel.min.css">
    <link rel="stylesheet" href="fortest/style2/animate.min.css">
    <link rel="stylesheet" href="fortest/style2/owl.theme.default.min.css">

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
      .save-job-btn {
        margin-top: 10px;
      }
      // Owl Carousel
      .owl-nav {
        position: absolute;
        top: 50%;
        width: 100%;
        display: flex;
        justify-content: space-between;
      }
      .owl-nav button {
        background: none;
        border: none;
        font-size: 2em;
        color: #333;
      }
      .owl-nav button.owl-prev {
        position: absolute;
        left: -25px;
      }
      .owl-nav button.owl-next {
        position: absolute;
        right: -25px;
      }
      .Announcement .container {
        text-align: center;
      }
      .Announcement .owl-carousel .slide {
        display: flex;
        justify-content: center;
        align-items: center;
        padding-bottom: 50px; /* Add padding below the images */
      }
          .logo-container {
        position: absolute;
        top: 70px; /* Adjust based on the height of your nav bar */
        left: 20px;
      }
      .logo-container img {
        width: 120px;
        height: auto;
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
    
    <div class="logo-container">
      <img src="fortest/images/spc_logo.png" alt="San Pablo Logo">
    </div>

    <!-- HOME -->
    <section class="home-section section-hero overlay bg-image" style="background-image: url('fortest/images/HOMEBG.jpg');" id="home-section">

      <div class="container">
        <div class="row align-items-center justify-content-center">
          <div class="col-md-12">
            <div class="mb-5 text-center">
            <img src="fortest/images/spc_logo.png" alt="PESO Logo" style="width: 120px; height: auto; margin-right: 10px;">
              <h1 class="text-white font-weight-bold">Public Employment Service Office</h1>
              <p>San Pablo City, Laguna.</p>
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
                  <button type="submit" class="btn btn-primary btn-lg btn-block btn-search"><span class="icon-search icon mr-2"></span>Search Job</button>
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
                <?php if (isset($_SESSION['user_id'])): ?>
                  <form method="post" action="includes/save_job_process.php" class="save-job-btn">
                    <input type="hidden" name="job_id" value="<?= $job['job_id'] ?>">
                    <?php if (in_array($job['job_id'], $saved_jobs)): ?>
                      <button type="submit" name="action" value="unsave" class="btn btn-outline-danger">Unsave</button>
                    <?php else: ?>
                      <button type="submit" name="action" value="save" class="btn btn-outline-primary">Save</button>
                    <?php endif; ?>
                  </form>
                <?php endif; ?>
              </div>
            </div>
          <?php endwhile; ?>
        </div>
      </div>
    </section>
    

    <!-- site stats -->
    <section class="py-5 bg-image overlay-primary fixed overlay" id="next" style="background-image: url('fortest/images/HOMEBG.jpg');">
      <div class="container" style="background-color: #6267FF">
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
    <div class="container text-center py-5 ">
        <h2 class="section-title text-white">Apply Process</h2>
        <h1 class="display-4 font-weight-bold mt-2 text-white">How it works</h1>
    </div>
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="https://storage.googleapis.com/a1aa/image/-B1FBR_Shx4RkCN2nVEy4ksyaapLS-I4yXDpLzecEc4.jpg" class="card-img-top mx-auto" alt="Icon representing job search" style="width: 64px; height: 64px;">
                    <div class="card-body text-black">
                        <h5 class="card-title">1. Search a job</h5>
                        <p class="card-text">Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod tempor incididunt ut laborea.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="https://storage.googleapis.com/a1aa/image/YIYYs-dEqsNdyJqJEfJLDAe1cON3DEbtamQpaM3pE04.jpg" class="card-img-top mx-auto" alt="Icon representing job application" style="width: 64px; height: 64px;">
                    <div class="card-body text-black">
                        <h5 class="card-title">2. Apply for job</h5>
                        <p class="card-text">Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod tempor incididunt ut laborea.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="https://storage.googleapis.com/a1aa/image/SPg_obwdygMCcsDWmQUBKKDLkSV4aF42IDTrT0c9R9g.jpg" class="card-img-top mx-auto" alt="Icon representing getting a job" style="width: 64px; height: 64px;">
                    <div class="card-body text-black">
                        <h5 class="card-title">3. Get your job</h5>
                        <p class="card-text">Sorem spsum dolor sit amsectetur adipisclit, seddo eiusmod tempor incididunt ut laborea.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    <section id="about" class="site-section py-4 flex justify-center items-center min-h-screen">
    <div class="container bg-light p-5 d-flex flex-column flex-md-row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <h1 class="display-4 font-weight-bold mb-3">ABOUT US</h1>
            <p class="text-muted mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing</p>
            <p class="text-muted mb-3">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <a href="https://owwamember.com/peso-public-employment-service-office/" target="_blank" class="btn btn-dark mb-3">Read More</a>
            <div class="d-flex">
                <a class="text-danger mr-3" href="#"><i class="fab fa-facebook-f"></i></a>
                <a class="text-danger mr-3" href="#"><i class="fab fa-twitter"></i></a>
                <a class="text-danger" href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="col-md-6">
            <img src="fortest/images/PESO_about1.png" class="img-fluid" alt="Peso logo">
        </div>
    </div>
    </section>
    <section id="Blogs" class="bg-light pt-5 Announcement">
      <div class="container">
        <h2>Our Blogs</h2>
        <div class="owl-carousel customer-logos">
          <div class="slide"><img src="https://image.freepik.com/free-vector/luxury-letter-e-logo-design_1017-8903.jpg"></div>
          <div class="slide"><img src="https://image.freepik.com/free-vector/3d-box-logo_1103-876.jpg"></div>
          <div class="slide"><img src="https://image.freepik.com/free-vector/blue-tech-logo_1103-822.jpg"></div>
          <div class="slide"><img src="https://image.freepik.com/free-vector/colors-curl-logo-template_23-2147536125.jpg"></div>
          <div class="slide"><img src="https://image.freepik.com/free-vector/abstract-cross-logo_23-2147536124.jpg"></div>
          <div class="slide"><img src="https://image.freepik.com/free-vector/football-logo-background_1195-244.jpg"></div>
          <div class="slide"><img src="https://image.freepik.com/free-vector/background-of-spots-halftone_1035-3847.jpg"></div>
        </div>
      </div>
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
    <script>
      $(document).ready(function(){
        $(".owl-carousel").owlCarousel({
          loop: true,
          margin: 10,
          nav: true,
          navText: ['<span class="icon-keyboard_arrow_left"></span>', '<span class="icon-keyboard_arrow_right"></span>'],
          autoplay: true, // Enable autoplay
          autoplayTimeout: 1000, // Set autoplay interval to 1 seconds
          autoplayHoverPause: true, // Pause on hover
          responsive: {
            0: {
              items: 1
            },
            600: {
              items: 3
            },
            1000: {
              items: 5
            }
          }
        });
      });
    </script>
  </body>
    </html>

