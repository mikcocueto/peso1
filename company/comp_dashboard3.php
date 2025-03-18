<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        /* Navbar */
        .navbar {
            background: #6c63ff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            color: white;
            height: 50px;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            width: 120px;
        }

        .navbar-icons {
            display: flex;
            gap: 20px;
            font-size: 24px;
            margin-left: auto; /* Align to the right */
        }

        .navbar-icons i {
            cursor: pointer;
        }

        /* Tabs */
        .tabs {
            background: #e0e0e0;
            padding: 10px;
            display: flex;
            border-bottom: 2px solid #ccc;
        }

        .tab {
            background: none;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .tab.active {
            border-bottom: 2px solid black;
        }

        /* Filters Job tab */
        .filters {
            background: white;
            padding: 15px;
            display: flex;
            justify-content: space-between; /* Separate left and right sections */
            align-items: center;
            border-bottom: 2px solid #ddd;
        }

        .filters .left-filters,
        .filters .right-filters {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-btn {
            background: #eee;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
        }

        .filter-btn.active {
            font-weight: bold;
        }


        /* Content Sections */
        .content {
            display: none;
            padding: 15px;
        }

        .content.active {
            display: block;
        }

        /* Job Listing */
        .job-listing {
            background: white;
            margin: 15px;
            padding: 15px;
            border-radius: 5px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            border-bottom: 2px solid #ddd;
            padding: 10px 0;
        }

        .job-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .job-status select {
            padding: 5px;
            border-radius: 4px;
        }

        .action-btn {
            background: #666;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .action-btn:hover {
            background: #444;
        }

        /* Applicants Table */
        .applicant-table {
            width: 100%;
            background: white;
            margin: 15px 0;
            border-radius: 5px;
            overflow: hidden;
            border-collapse: collapse;
        }

        .applicant-table th, .applicant-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .applicant-table th {
            background: #ddd;
            font-weight: bold;
        }

        .icon-actions {
            display: flex;
            gap: 10px;
        }

        .icon-btn {
            border: none;
            background: none;
            font-size: 18px;
            cursor: pointer;
        }
        /* Job Filters Section */
.job-filters {
    background: #ffffff;
    padding: 15px;
    margin: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Job Dropdown */
.job-position {
    font-size: 16px;
    padding: 8px;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 4px;
}

/* Filter Tabs */
.filter-tabs {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.tab-btn {
    padding: 8px 12px;
    border: 1px solid #ccc;
    background: #f4f4f4;
    border-radius: 4px;
    cursor: pointer;
}

.tab-btn.active {
    background: #d1d1d1;
    font-weight: bold;
}

/* Status Filters */
.status-filters {
    display: flex;
    gap: 15px;
    margin-top: 10px;
    font-size: 14px;
}

.status-filters span {
    cursor: pointer;
    color: #444;
}

.status-filters .status-link {
    color: blue;
    text-decoration: underline;
}

.status-filters .status-link.active {
    font-weight: bold;
}

/* Filters & Sorting */
.filter-controls {
    display: flex;
    justify-content: space-between;
    margin-top: 15px;
}

.filter-dropdown,
.sort-dropdown {
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 14px;
}

        .icon-btn.check { color: green; }
        .icon-btn.question { color: gray; }
        .icon-btn.close { color: red; }

        /* Hide job-filters by default */
        .job-filters.hidden {
            display: none;
        }
        /* Navbar Styling */
.navbar2 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #333;
    padding: 10px 20px;
    color: white;
}

/* Align right icons */
.nav-right {
    display: flex;
    gap: 20px; /* Space between icons */
}

.icon {
    color: white;
    font-size: 20px;
    text-decoration: none;
    transition: 0.3s;
}

.icon:hover {
    color: #f0a500;
}

/* Column layout for PESO for Company */
.navbar-brand div {
    display: flex;
    flex-direction: column;
}

    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-brand">
            <img src="../fortest/images/peso_icons.png" alt="PESO Logo">
            <div>
                <span style="font-size: 1.5rem; font-weight: bold;">PESO</span>
                <span style="font-size: 1.5rem; font-weight: bold; padding-left:30px;">for Company</span>
            </div>
        </div>
        <div class="navbar-icons">
            <i class="bx bx-bell"></i>
            <i class="bx bx-chat"></i>
            <i class="bx bx-user"></i>
        </div>
    </nav>

    <!-- Navigation Tabs -->
    <nav class="tabs">
        <button class="tab active" data-tab="jobs" onclick="switchTab('jobs')">Jobs</button>
        <button class="tab" data-tab="candidates" onclick="switchTab('candidates')">Candidates</button>
    </nav>

    <!-- Job Filters Section (Placed After Tabs) -->
    <section id="job-filters" class="job-filters hidden">
        <select class="job-position">
            <option>Customer Service Representative</option>
            <option>IT Support Specialist</option>
            <option>Sales Associate</option>
        </select>

        <div class="filter-tabs">
            <button class="tab-btn active">Applicants (17)</button>
            <button class="tab-btn">Matched Applicant</button>
        </div>

        <div class="status-filters">
            <span class="status-link active">17 Active</span>
            <span>12 Awaiting review</span>
            <span>2 Reviewed</span>
            <span>2 Contacted</span>
            <span>0 Hired</span>
            <span>22 Rejected</span>
        </div>

        <div class="filter-controls">
            <select class="filter-dropdown">
                <option>Screener questions: Any</option>
                <option>Answered</option>
                <option>Not Answered</option>
            </select>

            <select class="filter-dropdown">
                <option>Assessment: Any</option>
                <option>Passed</option>
                <option>Failed</option>
            </select>

            <select class="sort-dropdown">
                <option>Sort: Apply date (Newest)</option>
                <option>Sort: Apply date (Oldest)</option>
                <option>Sort: Relevance</option>
            </select>
        </div>
    </section>

    <!-- Jobs Tab -->
    <section id="jobs" class="content active">
        <!-- Filters -->
    <section class="filters">
        <div class="left-filters">
            <button class="filter-btn active">Open and Paused (#)</button>
            <button class="filter-btn">Closed (#)</button>
            <input type="text" placeholder="Search job title">
            <input type="text" placeholder="Search location">
        </div>
        <div class="right-filters">
            <button class="sort-btn">Sort by Posting Date</button>
            <button class="order-btn">Order: Descending</button>
        </div>
    </section>
        <div class="job-listing">
            <div class="table-header">
                <div>Job Title</div>
                <div>Candidates</div>
                <div>Job Status</div>
                <div>Action</div>
            </div>

            <div class="job-item">
                <div>
                    <strong>IT Executive Assistant</strong><br>
                    <small>San Jose, San Pablo City</small><br>
                    <small>Created: March 3 - Ends: March 31</small>
                </div>
                <div>
                    <span>25 Active</span> | 
                    <span>5 Awaiting</span> | 
                    <span>5 Reviewed</span> | 
                    <span>0 Contacting</span> | 
                    <span>0 of 5 Hired</span>
                </div>
                <div>
                    <select>
                        <option value="open" selected>🟢 Open</option>
                        <option value="paused">🟡 Paused</option>
                        <option value="closed">🔴 Closed</option>
                    </select>
                </div>
                <div>
                    <button class="action-btn">Actions ▼</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Candidates Tab -->
    <section id="candidates" class="content">
        <table class="applicant-table">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Job</th>
                    <th>Matches to job post</th>
                    <th>Recent Experience</th>
                    <th>Interested?</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox"></td>
                    <td><strong>Susan Kare</strong></td>
                    <td>Reviewed<br><span style="font-size: 12px;">Applied Mar 3</span></td>
                    <td>Customer Service Representative</td>
                    <td>
                        <span class="skill">Excel</span>
                        <span class="skill">SQL</span>
                        <span class="skill">Customer service: 4 yrs</span>
                    </td>
                    <td>
                        Customer Success Manager<br>
                        <span style="font-size: 12px;">Oracle - Jul 2015 - Present</span>
                    </td>
                    <td class="icon-actions">
                        <button class="icon-btn check"><i class="bx bx-check"></i></button>
                        <button class="icon-btn question"><i class="bx bx-question-mark"></i></button>
                        <button class="icon-btn close"><i class="bx bx-x"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </section>

    <script>
        function switchTab(tabId) {
            document.querySelectorAll('.content').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(button => button.classList.remove('active'));

            document.getElementById(tabId).classList.add('active');
            document.querySelector(`.tab[data-tab="${tabId}"]`).classList.add('active');

            // Hide job-filters section when in Job Tab
            if (tabId === 'jobs') {
                document.getElementById('job-filters').classList.add('hidden');
            } else {
                document.getElementById('job-filters').classList.remove('hidden');
            }
        }
    </script>   

</body>
</html>
