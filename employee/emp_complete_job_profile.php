<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Job Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/shared/styles.css" />
    <style>
        .form-step { display: none; }
        .form-step-active { display: block; }
        .progress-bar { width: 0; transition: width 0.4s; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <div class="card-header">
            <h4>Complete Job Profile</h4>
        </div>
        <div class="card-body">
            <div class="progress mb-4">
                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <form id="jobProfileForm" method="POST" enctype="multipart/form-data">
                <!-- Step 1: Personal Information -->
                <div class="form-step form-step-active">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-control" name="address" required>
                    </div>
                    <button type="button" class="btn btn-primary btn-next">Next</button>
                </div>
                <!-- Step 2: Professional Details -->
                <div class="form-step">
                    <div class="mb-3">
                        <label class="form-label">Current Job Title</label>
                        <input type="text" class="form-control" name="job_title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Years of Experience</label>
                        <input type="number" class="form-control" name="experience" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Highest Level of Education</label>
                        <select class="form-control" name="highest_education" required>
                            <option value="">Select Education Level</option>
                            <option value="High School">High School</option>
                            <option value="Vocational / Technical Certification">Vocational / Technical Certification</option>
                            <option value="Associate's Degree">Associate's Degree</option>
                            <option value="Bachelor's Degree">Bachelor's Degree</option>
                            <option value="Master's Degree">Master's Degree</option>
                            <option value="Doctorate">Doctorate</option>
                            <option value="Professional Degree">Professional Degree</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-secondary btn-prev">Previous</button>
                    <button type="button" class="btn btn-primary btn-next">Next</button>
                </div>
                <!-- Step 3: Industry & Skills -->
                <div class="form-step">
                    <div class="mb-3">
                        <label class="form-label">Industry</label>
                        <select class="form-control" name="industry" required>
                            <option value="">Select Industry</option>
                            <option value="Information Technology (IT) & Software">Information Technology (IT) & Software</option>
                            <option value="Healthcare & Medical">Healthcare & Medical</option>
                            <option value="Finance & Banking">Finance & Banking</option>
                            <option value="Engineering & Construction">Engineering & Construction</option>
                            <option value="Education & Training">Education & Training</option>
                            <option value="Marketing & Advertising">Marketing & Advertising</option>
                            <option value="Sales & Retail">Sales & Retail</option>
                            <option value="Customer Service & Support">Customer Service & Support</option>
                            <option value="Human Resources & Recruitment">Human Resources & Recruitment</option>
                            <option value="Hospitality & Tourism">Hospitality & Tourism</option>
                            <option value="Manufacturing & Production">Manufacturing & Production</option>
                            <option value="Transportation & Logistics">Transportation & Logistics</option>
                            <option value="Legal & Compliance">Legal & Compliance</option>
                            <option value="Media & Entertainment">Media & Entertainment</option>
                            <option value="Government & Public Administration">Government & Public Administration</option>
                            <option value="Non-Profit & NGOs">Non-Profit & NGOs</option>
                            <option value="Real Estate & Property Management">Real Estate & Property Management</option>
                            <option value="Agriculture & Farming">Agriculture & Farming</option>
                            <option value="Telecommunications">Telecommunications</option>
                            <option value="Pharmaceuticals & Biotechnology">Pharmaceuticals & Biotechnology</option>
                            <option value="Energy & Utilities">Energy & Utilities</option>
                            <option value="Aerospace & Defense">Aerospace & Defense</option>
                            <option value="Automotive Industry">Automotive Industry</option>
                            <option value="Fashion & Apparel">Fashion & Apparel</option>
                            <option value="Food & Beverage">Food & Beverage</option>
                            <option value="E-commerce & Online Business">E-commerce & Online Business</option>
                            <option value="Sports & Fitness">Sports & Fitness</option>
                            <option value="Consulting & Business Services">Consulting & Business Services</option>
                            <option value="Art & Design">Art & Design</option>
                            <option value="Environmental & Sustainability">Environmental & Sustainability</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Skills</label>
                        <input type="text" class="form-control" name="skills" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Certifications</label>
                        <input type="text" class="form-control" name="certifications">
                    </div>
                    <button type="button" class="btn btn-secondary btn-prev">Previous</button>
                    <button type="button" class="btn btn-primary btn-next">Next</button>
                </div>
                <!-- Step 4: Job Preferences -->
                <div class="form-step">
                    <div class="mb-3">
                        <label class="form-label">Job Type</label>
                        <select class="form-control" name="jobType" required>
                            <option value="">Select Job Type</option>
                            <option value="Full-time">Full-time</option>
                            <option value="Part-time">Part-time</option>
                            <option value="Contract">Contract</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Preferred Location</label>
                        <input type="text" class="form-control" name="location" required>
                    </div>
                    <button type="button" class="btn btn-secondary btn-prev">Previous</button>
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>
    $(document).ready(function() {
        const formSteps = $('.form-step');
        let currentStep = 0;

        function updateProgressBar() {
            const progress = ((currentStep + 1) / formSteps.length) * 100;
            $('.progress-bar').css('width', progress + '%');
        }

        $('.btn-next').click(function() {
            if (currentStep < formSteps.length - 1) {
                $(formSteps[currentStep]).removeClass('form-step-active');
                currentStep++;
                $(formSteps[currentStep]).addClass('form-step-active');
                updateProgressBar();
            }
        });

        $('.btn-prev').click(function() {
            if (currentStep > 0) {
                $(formSteps[currentStep]).removeClass('form-step-active');
                currentStep--;
                $(formSteps[currentStep]).addClass('form-step-active');
                updateProgressBar();
            }
        });

        updateProgressBar();
    });
</script>
</body>
</html>
