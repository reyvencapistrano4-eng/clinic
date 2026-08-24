document.addEventListener("DOMContentLoaded", () => {
    const form            = document.getElementById("personelForm");
    const formTitle        = document.getElementById("formTitle");
    const modeField        = document.getElementById("mode");
    const originalIdField  = document.getElementById("original_id");
    const btnSubmit        = document.getElementById("btnSubmit");
    const btnCancel        = document.getElementById("btnCancel");
    const tbody            = document.getElementById("personelBody");
    const countBadge       = document.getElementById("personelCount");

    const idField       = document.getElementById("personel_id");
    const nameField      = document.getElementById("name");
    const roleField      = document.getElementById("role");
    const contactField   = document.getElementById("contact_no");
    const licenseField   = document.getElementById("license_no");

    let cachedRows = [];

    // ===== Load table on page load =====
    fetchPersonel();

    function fetchPersonel() {
        fetch("save.php?action=fetch")
            .then(res => res.json())
            .then(res => {
                if (res.status === "success") {
                    cachedRows = res.data;
                    renderTable(cachedRows);
                    if (modeField.value === "add") {
                        idField.value = computeNextId(cachedRows);
                    }
                } else {
                    alert(res.message || "Failed to load data.");
                }
            })
            .catch(err => console.error("Fetch error:", err));
    }

    // ===== Work out the next Personel ID (PID-001, PID-002, ...) =====
    function computeNextId(rows) {
        let max = 0;
        rows.forEach(r => {
            const match = /^PID-(\d+)$/.exec(r.personel_id || "");
            if (match) {
                const num = parseInt(match[1], 10);
                if (num > max) max = num;
            }
        });
        return "PID-" + String(max + 1).padStart(3, "0");
    }

    function renderTable(rows) {
        tbody.innerHTML = "";
        countBadge.textContent = `${rows.length} Personnel`;

        if (rows.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="empty">No personnel records found.</td></tr>`;
            return;
        }

        rows.forEach(row => {
            const tr = document.createElement("tr");
            tr.innerHTML = `
                <td>${escapeHtml(row.personel_id)}</td>
                <td>${escapeHtml(row.name)}</td>
                <td>${escapeHtml(row.role)}</td>
                <td>${escapeHtml(row.contact_no)}</td>
                <td>${escapeHtml(row.license_no)}</td>
                <td class="actions">
                    <button class="btn-edit" data-id="${escapeHtml(row.personel_id)}">Edit</button>
                    <button class="btn-delete" data-id="${escapeHtml(row.personel_id)}">Delete</button>
                </td>
            `;
            tbody.appendChild(tr);
        });

        document.querySelectorAll(".btn-edit").forEach(btn => {
            btn.addEventListener("click", () => loadForEdit(btn.dataset.id));
        });
        document.querySelectorAll(".btn-delete").forEach(btn => {
            btn.addEventListener("click", () => deletePersonel(btn.dataset.id));
        });
    }

    function escapeHtml(str) {
        const div = document.createElement("div");
        div.textContent = str ?? "";
        return div.innerHTML;
    }

    // ===== Load a record into the form for editing =====
    function loadForEdit(id) {
        const record = cachedRows.find(r => r.personel_id === id);
        if (!record) return;

        formTitle.textContent = "Edit Personnel";
        modeField.value = "edit";
        originalIdField.value = record.personel_id;

        idField.value = record.personel_id;
        nameField.value = record.name;
        roleField.value = record.role;
        contactField.value = record.contact_no;
        licenseField.value = record.license_no;

        btnSubmit.textContent = "Update Personnel";
        btnCancel.hidden = false;

        window.scrollTo({ top: 0, behavior: "smooth" });
    }

    // ===== Reset form back to "Add" mode =====
    function resetForm() {
        form.reset();
        modeField.value = "add";
        originalIdField.value = "";
        formTitle.textContent = "Add Personnel";
        btnSubmit.textContent = "Add Personnel";
        btnCancel.hidden = true;
        idField.value = computeNextId(cachedRows);
    }

    btnCancel.addEventListener("click", resetForm);

    // ===== Submit Form (Add or Edit) =====
    form.addEventListener("submit", e => {
        e.preventDefault();

        const formData = new FormData();
        formData.append("action", modeField.value);
        formData.append("original_id", originalIdField.value);
        formData.append("personel_id", idField.value.trim());
        formData.append("name", nameField.value.trim());
        formData.append("role", roleField.value);
        formData.append("contact_no", contactField.value.trim());
        formData.append("license_no", licenseField.value.trim());

        fetch("save.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(res => {
                if (res.status === "success") {
                    resetForm();
                    fetchPersonel();
                } else {
                    alert(res.message || "Something went wrong.");
                }
            })
            .catch(err => console.error("Save error:", err));
    });

    // ===== Delete =====
    function deletePersonel(id) {
        if (!confirm(`Delete personnel record ${id}? This cannot be undone.`)) return;

        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("personel_id", id);

        fetch("save.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(res => {
                if (res.status === "success") {
                    fetchPersonel();
                } else {
                    alert(res.message || "Delete failed.");
                }
            })
            .catch(err => console.error("Delete error:", err));
    }
});