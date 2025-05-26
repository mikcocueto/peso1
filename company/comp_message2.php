<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Job Portal - Modern UI</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: #f8f9fa;
      color: #212529;
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 240px;
      background-color: #fff;
      padding: 24px;
      border-right: 1px solid #dee2e6;
      box-shadow: 2px 0 5px rgba(0, 0, 0, 0.03);
    }

    .sidebar h2 {
      margin-bottom: 30px;
      font-size: 1.75rem;
      color: #007bff;
    }

    .sidebar nav a {
      display: block;
      margin: 14px 0;
      font-weight: 500;
      color: #495057;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .sidebar nav a:hover {
      color: #007bff;
    }

    .main {
      display: flex;
      flex: 1;
      flex-direction: row;
    }

    .job-listings {
      width: 35%;
      border-right: 1px solid #dee2e6;
      background-color: #fff;
      overflow-y: auto;
    }

    .job-listings h3 {
      padding: 20px;
      font-size: 1.25rem;
      border-bottom: 1px solid #dee2e6;
      background: #f1f3f5;
    }

    .job-item {
      padding: 16px 20px;
      border-bottom: 1px solid #f1f3f5;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    .job-item:hover {
      background-color: #e9f5ff;
    }

    .job-item strong {
      font-size: 1.05rem;
      display: block;
      color: #212529;
    }

    .job-item small {
      color: #868e96;
    }

    .job-details {
      flex: 1;
      padding: 40px;
      background-color: #ffffff;
      overflow-y: auto;
      transition: all 0.3s ease;
    }

    .job-details h2 {
      font-size: 1.8rem;
      margin-bottom: 20px;
      color: #343a40;
    }

    .job-details p {
      font-size: 1.05rem;
      line-height: 1.6;
      color: #495057;
    }
  </style>
</head>
<body>
  <div class="main">
    <div class="job-listings">
      <h3>Job Listing</h3>
      <div class="job-item" onclick="showDetails('Aircon Specialist', 'Responsible for installing, maintaining, and repairing air conditioning systems.', 1)">
        <strong>Aircon Specialist</strong>
        <small>May 2, 2025 at 12:00 AM</small>
      </div>
      <div class="job-item" onclick="showDetails('House Keeping Attendant', 'Maintains cleanliness and orderliness in assigned areas.', 2)">
        <strong>House Keeping Attendant</strong>
        <small>April 30, 2025 at 12:00 AM</small>
      </div>
      <div class="job-item" onclick="showDetails('Accountant', 'Prepares financial statements, tax reports, and audits.', 3)">
        <strong>Accountant</strong>
        <small>April 24, 2025 at 12:00 AM</small>
      </div>
      <div class="job-item" onclick="showDetails('Job Test na Naman', 'Placeholder for test purposes.', 4)">
        <strong>Job Test na Naman</strong>
        <small>April 21, 2025 at 12:00 AM</small>
      </div>
      <div class="job-item" onclick="showDetails('Capitolyo', 'Various community-based job opportunities.', 5)">
        <strong>Capitolyo</strong>
        <small>April 15, 2025 at 12:00 AM</small>
      </div>
      <div class="job-item" onclick="showDetails('Capitolyo12', 'Extended program for Capitolyo applicants.', 6)">
        <strong>Capitolyo12</strong>
        <small>April 15, 2025 at 12:00 AM</small>
      </div>
      <div class="job-item" onclick="showDetails('Registered Nurse (RN)', 'Provides patient care and health monitoring.', 7)">
        <strong>Registered Nurse (RN)</strong>
        <small>April 3, 2025 at 12:00 AM</small>
      </div>
      <div class="job-item" onclick="showDetails('Web Developer', 'Develops and maintains websites and web applications.', 8)">
        <strong>Web Developer</strong>
        <small>April 1, 2025 at 12:00 AM</small>
      </div>
    </div>

    <div class="job-details" id="job-details">
      <p>Select a job listing to view details.</p>
      <div id="messagesContainer" style="margin-top:2rem;">
        <!-- Messages will be loaded here -->
      </div>
      <!-- No input or send button -->
    </div>
  </div>

  <!-- Simulated backend messages per jobId as HTML (for demo only) -->
  <div id="job-messages-data" style="display:none;">
    <div id="job-messages-1">
      <table style="width:100%; border-collapse:separate; border-spacing:0 8px;">
        <thead>
          <tr style="background:#f1f3f5;">
            <th style="padding:10px 12px; text-align:left; color:#868e96; font-weight:500;">From</th>
            <th style="padding:10px 12px; text-align:left; color:#868e96; font-weight:500;">Message</th>
            <th style="padding:10px 12px; text-align:right; color:#868e96; font-weight:500;">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr style="background:#fff; border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.03);">
            <td style="padding:14px 12px; font-weight:600; color:#495057;">Chris</td>
            <td style="padding:14px 12px; color:#343a40;">Hello, is this position still open?</td>
            <td style="padding:14px 12px; text-align:right; color:#868e96; white-space:nowrap;">2024-06-01 10:00</td>
          </tr>
          <tr style="background:#fff; border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.03);">
            <td style="padding:14px 12px; font-weight:600; color:#495057;">HR</td>
            <td style="padding:14px 12px; color:#343a40;">Yes, please send your resume.</td>
            <td style="padding:14px 12px; text-align:right; color:#868e96; white-space:nowrap;">2024-06-01 10:01</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div id="job-messages-2">
      <table style="width:100%; border-collapse:separate; border-spacing:0 8px;">
        <thead>
          <tr style="background:#f1f3f5;">
            <th style="padding:10px 12px; text-align:left; color:#868e96; font-weight:500;">From</th>
            <th style="padding:10px 12px; text-align:left; color:#868e96; font-weight:500;">Message</th>
            <th style="padding:10px 12px; text-align:right; color:#868e96; font-weight:500;">Date</th>
          </tr>
        </thead>
        <tbody>
          <tr style="background:#fff; border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,0.03);">
            <td style="padding:14px 12px; font-weight:600; color:#495057;">Recruiter</td>
            <td style="padding:14px 12px; color:#343a40;">Are you available for an interview?</td>
            <td style="padding:14px 12px; text-align:right; color:#868e96; white-space:nowrap;">2024-06-02 09:00</td>
          </tr>
        </tbody>
      </table>
    </div>
    <!-- Add more job-messages-N divs as needed for other jobs -->
  </div>

  <script>
    function showDetails(title, description, jobId) {
      const details = document.getElementById('job-details');
      let html = `
        
        <div id="messagesContainer" style="margin-top:2rem;">`;

      // Get the HTML table for this jobId from the hidden divs
      const messagesDiv = document.getElementById('job-messages-' + jobId);
      if (messagesDiv && messagesDiv.innerHTML.trim()) {
        html += messagesDiv.innerHTML;
      } else {
        html += '<div class="text-muted text-center py-4">No messages yet.</div>';
      }
      html += '</div>';
      details.innerHTML = html;
    }
  </script>
</body>
</html>
