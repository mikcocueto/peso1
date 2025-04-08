<?php
session_start();
include '../includes/db_connect.php';

if (!isset($_SESSION['company_id'])) {
    header("Location: comp_login.php");
    die();
}

$company_id = $_SESSION['company_id'];

$query = "SELECT firstName, lastName, companyName, country, companyNumber, comp_logo_dir FROM tbl_comp_info WHERE company_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $company_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<body class="bg-gray-100">
    <?php include 'comp_navbar&tab.php'; ?>
   
    <main class="p-4">
        <div class="flex space-x-4">
            <!-- Welcome Section -->
            <div class="bg-white p-4 border rounded shadow w-1/4">
                <img id="logoPreview" src="<?php echo $company['comp_logo_dir'] ? htmlspecialchars($company['comp_logo_dir']) : 'path/to/placeholder.png'; ?>" 
                     alt="Company Logo" class="mx-auto mb-4" width="100" height="100">
                <p class="text-center font-semibold">Welcome, <?php echo htmlspecialchars($company['firstName'] . ' ' . $company['lastName']); ?></p>
            </div>

            <!-- Company Information Section -->
            <div class="bg-white p-4 border rounded shadow w-3/4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Company Information</h3>
                    <button id="editButton" class="bg-blue-500 text-white px-4 py-2 rounded">Edit</button>
                </div>
                <div id="infoSection" class="space-y-2">
                    <p><span class="font-semibold">First Name:</span> <?php echo htmlspecialchars($company['firstName']); ?></p>
                    <p><span class="font-semibold">Last Name:</span> <?php echo htmlspecialchars($company['lastName']); ?></p>
                    <p><span class="font-semibold">Company Name:</span> <?php echo htmlspecialchars($company['companyName']); ?></p>
                    <p><span class="font-semibold">Country:</span> <?php echo htmlspecialchars($company['country']); ?></p>
                    <p><span class="font-semibold">Company Number:</span> <?php echo htmlspecialchars($company['companyNumber']); ?></p>
                </div>

                <!-- Edit Form (Hidden by Default) -->
                <form id="editForm" method="POST" action="includes/company/update_company_info.php" enctype="multipart/form-data" class="hidden">
                    <div class="space-y-4">
                        <div>
                            <label for="firstName" class="block font-semibold">First Name</label>
                            <input type="text" id="firstName" name="firstName" value="<?php echo htmlspecialchars($company['firstName']); ?>" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label for="lastName" class="block font-semibold">Last Name</label>
                            <input type="text" id="lastName" name="lastName" value="<?php echo htmlspecialchars($company['lastName']); ?>" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label for="companyName" class="block font-semibold">Company Name</label>
                            <input type="text" id="companyName" name="companyName" value="<?php echo htmlspecialchars($company['companyName']); ?>" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label for="country" class="block font-semibold">Country</label>
                            <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($company['country']); ?>" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label for="companyNumber" class="block font-semibold">Company Number</label>
                            <input type="text" id="companyNumber" name="companyNumber" value="<?php echo htmlspecialchars($company['companyNumber']); ?>" class="w-full border rounded px-3 py-2">
                        </div>
                        <div>
                            <label for="companyLogo" class="block font-semibold">Company Logo</label>
                            <input type="file" id="companyLogo" name="companyLogo" class="w-full border rounded px-3 py-2">
                        </div>
                    </div>
                    <div class="flex justify-end space-x-4 mt-4">
                        <button type="button" id="cancelButton" class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        const editButton = document.getElementById('editButton');
        const cancelButton = document.getElementById('cancelButton');
        const infoSection = document.getElementById('infoSection');
        const editForm = document.getElementById('editForm');

        editButton.addEventListener('click', () => {
            infoSection.classList.add('hidden');
            editForm.classList.remove('hidden');
        });

        cancelButton.addEventListener('click', () => {
            editForm.classList.add('hidden');
            infoSection.classList.remove('hidden');
        });

        // Prevent form submission on Enter key
        editForm.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') e.preventDefault();
        });

        // Preview logo before upload
        document.getElementById('companyLogo').addEventListener('change', function (e) {
            const reader = new FileReader();
            reader.onload = function () {
                document.getElementById('logoPreview').src = reader.result;
            };
            reader.readAsDataURL(e.target.files[0]);
        });
    </script>

    <footer class="p-4 flex justify-end">
        <form method="POST" action="includes/company/comp_logout.php">
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
        </form>
    </footer>
</body>
</html>
