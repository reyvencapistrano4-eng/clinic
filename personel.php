<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Medical Personnel Management</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="page">

    <div class="page-header">
        <h1>Medical Personnel Management</h1>
        <p>Record and manage clinic doctors, nurses, and staff.</p>
    </div>

    <!-- ===== Add / Edit Card ===== -->
    <div class="card">
        <div class="card-header">
            <span id="formTitle">Add Personnel</span>
        </div>

        <div class="card-body">
            <form id="personelForm">
                <input type="hidden" id="mode" value="add">
                <input type="hidden" id="original_id" value="">

                <div class="field-grid">
                    <div class="field">
                        <label for="personel_id">Personel ID</label>
                        <input type="text" id="personel_id" placeholder="Generating..." readonly>
                    </div>

                    <div class="field">
                        <label for="name">Name</label>
                        <input type="text" id="name" placeholder="Full name" required maxlength="100">
                    </div>

                    <div class="field">
                        <label for="role">Role</label>
                        <select id="role" required>
                            <option value="">Select role</option>
                            <option value="doctor">Doctor</option>
                            <option value="nurse">Nurse</option>
                            <option value="admin">Admin Staff</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="contact_no">Contact No.</label>
                        <input type="text" id="contact_no" placeholder="09XXXXXXXXX" required maxlength="20">
                    </div>

                    <div class="field field-wide">
                        <label for="license_no">License No.</label>
                        <input type="text" id="license_no" placeholder="License number" required maxlength="50">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" id="btnCancel" class="btn btn-cancel" hidden>Cancel Edit</button>
                    <button type="submit" id="btnSubmit" class="btn btn-add">Add Personnel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== List Card ===== -->
    <div class="card">
        <div class="card-header card-header--list">
            <span>Personnel List</span>
            <span class="badge" id="personelCount">0 Personnel</span>
        </div>

        <table class="personel-table" id="personelTable">
            <thead>
                <tr>
                    <th>Personel ID</th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Contact No.</th>
                    <th>License No.</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="personelBody">
                <!-- rows injected by personel.js -->
            </tbody>
        </table>
    </div>

</div>

<script src="personel.js"></script>
</body>
</html>