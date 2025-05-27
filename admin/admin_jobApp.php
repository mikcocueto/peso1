<?php


// Example: Fetch job applications from database (replace with real DB logic)
$applications = [
    [
        'id' => 1,
        'applicant' => 'John Doe',
        'job_title' => 'Web Developer',
        'company' => 'Acme Corp',
        'status' => 'Pending',
        'date_applied' => '2024-06-01',
        'resume' => '#',
        // Personal Details
        'email' => 'john@example.com',
        'phone' => '0917-123-4567',
        'address' => '123 Main St, Makati, Metro Manila',
        'gender' => 'Male',
        'birthday' => '1995-04-12',
        'nationality' => 'Filipino',
        'civil_status' => 'Single',
        // Professional Details
        'education' => 'BS Computer Science, Ateneo de Manila University',
        'skills' => 'PHP, Laravel, JavaScript, MySQL',
        'experience' => '2 years at Acme Corp as Web Developer',
        'linkedin' => 'https://linkedin.com/in/johndoe',
        'expected_salary' => '₱35,000',
        'availability' => 'Immediate',
        'notes' => 'Looking for growth opportunities.',
    ],
    [
        'id' => 2,
        'applicant' => 'Jane Smith',
        'job_title' => 'UI/UX Designer',
        'company' => 'Designify',
        'status' => 'Reviewed',
        'date_applied' => '2024-06-02',
        'resume' => '#',
        // Personal Details
        'email' => 'jane@example.com',
        'phone' => '0928-456-7890',
        'address' => '456 Market Ave, Quezon City, Metro Manila',
        'gender' => 'Female',
        'birthday' => '1997-09-23',
        'nationality' => 'Filipino',
        'civil_status' => 'Married',
        // Professional Details
        'education' => 'BA Multimedia Arts, De La Salle-College of Saint Benilde',
        'skills' => 'Adobe XD, Sketch, Figma, HTML, CSS',
        'experience' => '3 years at Designify as UI/UX Designer',
        'linkedin' => 'https://linkedin.com/in/janesmith',
        'expected_salary' => '₱40,000',
        'availability' => '2 weeks notice',
        'notes' => 'Open to freelance opportunities.',
    ],
    // ...add more or fetch from DB...
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Applications - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body {
            background: #f4f6fb;
        }
        .content {
            margin-left: 16.6667%;
            margin-top: 4rem;
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 2px 16px rgba(44,62,80,0.07);
            transition: box-shadow 0.2s;
        }
        .card:hover {
            box-shadow: 0 6px 32px rgba(44,62,80,0.13);
        }
        .badge-status {
            font-size: 0.95em;
            padding: 0.5em 1em;
            border-radius: 1em;
        }
        .badge-pending { background: #ffe082; color: #795548; }
        .badge-reviewed { background: #b2f2bb; color: #14532d; }
        .badge-rejected { background: #ffb3b3; color: #b71c1c; }
        .badge-accepted { background: #a5d6a7; color: #1b5e20; }
        .table thead th {
            background: #2c3e50;
            color: #fff;
            border: none;
        }
        .table tbody tr {
            background: #fff;
            transition: background 0.2s;
        }
        .table tbody tr:hover {
            background: #f1f3f6;
        }
        .modal-content {
            border-radius: 1rem;
        }
        .search-bar {
            max-width: 350px;
        }
        .action-btns .btn {
            margin-right: 0.25rem;
        }
        .action-btns .btn:last-child {
            margin-right: 0;
        }
        @media (max-width: 991px) {
            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
<!-- side&nav.php is already included above -->
  <div class="col-md-2 sidebar p-0">
      <?php include '../admin/side&nav.php'; ?>
    </div>
<div class="content p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <h2 class="fw-bold mb-0">Job Applications</h2>
        <form class="d-flex search-bar" role="search">
            <input class="form-control me-2" type="search" placeholder="Search applicant, job..." aria-label="Search">
            <button class="btn btn-outline-primary" type="submit"><i class="bi bi-search"></i></button>
        </form>
    </div>
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Applicant</th>
                        <th>Job Title</th>
                        <th>Applied Company</th>
                        <th>Status</th>
                        <th>Date Applied</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?= htmlspecialchars($app['id']) ?></td>
                        <td>
                            <span class="fw-semibold"><?= htmlspecialchars($app['applicant']) ?></span><br>
                            <small class="text-muted"><?= htmlspecialchars($app['email']) ?></small>
                        </td>
                        <td><?= htmlspecialchars($app['job_title']) ?></td>
                        <td><?= htmlspecialchars($app['company']) ?></td>
                        <td>
                            <?php
                                $status = strtolower($app['status']);
                                $badgeClass = match($status) {
                                    'pending' => 'badge-pending',
                                    'reviewed' => 'badge-reviewed',
                                    'accepted' => 'badge-accepted',
                                    'rejected' => 'badge-rejected',
                                    default => 'badge-secondary'
                                };
                            ?>
                            <span class="badge badge-status <?= $badgeClass ?>"><?= ucfirst($status) ?></span>
                        </td>
                        <td><?= htmlspecialchars($app['date_applied']) ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <button class="btn btn-sm btn-outline-info shadow-sm" data-bs-toggle="modal" data-bs-target="#viewModal<?= $app['id'] ?>" title="View">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="<?= htmlspecialchars($app['resume']) ?>" class="btn btn-sm btn-outline-secondary shadow-sm" target="_blank" title="Download Resume">
                                    <i class="bi bi-file-earmark-arrow-down"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <!-- Modal for viewing application details -->
                    <div class="modal fade" id="viewModal<?= $app['id'] ?>" tabindex="-1" aria-labelledby="viewModalLabel<?= $app['id'] ?>" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content" style="border-radius:1.25rem; box-shadow:0 8px 32px rgba(44,62,80,0.18);">
                          <div class="modal-header" style="background:#2c3e50; color:#fff; border-top-left-radius:1.25rem; border-top-right-radius:1.25rem;">
                            <h5 class="modal-title" id="viewModalLabel<?= $app['id'] ?>">Application Details</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body bg-light">
                            <!-- User Profile Image at the top center -->
                            <div class="d-flex flex-column align-items-center mb-4">
                              <img src="https://ui-avatars.com/api/?name=<?= urlencode($app['applicant']) ?>&background=2c3e50&color=fff&size=96" alt="Profile" class="rounded-circle border mb-2" width="96" height="96">
                              <h6 class="text-primary mb-0"><i class="bi bi-person-circle"></i> Applicant</h6>
                              <p class="mb-1">Status: <span class="badge badge-status <?= $badgeClass ?>"><?= ucfirst($status) ?></span></p>
                            </div>
                            <div class="row g-4">
                              <div class="col-md-6">
                                <div class="p-3 rounded bg-white shadow-sm">
                                  <h6 class="text-secondary mb-2">Personal Details</h6>
                                  <p class="mb-1 fw-bold"><?= htmlspecialchars($app['applicant']) ?></p>
                                  <p class="mb-1 text-muted"><i class="bi bi-envelope"></i> <?= htmlspecialchars($app['email']) ?></p>
                                  <p class="mb-1 text-muted"><i class="bi bi-telephone"></i> <?= htmlspecialchars($app['phone']) ?></p>
                                  <p class="mb-1 text-muted"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($app['address']) ?></p>
                                  <p class="mb-1 text-muted"><i class="bi bi-gender-ambiguous"></i> <?= htmlspecialchars($app['gender']) ?></p>
                                  <p class="mb-1 text-muted"><i class="bi bi-cake"></i> <?= htmlspecialchars($app['birthday']) ?></p>
                                  <p class="mb-1 text-muted"><i class="bi bi-flag"></i> <?= htmlspecialchars($app['nationality']) ?></p>
                                  <p class="mb-1 text-muted"><i class="bi bi-person-badge"></i> <?= htmlspecialchars($app['civil_status']) ?></p>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <div class="p-3 rounded bg-white shadow-sm">
                                  <h6 class="text-secondary mb-2">Professional Details</h6>
                                  <p class="mb-1"><i class="bi bi-mortarboard"></i> <?= htmlspecialchars($app['education']) ?></p>
                                  <p class="mb-1"><i class="bi bi-tools"></i> <?= htmlspecialchars($app['skills']) ?></p>
                                  <p class="mb-1"><i class="bi bi-briefcase"></i> <?= htmlspecialchars($app['experience']) ?></p>
                                  <p class="mb-1"><i class="bi bi-linkedin"></i> <a href="<?= htmlspecialchars($app['linkedin']) ?>" target="_blank">LinkedIn</a></p>
                                  <p class="mb-1"><i class="bi bi-cash"></i> Expected Salary: <?= htmlspecialchars($app['expected_salary']) ?></p>
                                  <p class="mb-1"><i class="bi bi-clock"></i> Availability: <?= htmlspecialchars($app['availability']) ?></p>
                                  <p class="mb-1"><i class="bi bi-chat-left-text"></i> Notes: <?= htmlspecialchars($app['notes']) ?></p>
                                </div>
                              </div>
                            </div>
                            <hr>
                            <div class="d-flex align-items-center gap-2">
                              <h6 class="mb-0"><i class="bi bi-file-earmark-arrow-down"></i> Resume</h6>
                              <a href="<?= htmlspecialchars($app['resume']) ?>" class="btn btn-outline-primary btn-sm ms-2" target="_blank">Download</a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
<script>
    // Interactive feedback for Accept/Reject buttons
    document.querySelectorAll('.btn-outline-success').forEach(btn => {
        btn.addEventListener('click', function() {
            btn.classList.add('btn-success');
            btn.innerHTML = '<i class="bi bi-check-lg"></i>';
            setTimeout(() => {
                btn.classList.remove('btn-success');
                btn.innerHTML = '<i class="bi bi-check2-circle"></i>';
            }, 1000);
        });
    });
    document.querySelectorAll('.btn-outline-danger').forEach(btn => {
        btn.addEventListener('click', function() {
            btn.classList.add('btn-danger');
            btn.innerHTML = '<i class="bi bi-x-lg"></i>';
            setTimeout(() => {
                btn.classList.remove('btn-danger');
                btn.innerHTML = '<i class="bi bi-x-circle"></i>';
            }, 1000);
        });
    });
</script>
</body>
</html>
