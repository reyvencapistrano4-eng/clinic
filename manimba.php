<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clinic Visit - Patient Visit Record</title>
<link rel="stylesheet" href="style.css">
<script src="jquery-min.js"></script>
</head>
<body>

<div id="msg"></div>

<div class="card">
  <div class="header">
    <h1>Clinic Visit</h1>
    <div class="lrn-search-wrap">
      <input type="text" id="lrn_search" placeholder="Search LRN or name..." autocomplete="off">
      <div id="lrn_search_results" class="search-results"></div>
    </div>
  </div>
  <p style="color:var(--green); margin-top:-8px;">Patient Visit Record</p>

  <div class="grid">
    <div>
      <label for="visit_id">Visit ID:</label>
      <input type="text" id="visit_id" placeholder="Auto numbering" readonly>
    </div>
    <div>
      <label for="lrn">LRN:</label>
      <input type="text" id="lrn" placeholder="Enter LRN">
    </div>
    <div>
      <label for="personel_id">Personnel ID:</label>
      <input type="text" id="personel_id" placeholder="Enter Personnel ID">
    </div>
  </div>

  <div class="grid" style="grid-template-columns: 1fr;">
    <div>
      <label for="visit_date">Date:</label>
      <input type="date" id="visit_date">
    </div>
  </div>
</div>

<div class="card">
  <h2>Prescription Details</h2>

  <div class="rx-grid">
    <div>
      <label for="rx_id">Prescription ID</label>
      <input type="text" id="rx_id" placeholder="Auto numbering" readonly>
    </div>
    <div>
      <label for="rx_needs">Needs</label>
      <input type="text" id="rx_needs" placeholder="Enter needs">
    </div>
    <div>
      <label for="rx_dosage">Dosage</label>
      <input type="text" id="rx_dosage" placeholder="Enter dosage">
    </div>
    <div>
      <label for="rx_instruction">Instruction</label>
      <input type="text" id="rx_instruction" placeholder="Enter instruction">
    </div>
  </div>

  <button class="btn btn-add" id="addBtn" type="button">Add &rarr;</button>

  <table>
    <thead>
      <tr><th>ID</th><th>Needs</th><th>Dosage</th><th>Instruction</th><th>Action</th></tr>
    </thead>
    <tbody id="rxTableBody"></tbody>
  </table>
</div>

<div class="card">
  <button class="btn btn-save" id="saveBtn" type="button">SAVE &rarr;</button>
</div>

<div class="overlay" id="confirmOverlay">
  <div class="modal">
    <h3>Save this visit?</h3>
    <p>This will save the visit details and all prescriptions listed above to the database. This cannot be undone from here.</p>
    <div class="modal-actions">
      <button class="btn btn-cancel" id="cancelBtn" type="button">Cancel</button>
      <button class="btn btn-confirm" id="confirmBtn" type="button">Confirm</button>
    </div>
  </div>
</div>

<script src="java.js"></script>

</body>
</html>