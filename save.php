<?php
require_once "connect.php";
header("Content-Type: application/json");

$pdo = Database::letsconnect();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ===== FETCH ALL =====
    case 'fetch':
        $rows = Database::GetAllData($pdo, "SELECT * FROM medical_personel ORDER BY name ASC");
        echo json_encode(["status" => "success", "data" => $rows]);
        break;

    // ===== ADD =====
    case 'add':
        $name       = trim($_POST['name'] ?? '');
        $role       = trim($_POST['role'] ?? '');
        $contact_no = trim($_POST['contact_no'] ?? '');
        $license_no = trim($_POST['license_no'] ?? '');

        if ($name === '' || $role === '' || $contact_no === '' || $license_no === '') {
            echo json_encode(["status" => "error", "message" => "All fields are required."]);
            exit;
        }

        // ===== Auto-generate the next Personel ID (PID-001, PID-002, ...) =====
        $last = Database::GetOneData(
            $pdo,
            "SELECT personel_id FROM medical_personel
             WHERE personel_id REGEXP '^PID-[0-9]+$'
             ORDER BY CAST(SUBSTRING(personel_id, 5) AS UNSIGNED) DESC
             LIMIT 1"
        );

        $nextNum = 1;
        if ($last && isset($last['personel_id'])) {
            $nextNum = (int) substr($last['personel_id'], 4) + 1;
        }
        $id = "PID-" . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $success = Database::ManageRecord(
            $pdo,
            "INSERT INTO medical_personel (personel_id, name, role, contact_no, license_no) VALUES (?, ?, ?, ?, ?)",
            [$id, $name, $role, $contact_no, $license_no]
        );

        if ($success) {
            echo json_encode(["status" => "success", "message" => "Personnel added successfully.", "personel_id" => $id]);
        } else {
            echo json_encode(["status" => "error", "message" => "Insert failed."]);
        }
        break;

    // ===== EDIT / UPDATE =====
    case 'edit':
        $original_id = trim($_POST['original_id'] ?? '');
        $id          = trim($_POST['personel_id'] ?? '');
        $name        = trim($_POST['name'] ?? '');
        $role        = trim($_POST['role'] ?? '');
        $contact_no  = trim($_POST['contact_no'] ?? '');
        $license_no  = trim($_POST['license_no'] ?? '');

        if ($id === '' || $name === '' || $role === '' || $contact_no === '' || $license_no === '') {
            echo json_encode(["status" => "error", "message" => "All fields are required."]);
            exit;
        }

        // if ID changed, make sure new ID isn't already taken by someone else
        if ($id !== $original_id) {
            $existing = Database::GetOneData(
                $pdo,
                "SELECT personel_id FROM medical_personel WHERE personel_id = ?",
                [$id]
            );
            if ($existing) {
                echo json_encode(["status" => "error", "message" => "Personel ID already exists."]);
                exit;
            }
        }

        $success = Database::ManageRecord(
            $pdo,
            "UPDATE medical_personel SET personel_id=?, name=?, role=?, contact_no=?, license_no=? WHERE personel_id=?",
            [$id, $name, $role, $contact_no, $license_no, $original_id]
        );

        if ($success) {
            echo json_encode(["status" => "success", "message" => "Personnel updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Update failed."]);
        }
        break;

    // ===== DELETE =====
    case 'delete':
        $id = trim($_POST['personel_id'] ?? '');

        $success = Database::ManageRecord(
            $pdo,
            "DELETE FROM medical_personel WHERE personel_id = ?",
            [$id]
        );

        if ($success) {
            echo json_encode(["status" => "success", "message" => "Personnel deleted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Delete failed."]);
        }
        break;

    default:
        echo json_encode(["status" => "error", "message" => "Invalid action."]);
}