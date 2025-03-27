<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Job</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <!-- Left Column: Create Job -->
        <div class="col-md-6">
            <h2>Create Job</h2>
            <form>
                <div class="form-group">
                    <label for="jobTitle">Job Title</label>
                    <input type="text" class="form-control" id="jobTitle" placeholder="Housekeeping Attendant">
                </div>
                <div class="form-group">
                    <label for="jobCategory">Job Category</label>
                    <select class="form-control" id="jobCategory">
                        <option>Housekeeping</option>
                        <!-- Add other categories as needed -->
                    </select>
                </div>
                <div class="form-group">
                    <label>Employment Type</label><br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="employmentType" value="part-time" checked>
                                <label class="form-check-label">Part-time</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="employmentType" value="full-time">
                                <label class="form-check-label">Full-time</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="employmentType" value="internship">
                                <label class="form-check-label">Internship</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="employmentType" value="contract">
                                <label class="form-check-label">Contract</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="deadline">Deadline</label>
                    <input type="date" class="form-control" id="deadline">
                </div>
                <div class="form-group">
                    <label for="numberOpenings">Number of Openings</label>
                    <input type="number" class="form-control" id="numberOpenings" value="15">
                </div>
                <div class="form-group">
                    <label for="startDate">Start Date</label>
                    <input type="date" class="form-control" id="startDate">
                </div>
                <div class="form-group">
                    <label for="endDate">End Date</label>
                    <input type="date" class="form-control" id="endDate">
                </div>
                <div class="form-group">
                    <label for="rateAmount">Rate Amount</label>
                    <input type="text" class="form-control" id="rateAmount" placeholder="THB 200 - THB 600 / Hour">
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" id="usePriceRange">
                        <label class="form-check-label" for="usePriceRange">Use price range</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="jobDescription">Job Description</label>
                    <textarea class="form-control" id="jobDescription" rows="5"></textarea>
                </div>
            </form>
        </div>

        <!-- Right Column: Employer Details -->
        <div class="col-md-6">
            <h4>Employer Details</h4>
            <form>
                <div class="form-group">
                    <label for="company">Company</label>
                    <select class="form-control" id="company">
                        <option>Royal Thai Retreats</option>
                        <!-- Add other companies as needed -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="branch">Branch</label>
                    <select class="form-control" id="branch">
                        <option>Phuket</option>
                        <!-- Add other branches as needed -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="contactPerson">Contact Person</label>
                    <input type="text" class="form-control" id="contactPerson" placeholder="Maria Tan">
                </div>
                <div class="form-group">
                    <label for="phoneNumber">Phone Number</label>
                    <input type="tel" class="form-control" id="phoneNumber" placeholder="+66 8895-93899">
                </div>
                <div class="form-group">
                    <label for="addressLocation">Address Location</label>
                    <textarea class="form-control" id="addressLocation" rows="2" placeholder="41, 30 Soi Aonui..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>