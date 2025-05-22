function handleJobSelection(jobId) {
    if (!jobId) {
        resetCandidateList();
        return;
    }

    // Fetch all candidates first
    fetchAllCandidates(jobId);

    // Then fetch candidates for each status
    const statuses = ['applied', 'awaiting', 'reviewed', 'contacted', 'hired'];
    statuses.forEach(status => {
        fetchCandidatesForStatus(jobId, status);
    });
}

function resetCandidateList() {
    // Reset all tab
    document.getElementById('allCandidatesTable').innerHTML = `
        <tr>
            <td colspan="5" class="text-center py-5">
                <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                <h4 class="text-muted">Select a job posting to view candidates</h4>
                <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
            </td>
        </tr>
    `;

    // Reset other tabs
    const statuses = ['applied', 'awaiting', 'reviewed', 'contacted', 'hired'];
    statuses.forEach(status => {
        document.getElementById(`${status}List`).innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="fas fa-briefcase mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                <h4 class="text-muted">Select a job posting to view candidates</h4>
                <p class="text-muted">Choose a job from the dropdown above to see the list of candidates</p>
            </div>
        `;
    });
}

function fetchAllCandidates(jobId) {
    fetch(`../includes/company/comp_candidates_fetch.php?job_id=${jobId}&status=all`)
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('allCandidatesTable');
            
            if (data.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-users mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                            <h4 class="text-muted">No candidates found</h4>
                            <p class="text-muted">There are no candidates for this job posting</p>
                        </td>
                    </tr>
                `;
            } else {
                tableBody.innerHTML = data.map(candidate => `
                    <tr>
                        <td>${candidate.job_title}</td>
                        <td>${candidate.firstName} ${candidate.lastName}</td>
                        <td>${new Date(candidate.application_time).toLocaleString()}</td>
                        <td><span class="badge bg-${getStatusColor(candidate.status)}">${capitalizeFirst(candidate.status)}</span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary" onclick="viewCandidateProfile(${candidate.application_id})">
                                View Profile
                            </button>
                        </td>
                    </tr>
                `).join('');
            }
        })
        .catch(error => {
            console.error('Error fetching all candidates:', error);
            document.getElementById('allCandidatesTable').innerHTML = `
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="fas fa-exclamation-circle mb-3" style="font-size: 3rem; color: #dc3545;"></i>
                        <h4 class="text-danger">Error loading candidates</h4>
                        <p class="text-muted">Please try again later</p>
                    </td>
                </tr>
            `;
        });
}

function fetchCandidatesForStatus(jobId, status) {
    fetch(`../includes/company/comp_candidates_fetch.php?job_id=${jobId}&status=${status}`)
        .then(response => response.json())
        .then(data => {
            const candidateList = document.getElementById(`${status}List`);
            
            if (data.length === 0) {
                candidateList.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-users mb-3" style="font-size: 3rem; color: #6c757d;"></i>
                        <h4 class="text-muted">No ${status} candidates</h4>
                        <p class="text-muted">There are no candidates in this status</p>
                    </div>
                `;
            } else {
                candidateList.innerHTML = data.map(candidate => `
                    <div class="col-md-6 col-lg-4">
                        <div class="card candidate-card">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="candidate-avatar me-3">
                                        <i class="fas fa-user-circle" style="font-size: 2.5rem; color: #6c757d;"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">${candidate.firstName} ${candidate.lastName}</h5>
                                        <p class="text-muted mb-0">${candidate.emailAddress}</p>
                                        <small class="text-muted">Applied: ${new Date(candidate.application_time).toLocaleDateString()}</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-${getStatusColor(candidate.status)} status-badge">${capitalizeFirst(candidate.status)}</span>
                                    <div>
                                        <span class="badge bg-light text-dark me-2">
                                            <i class="fas fa-file-alt me-1"></i>${candidate.file_count} Files
                                        </span>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewCandidateProfile(${candidate.application_id})">
                                            View Profile
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        })
        .catch(error => {
            console.error('Error fetching candidates:', error);
            document.getElementById(`${status}List`).innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="fas fa-exclamation-circle mb-3" style="font-size: 3rem; color: #dc3545;"></i>
                    <h4 class="text-danger">Error loading candidates</h4>
                    <p class="text-muted">Please try again later</p>
                </div>
            `;
        });
}

function getStatusColor(status) {
    const colors = {
        'applied': 'success',
        'awaiting': 'primary',
        'reviewed': 'secondary',
        'contacted': 'info',
        'hired': 'warning',
        'rejected': 'danger'
    };
    return colors[status] || 'secondary';
}

function capitalizeFirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function viewCandidateProfile(applicationId) {
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('candidateProfileModal'));
    modal.show();

    // Show loading spinner and hide content
    document.getElementById('profileLoadingSpinner').style.display = 'block';
    document.getElementById('profileContent').style.display = 'none';

    // Fetch candidate profile data
    fetch(`../includes/company/comp_candidate_profile.php?application_id=${applicationId}`)
        .then(response => response.json())
        .then(data => {
            // Hide loading spinner and show content
            document.getElementById('profileLoadingSpinner').style.display = 'none';
            document.getElementById('profileContent').style.display = 'block';

            // Update basic information
            document.getElementById('candidateName').textContent = data.basic_info.name;
            document.getElementById('candidateEmail').textContent = data.basic_info.email;
            document.getElementById('candidatePhone').textContent = data.basic_info.phone;
            document.getElementById('candidateAddress').textContent = data.basic_info.address;
            document.getElementById('candidateAge').textContent = `${data.basic_info.age} years old`;
            document.getElementById('candidateGender').textContent = data.basic_info.gender;
            document.getElementById('candidateEducation').textContent = data.basic_info.education;
            document.getElementById('candidateExperience').textContent = data.basic_info.experience;

            // Update resumes list
            const resumeList = document.getElementById('resumeList');
            resumeList.innerHTML = data.resumes.map(resume => `
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-file-pdf me-2 text-danger"></i>
                        ${resume.name}
                    </div>
                    <button class="btn btn-sm btn-primary" onclick="previewResume('${resume.dir}')">
                        <i class="fas fa-eye me-1"></i> Preview
                    </button>
                </div>
            `).join('') || '<p class="text-muted mb-0">No resumes submitted</p>';

            // Update education list
            const educationList = document.getElementById('educationList');
            educationList.innerHTML = data.education.map(edu => `
                <div class="mb-3">
                    <h6 class="mb-1">${edu.course}</h6>
                    <p class="text-muted mb-1">${edu.institution}</p>
                    <p class="text-muted mb-0">Ended: ${new Date(edu.ending_date).toLocaleDateString()}</p>
                    ${edu.course_highlights ? `<p class="mt-1">${edu.course_highlights}</p>` : ''}
                </div>
            `).join('') || '<p class="text-muted mb-0">No education history available</p>';

            // Update experience list
            const experienceList = document.getElementById('experienceList');
            experienceList.innerHTML = data.experience.map(exp => `
                <div class="mb-3">
                    <h6 class="mb-1">${exp.job_title}</h6>
                    <p class="text-muted mb-1">${exp.company_name}</p>
                    <p class="text-muted mb-0">
                        ${new Date(exp.start_date).toLocaleDateString()} - 
                        ${exp.still_in_role ? 'Present' : new Date(exp.end_date).toLocaleDateString()}
                    </p>
                    ${exp.description ? `<p class="mt-1">${exp.description}</p>` : ''}
                </div>
            `).join('') || '<p class="text-muted mb-0">No work experience available</p>';

            // Update skills list
            const skillsList = document.getElementById('skillsList');
            skillsList.innerHTML = data.skills.map(skill => `
                <span class="badge bg-light text-dark me-2 mb-2">${skill}</span>
            `).join('') || '<p class="text-muted mb-0">No skills listed</p>';

            // Update languages list
            const languagesList = document.getElementById('languagesList');
            languagesList.innerHTML = data.languages.map(language => `
                <span class="badge bg-light text-dark me-2 mb-2">${language}</span>
            `).join('') || '<p class="text-muted mb-0">No languages listed</p>';
        })
        .catch(error => {
            console.error('Error fetching candidate profile:', error);
            document.getElementById('profileLoadingSpinner').style.display = 'none';
            document.getElementById('profileContent').innerHTML = `
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-circle mb-3" style="font-size: 3rem; color: #dc3545;"></i>
                    <h4 class="text-danger">Error loading profile</h4>
                    <p class="text-muted">Please try again later</p>
                </div>
            `;
        });
}

function previewResume(resumeDir) {
    // Show the resume preview modal
    const modal = new bootstrap.Modal(document.getElementById('resumePreviewModal'));
    modal.show();

    // Show loading spinner and hide iframe
    document.getElementById('resumePreviewLoading').style.display = 'block';
    document.getElementById('resumePreviewFrame').style.display = 'none';

    // Set the iframe source with the correct path
    const iframe = document.getElementById('resumePreviewFrame');
    iframe.onload = function() {
        document.getElementById('resumePreviewLoading').style.display = 'none';
        iframe.style.display = 'block';
    };
    // Use the correct relative path from the script location
    iframe.src = `../../db/pdf/application_files/${resumeDir}`;
}

function toggleProfileDropdown() {
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('show');
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
    if (!event.target.matches('.profile-btn') && !event.target.matches('.profile-btn *')) {
        const dropdowns = document.getElementsByClassName('profile-dropdown-content');
        for (let dropdown of dropdowns) {
            if (dropdown.classList.contains('show')) {
                dropdown.classList.remove('show');
            }
        }
    }
}
