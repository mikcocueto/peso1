// Initialize dropdowns
        $(document).ready(function() {
            // Initialize Bootstrap dropdowns
            $('.dropdown-toggle').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                $(this).next('.dropdown-menu').toggleClass('show');
            });

            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });

            // Initialize mobile menu toggle
            $('.js-menu-toggle').click(function(e) {
                e.preventDefault();
                $('.site-menu').toggleClass('active');
            });

            // Initialize Bootstrap Select
            $('.selectpicker').selectpicker();
        });

        function showJobDetails(jobId) {
            const jobDetails = document.getElementById('job-details');
            const jobBoxes = document.querySelectorAll('.job-box');
            jobBoxes.forEach(box => box.classList.remove('selected-job'));
            document.getElementById('job-' + jobId).classList.add('selected-job');

            fetch('../includes/employee/emp_get_job_details.php?job_id=' + jobId)
                .then(response => response.text())
                .then(data => {
                    jobDetails.innerHTML = data;

                    // Leaflet map preview logic
                    setTimeout(function() {
                        const mapDiv = document.getElementById('job-location-map');
                        if (mapDiv && mapDiv.dataset.lat && mapDiv.dataset.lng) {
                            const lat = parseFloat(mapDiv.dataset.lat);
                            const lng = parseFloat(mapDiv.dataset.lng);

                            // Remove previous map instance if any
                            if (window.jobDetailsMap) {
                                window.jobDetailsMap.remove();
                                window.jobDetailsMap = null;
                            }
                            window.jobDetailsMap = L.map('job-location-map').setView([lat, lng], 15);
                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap contributors'
                            }).addTo(window.jobDetailsMap);
                            L.marker([lat, lng]).addTo(window.jobDetailsMap);
                        }
                    }, 100);

                    fetch('../includes/employee/emp_check_application.php?job_id=' + jobId)
                        .then(response => response.json())
                        .then(data => {
                            const applyButton = document.createElement('button');
                            if (data.applied) {
                                applyButton.textContent = 'Already Applied';
                                applyButton.classList.add('btn', 'btn-secondary');
                                applyButton.disabled = true;
                            } else {
                                applyButton.textContent = 'Apply';
                                applyButton.classList.add('btn', 'btn-primary');
                                applyButton.onclick = function() {
                                    showCVSelectionModal(jobId);
                                };
                            }
                            jobDetails.appendChild(applyButton);
                        });
                });
        }

        function showCVSelectionModal(jobId) {
            fetch('../includes/employee/emp_get_uploaded_cvs.php')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const modalBody = document.getElementById('cv-modal-body');
                    modalBody.innerHTML = '';

                    if (data.length === 0) {
                        modalBody.innerHTML = `
                            <tr>
                                <td colspan="2" class="text-center text-muted">
                                    You have no uploaded CVs.
                                    <a href="emp_dashboard.php" class="text-primary" style="text-decoration:underline;">Please upload a CV before applying.</a>
                                </td>
                            </tr>
                        `;
                    } else {
                        data.forEach(cv => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td><input type="checkbox" name="cv_files" value='${JSON.stringify(cv)}' onchange="limitSelection(this)"></td>
                                <td>${cv.cv_name}</td>
                            `;
                            modalBody.appendChild(row);
                        });
                    }

                    const sendButton = document.getElementById('send-application-btn');
                    sendButton.onclick = function () {
                        const selectedFiles = Array.from(document.querySelectorAll('input[name="cv_files"]:checked')).map(input => JSON.parse(input.value));
                        if (selectedFiles.length > 0) {
                            applyForJobWithCVs(jobId, selectedFiles);
                        } else {
                            alert('Please select at least one CV.');
                        }
                    };

                    const cvModal = new bootstrap.Modal(document.getElementById('cvModal'));
                    cvModal.show();
                })
                .catch(error => {
                    console.error('Error fetching CVs:', error);
                    alert('Failed to load CVs. Please try again later.');
                });
        }

        function limitSelection(checkbox) {
            const selected = document.querySelectorAll('input[name="cv_files"]:checked');
            if (selected.length > 5) {
                checkbox.checked = false;
                alert('You can select a maximum of 5 CVs.');
            }
        }

        function applyForJobWithCVs(jobId, selectedFiles) {
            fetch('../includes/employee/emp_apply_job.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ job_id: jobId, selected_files: selectedFiles })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Network response was not ok: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Application submitted successfully');
                    const cvModal = document.getElementById('cvModal');
                    const modalInstance = bootstrap.Modal.getOrCreateInstance(cvModal); // Use getOrCreateInstance for compatibility
                    modalInstance.hide();

                    // Reselect the job listing after modal closes
                    modalInstance._element.addEventListener('hidden.bs.modal', () => {
                        showJobDetails(jobId);
                    }, { once: true });
                } else {
                    console.error('Server error:', data.error, data.details || '');
                    alert(`${data.error}\nDetails:\n${(data.details || []).join('\n')}`);
                }
            })
            .catch(error => {
                console.error('Error submitting application:', error);
                alert('Failed to submit application. Please try again later.');
            });
        }