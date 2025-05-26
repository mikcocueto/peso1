<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Company Settings</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
      font-family: "Segoe UI", sans-serif;
    }
    .settings-card {
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.05);
      background-color: #fff;
    }
    .form-label {
      font-weight: 500;
    }
    .form-control, .form-select {
      border-radius: 0.5rem;
    }
    .tab-content {
      padding-top: 1.5rem;
    }
    .save-btn {
      border-radius: 10px;
      padding: 0.75rem 2rem;
    }
  </style>
</head>
<body>

<?php include 'comp_navbar&tab.php'; ?>

<div class="container py-5">
  <div class="settings-card p-4 p-md-5">
    <h2 class="mb-4">Company Settings</h2>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="team-tab" data-bs-toggle="tab" data-bs-target="#team" type="button" role="tab" aria-controls="team" aria-selected="true">Team Members</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">Notification</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab" aria-controls="password" aria-selected="false">Change Password</button>
      </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="settingsTabsContent">

      <!-- Team Members -->
      <div class="tab-pane fade show active" id="team" role="tabpanel" aria-labelledby="team-tab">
        <h5>Team Members</h5>
        <p>Add and manage team members with access to your company dashboard.</p>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#inviteTeamModal">
          <i class="fas fa-user-plus me-2"></i>Invite Team Member
        </button>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between align-items-center">
            John Doe <span class="badge bg-secondary">Admin</span>
          </li>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            Jane Smith <span class="badge bg-secondary">Recruiter</span>
          </li>
        </ul>
        <div class="d-flex justify-content-end gap-2 mt-4">
          <button class="btn btn-secondary save-btn" type="button" onclick="window.location.reload()">Cancel</button>
          <button class="btn btn-primary save-btn" type="button">Save Changes</button>
        </div>
      </div>

      <!-- Notification Settings -->
      <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
        <h5>Notification Settings</h5>
        <form>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="notifyApplicants" checked>
            <label class="form-check-label" for="notifyApplicants">
              Email me when new applicants apply
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="jobExpiry">
            <label class="form-check-label" for="jobExpiry">
              Notify me when a job post is about to expire
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="weeklyDigest">
            <label class="form-check-label" for="weeklyDigest">
              Send me weekly summary emails
            </label>
          </div>
          <div class="d-flex justify-content-end gap-2 mt-4">
            <button class="btn btn-secondary save-btn" type="button" onclick="window.location.reload()">Cancel</button>
            <button class="btn btn-primary save-btn" type="submit">Save Changes</button>
          </div>
        </form>
      </div>

      <!-- Change Password -->
      <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
        <div class="g-3">
          <div class="col-md-4">
            <label class="form-label">Current Password</label>
            <input type="password" class="form-control" id="currentPassword" placeholder="••••••••">
          </div>
          <div class="col-md-4">
            <label class="form-label">New Password</label>
            <input type="password" class="form-control" id="newPassword" placeholder="••••••••">
          </div>
          <div class="col-md-4">
            <label class="form-label">Confirm New Password</label>
            <input type="password" class="form-control" id="confirmPassword" placeholder="••••••••">
          </div>
        </div>
        <div class="form-text text-muted">
          Password must be at least 8 characters and include a number, uppercase, and symbol.
        </div>
        <!-- Add password strength meter -->
        <div id="password-strength" class="mt-2">
          <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 0%" id="strengthBar"></div>
          </div>
          <small id="strengthLabel" class="form-text"></small>
        </div>
        <div class="form-check mt-2">
          <input class="form-check-input" type="checkbox" id="togglePassword">
          <label class="form-check-label" for="togglePassword">Show Passwords</label>
        </div>
        <div class="d-flex justify-content-end gap-2 mt-4">
          <button class="btn btn-secondary save-btn" type="button" onclick="window.location.reload()">Cancel</button>
          <div class="dropdown">
            <button class="btn btn-primary save-btn dropdown-toggle" type="button" id="savePasswordDropdown" data-bs-toggle="dropdown" aria-expanded="false">
              Save Changes
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="savePasswordDropdown">
              <li>
                <a class="dropdown-item" href="#" onclick="handlePasswordChange('logout'); return false;">
                  Save &amp; Log Out
                </a>
              </li>
              <li>
                <a class="dropdown-item" href="#" onclick="handlePasswordChange('stay'); return false;">
                  Save &amp; Stay on Page
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Invite Team Member Modal -->
<div class="modal fade" id="inviteTeamModal" tabindex="-1" aria-labelledby="inviteTeamModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow-lg border-0" style="border-radius: 1.25rem;">
      <div class="modal-header" style="background: linear-gradient(90deg, #0d6efd 60%, #4f46e5 100%); border-top-left-radius: 1.25rem; border-top-right-radius: 1.25rem;">
        <h5 class="modal-title text-white d-flex align-items-center gap-2" id="inviteTeamModalLabel">
          <i class="fas fa-user-plus" style="color: #fff; background: #4f46e5; border-radius: 50%; padding: 8px; font-size: 1.5rem; box-shadow: 0 2px 8px rgba(79,70,229,0.15);"></i>
          Invite Team Member
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="inviteTeamForm" autocomplete="off">
        <div class="modal-body p-4">
          <div class="mb-3">
            <label for="inviteName" class="form-label">Full Name</label>
            <input type="text" class="form-control form-control-lg" id="inviteName" placeholder="Enter full name" required>
          </div>
          <div class="mb-3">
            <label for="inviteEmail" class="form-label">Email Address</label>
            <input type="email" class="form-control form-control-lg" id="inviteEmail" placeholder="Enter email address" required>
          </div>
          <div class="mb-3">
            <label for="inviteRole" class="form-label">Role</label>
            <select class="form-select form-select-lg" id="inviteRole" required>
              <option value="" disabled selected>Select role</option>
              <option value="Admin">Admin</option>
              <option value="Recruiter">Recruiter</option>
              <option value="HR">HR</option>
            </select>
          </div>
          <div id="inviteFeedback" class="alert d-none mt-3" role="alert"></div>
        </div>
        <div class="modal-footer d-flex justify-content-between align-items-center px-4 pb-4">
          <div class="d-flex align-items-center gap-2">
            <i class="fas fa-info-circle text-muted"></i>
            <small class="text-muted">An invitation email will be sent to the team member.</small>
          </div>
          <button type="submit" class="btn btn-primary btn-lg px-4" style="border-radius: 2rem;">
            <i class="fas fa-paper-plane me-2"></i>Send Invite
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Font Awesome for icons (if not already included) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
  /* Modal custom styles */
  #inviteTeamModal .modal-content {
    background: #f9fafd;
    border-radius: 1.25rem;
    box-shadow: 0 8px 32px rgba(13,110,253,0.08), 0 1.5px 6px rgba(111,66,193,0.10);
    border: none;
  }
  #inviteTeamModal .form-control, #inviteTeamModal .form-select {
    border-radius: 0.75rem;
    font-size: 1.1rem;
    background: #f4f7fb;
    border: 1px solid #e0e6ed;
    transition: border-color 0.2s;
  }
  #inviteTeamModal .form-control:focus, #inviteTeamModal .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.15rem rgba(13,110,253,0.15);
    background: #fff;
  }
  #inviteTeamModal .modal-header {
    border-bottom: none;
    background: linear-gradient(90deg, #0d6efd 60%, #4f46e5 100%) !important;
  }
  #inviteTeamModal .modal-footer {
    border-top: none;
    background: transparent;
  }
  #inviteTeamModal .btn-primary {
    background: linear-gradient(90deg, #0d6efd 60%, #4f46e5 100%) !important;
    border: none;
  }
  #inviteTeamModal .btn-primary:hover {
    background: linear-gradient(90deg, #0b5ed7 60%, #3730a3 100%) !important;
  }
</style>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordIds = ['currentPassword', 'newPassword', 'confirmPassword'];
    if (togglePassword) {
      togglePassword.addEventListener('change', function () {
        passwordIds.forEach(id => {
          const input = document.getElementById(id);
          if (input) input.type = togglePassword.checked ? 'text' : 'password';
        });
      });
    }

    // Password strength meter
    const newPasswordInput = document.getElementById('newPassword');
    const strengthBar = document.getElementById('strengthBar');
    const strengthLabel = document.getElementById('strengthLabel');
    if (newPasswordInput && strengthBar && strengthLabel) {
      newPasswordInput.addEventListener('input', function() {
        const value = this.value;
        let strength = 0;
        if (value.length >= 8) strength += 1;
        if (/[A-Z]/.test(value)) strength += 1;
        if (/[0-9]/.test(value)) strength += 1;
        if (/[^A-Za-z0-9]/.test(value)) strength += 1;

        const strengths = ['Weak', 'Fair', 'Good', 'Strong'];
        const colors = ['#dc3545', '#ffc107', '#0d6efd', '#198754'];

        strengthBar.style.width = (strength * 25) + '%';
        strengthBar.style.backgroundColor = colors[strength - 1] || '#dee2e6';
        strengthLabel.textContent = strengths[strength - 1] || '';
      });
    }

    // Invite Team Member Modal - Interactive
    document.getElementById('inviteTeamForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const name = document.getElementById('inviteName').value.trim();
      const email = document.getElementById('inviteEmail').value.trim();
      const role = document.getElementById('inviteRole').value;
      const feedback = document.getElementById('inviteFeedback');
      feedback.classList.add('d-none');
      feedback.classList.remove('alert-success', 'alert-danger');

      // Simple validation
      if (!name || !email || !role) {
        feedback.textContent = "Please fill in all fields.";
        feedback.classList.remove('d-none');
        feedback.classList.add('alert', 'alert-danger');
        return;
      }

      // Simulate sending invite (replace with AJAX for real backend)
      feedback.textContent = "Sending invitation...";
      feedback.classList.remove('d-none', 'alert-danger', 'alert-success');
      feedback.classList.add('alert', 'alert-info');

      setTimeout(() => {
        // Simulate success
        feedback.textContent = "Invitation sent successfully to " + name + "!");
        feedback.classList.remove('alert-info', 'alert-danger');
        feedback.classList.add('alert-success');
        // Optionally reset form
        document.getElementById('inviteTeamForm').reset();
        setTimeout(() => {
          const modal = bootstrap.Modal.getInstance(document.getElementById('inviteTeamModal'));
          if (modal) modal.hide();
          feedback.classList.add('d-none');
        }, 1500);
      }, 1200);
    });
  });

  // Change Password logic
  function handlePasswordChange(action) {
    const currentPass = document.getElementById('currentPassword').value.trim();
    const newPass = document.getElementById('newPassword').value.trim();
    const confirmPass = document.getElementById('confirmPassword').value.trim();

    // Basic validation
    if (!currentPass || !newPass || !confirmPass) {
      alert('Please fill in all password fields.');
      return;
    }
    if (newPass.length < 8) {
      alert('Password must be at least 8 characters.');
      return;
    }
    if (!/[A-Z]/.test(newPass) || !/[0-9]/.test(newPass) || !/[^A-Za-z0-9]/.test(newPass)) {
      alert('Password must include a number, uppercase letter, and symbol.');
      return;
    }
    if (newPass !== confirmPass) {
      alert('New password and confirm password do not match.');
      return;
    }
    if (currentPass === newPass) {
      alert('New password must be different from current password.');
      return;
    }

    // Simulate password change success (replace with AJAX for real backend)
    if (action === 'logout') {
      alert('Password changed successfully. You will be logged out.');
      window.location.href = '../includes/company/comp_logout.php';
    } else {
      alert('Password changed successfully. You will stay on this page.');
      document.getElementById('currentPassword').value = '';
      document.getElementById('newPassword').value = '';
      document.getElementById('confirmPassword').value = '';
      document.getElementById('strengthBar').style.width = '0%';
      document.getElementById('strengthLabel').textContent = '';
    }
  }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
