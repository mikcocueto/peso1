<?php
session_start();
require "includes/db_connect.php"; // Database connection
require "includes/nav_index.php"; // Database connection

// Fetch counts from the database
$employee_count = $conn->query("SELECT COUNT(*) AS count FROM tbl_emp_info")->fetch_assoc()['count'];
$jobs_posted_count = $conn->query("SELECT COUNT(*) AS count FROM tbl_job_listing")->fetch_assoc()['count'];
$jobs_filled_count = $conn->query("SELECT COUNT(*) AS count FROM tbl_job_listing WHERE status = 'filled'")->fetch_assoc()['count'];
$companies_count = $conn->query("SELECT COUNT(*) AS count FROM tbl_comp_info")->fetch_assoc()['count'];

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

$query = "SELECT jl.job_id, jl.title, jl.employment_type, c.companyName, c.comp_logo_dir 
          FROM tbl_job_listing jl 
          JOIN tbl_comp_info c ON jl.employer_id = c.company_id 
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
    $user_query = $conn->prepare("SELECT firstName FROM tbl_emp_info WHERE user_id = ?");
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
      .job-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
      }

      .job-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
      }

      .job-image-container {
        position: relative;
        height: 150px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        overflow: hidden;
      }

      .job-company-logo {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
      }

      .job-card:hover .job-company-logo {
        transform: scale(1.1);
      }

      .job-type-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #6c63ff;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8em;
        font-weight: 500;
      }

      .job-content {
        padding: 20px;
        flex-grow: 1;
      }

      .job-title {
        font-size: 1.2em;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        transition: color 0.3s ease;
      }

      .job-card:hover .job-title {
        color: #6c63ff;
      }

      .company-name {
        color: #6c757d;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 5px;
      }

      .company-name i {
        color: #6c63ff;
      }

      .btn-save-job {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: auto;
        height: auto;
        padding: 8px;
        border: none;
        background: transparent;
        color: #6c63ff;
        font-size: 1.5em;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.3s ease;
      }

      .btn-save-job:hover {
        color: #dc3545;
      }

      .btn-save-job.saved {
        color: #dc3545;
      }

      .btn-save-job.saved:hover {
        color: #6c63ff;
      }

      .btn-save-job i {
        font-size: 1.1em;
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
            <form method="get" action="employee/emp_job_list.php" class="search-jobs-form">
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
                    <option value="Part-Time">Part-Time</option>
                    <option value="Full-Time">Full-Time</option>
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
        <h2 class="text-center mb-4">Recent Job Listings</h2>
        <div class="row" id="recent-jobs-container" style="max-height: 400px; overflow-y: auto;">
            <!-- Job listings will be dynamically loaded here -->
        </div>
    </div>
</section>

    <!-- site stats -->
    <section class="py-5 bg-image overlay-primary fixed overlay" id="next" style="background-image: url('fortest/images/HOMEBG.jpg');">
      <div class="container" style="background-color: #6267FF">
        <div class="row mb-5 justify-content-center">
          <div class="col-md-7 text-center">
            <h2 class="section-title mb-2 text-white">JobBoard Site Stats</h2>
            <p class="lead text-white">This section highlights key JobBoard stats, including applicants, jobs posted, jobs filled, and participating companies, offering insight into the platform's growth and impact.</p>
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

    <section id="about" class="site-section py-4 flex justify-center items-center min-h-screen">
    <div class="container bg-light p-5 d-flex flex-column flex-md-row align-items-center">
        <div class="col-md-6 mb-4 mb-md-0">
            <h1 class="display-4 font-weight-bold mb-3">ABOUT US</h1>
            <p style="color: #333; class=text-muted mb-3">The Public Employment Service Office (PESO) of San Pablo City is a government office dedicated to assisting job seekers and employers by providing employment services, 
              career counseling, and job placement opportunities. Our mission is to bridge the gap between job seekers and the labor market, ensuring a sustainable
               livelihood for the community.</p>
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
                        <p class="card-text">Visit PESO San Pablo City, explore job listings, and discover opportunities that align with your skills and career goals.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="https://storage.googleapis.com/a1aa/image/YIYYs-dEqsNdyJqJEfJLDAe1cON3DEbtamQpaM3pE04.jpg" class="card-img-top mx-auto" alt="Icon representing job application" style="width: 64px; height: 64px;">
                    <div class="card-body text-black">
                        <h5 class="card-title">2. Apply for job
                        </h5>
                        <p class="card-text">Register at PESO, submit your resume, get matched with job openings, and attend interviews or screenings.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card p-4">
                    <img src="https://storage.googleapis.com/a1aa/image/SPg_obwdygMCcsDWmQUBKKDLkSV4aF42IDTrT0c9R9g.jpg" class="card-img-top mx-auto" alt="Icon representing getting a job" style="width: 64px; height: 64px;">
                    <div class="card-body text-black">
                        <h5 class="card-title">3. Get your job</h5>
                        <p class="card-text">Secure a job offer, complete requirements, and begin your career with PESO's guidance, ensuring a smooth transition into employment.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    
    <section id="Blogs" class="bg-light pt-5 Announcement text-center">
  <div class="container">
    <h2>Announcements</h2>
    <div class="owl-carousel customer-logos">
      <div class="slide"><img src="fortest/images/Maxim.png" alt="Maxim"></div>
      <div class="slide"><img src="fortest/images/Maxim2.png" alt="Maxim"></div>
      <div class="slide"><img src="fortest/images/Alorica.png" alt="Alorica"></div>
      <div class="slide"><img src="fortest/images/Alorica1.png" alt="Alorica"></div>
      <div class="slide"><img src="fortest/images/Canon1.png" alt="Canon"></div>
      <div class="slide"><img src="fortest/images/Canon2.png" alt="Canon"></div>
      <div class="slide"><img src="fortest/images/Canon3.png" alt="Canon"></div>
    </div>
  </div>
</section>

    <section id="contacts" style="background: #6c63ff; color: #fff; padding: 50px 0; text-align: center;">
    <div style="max-width: 1100px; margin: auto; display: flex; flex-wrap: wrap; justify-content: space-between;">
        <div style="flex: 1; min-width: 500px; text-align: left; padding: 50px;">
            <h2 style="color:white;">Contact Us</h2>
            <p>Reach out to us for any inquiries or assistance. We're here to help!</p>
            <div style="margin-top: 50px;">
                <p><strong>📍 Address:</strong> 4th floor City Governance Bldg. City Hall Compound Brgy. V-A , San Pablo City, Philippines</p>
                <p><strong>📞 Phone:</strong> 9999-999-9999</p>
                <p><strong>📧 Email:</strong> pesosanpablo@gmail.com</p>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 300px; background: #fff; color: #000; padding: 20px; border-radius: 8px;">
            <h3>Send Message</h3>
            <form action="#" method="POST">
                <input type="text" name="name" placeholder="Full Name" required style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">
                <input type="email" name="email" placeholder="Email" required style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc;">
                <textarea name="message" placeholder="Type your message..." required style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; height: 100px;"></textarea>
                <button type="submit" style="width: 100%; padding: 12px; background: #00bcd4; color: white; border: none; cursor: pointer;">Send</button>
            </form>
        </div>
    </div>
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
              <a href="https://web.facebook.com/PESO.sanpablo"><span class="icon-facebook"></span></a>
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
    document.addEventListener("DOMContentLoaded", function () {
        const container = document.getElementById("recent-jobs-container");

        fetch("includes/employee/fetch_recent_jobs.php")
            .then(response => response.json())
            .then(jobs => {
                jobs.forEach(job => {
                    const jobCard = document.createElement("div");
                    jobCard.className = "col-md-6 mb-4";
                    jobCard.innerHTML = `
                        <div class="job-card">
                            <div class="job-image-container">
                                <img src="${job.comp_logo_dir.replace('../', '')}" 
                                     alt="${job.companyName} Logo" 
                                     class="job-company-logo">
                                <div class="job-type-badge">${job.employment_type}</div>
                            </div>
                            <div class="job-content">
                                <h5 class="job-title">${job.title}</h5>
                                <p class="company-name">
                                    <i class="icon-building"></i> ${job.companyName}
                                </p>
                                ${<?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?> ? `
                                    <form method="post" action="includes/save_job_process.php" class="save-job-form">
                                        <input type="hidden" name="job_id" value="${job.job_id}">
                                        <button type="submit" name="action" value="save" class="btn btn-save-job">
                                            <i class="icon-heart"></i>
                                        </button>
                                    </form>
                                ` : ''}
                            </div>
                        </div>
                    `;
                    container.appendChild(jobCard);
                });
            })
            .catch(error => console.error("Error fetching recent jobs:", error));
    });
</script>
<script>
  $(document).ready(function() {
    $(".owl-carousel").owlCarousel({
      loop: true,
      margin: 10,
      nav: true,
      autoplay: true,
      autoplayTimeout: 3000,
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

