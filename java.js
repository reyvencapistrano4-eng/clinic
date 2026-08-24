let prescriptions = [];
let rxCounter = 1;
let editingIndex = null;

// =====================================================
// INITIAL LOAD - fetch the next Visit ID, default date to today
// =====================================================
LoadVisitId();
$("#visit_date").val(new Date().toISOString().split('T')[0]);

function LoadVisitId() {
    $.post("save.php", {
        func_name: "VisitAutoNumbering"
    }, function(response) {
        $("#visit_id").val(response);
    });
}

function nextRxIdPreview() {
    return 'RX-' + String(rxCounter).padStart(6, '0');
}

function renderTable() {
    $("#rxTableBody").empty();
    prescriptions.forEach((rx, i) => {
        let row = `
            <tr>
                <td>${rx.id}</td>
                <td>${rx.needs}</td>
                <td>${rx.dosage}</td>
                <td>${rx.instruction}</td>
                <td>
                    <button class="btn-edit" onclick="editRow(${i})">EDIT</button>
                    <button class="btn-delete" onclick="deleteRow(${i})">DELETE</button>
                </td>
            </tr>`;
        $("#rxTableBody").append(row);
    });
}

$("#addBtn").on("click", function() {
    const needs = $("#rx_needs").val().trim();
    const dosage = $("#rx_dosage").val().trim();
    const instruction = $("#rx_instruction").val().trim();

    if (!needs || !dosage || !instruction) {
        showMsg('Please fill in Needs, Dosage, and Instruction.', 'error');
        return;
    }

    if (editingIndex !== null) {
        prescriptions[editingIndex] = { ...prescriptions[editingIndex], needs, dosage, instruction };
        editingIndex = null;
    } else {
        prescriptions.push({ id: nextRxIdPreview(), needs, dosage, instruction });
        rxCounter++;
    }

    $("#rx_needs, #rx_dosage, #rx_instruction, #rx_id").val('');
    renderTable();
});

function editRow(i) {
    const rx = prescriptions[i];
    $("#rx_id").val(rx.id);
    $("#rx_needs").val(rx.needs);
    $("#rx_dosage").val(rx.dosage);
    $("#rx_instruction").val(rx.instruction);
    editingIndex = i;
}

function deleteRow(i) {
    prescriptions.splice(i, 1);
    renderTable();
}

function showMsg(text, type) {
    $("#msg").text(text).attr("class", type);
}

// =====================================================
// LRN SEARCH (upper-right search bar) - search-as-you-type
// =====================================================
let lrnSearchTimer = null;

$("#lrn_search").on("input", function() {
    const q = $(this).val().trim();
    clearTimeout(lrnSearchTimer);

    if (q.length < 2) {
        $("#lrn_search_results").removeClass("show").empty();
        return;
    }

    lrnSearchTimer = setTimeout(() => {
        $.post("save.php", {
            func_name: "SearchLRN",
            q: q
        }, function(response) {
            let data;
            try {
                data = JSON.parse(response);
            } catch (e) {
                data = [];
            }
            renderLrnSearchResults(data);
        }).fail(function() {
            renderLrnSearchResults([]);
        });
    }, 250);
});

function renderLrnSearchResults(results) {
    const $box = $("#lrn_search_results");
    $box.empty();

    if (results && results.error) {
        // TEMP DEBUG: shows the real MySQL error (bad table/column name, etc.)
        $box.append(`<div class="no-results">DB error: ${results.error}</div>`);
        $box.addClass("show");
        return;
    }

    if (!results || results.length === 0) {
        $box.append(`<div class="no-results">No matches found.</div>`);
        $box.addClass("show");
        return;
    }

    results.forEach(r => {
        const $item = $(`
            <div class="result-item">
                <span class="r-lrn">${r.LRN}</span>
                <span class="r-name">${r.name}</span>
            </div>`);
        $item.on("click", function() {
            $("#lrn").val(r.LRN);
            $("#lrn_search").val("");
            $box.removeClass("show").empty();
        });
        $box.append($item);
    });

    $box.addClass("show");
}

// Close the dropdown when clicking outside of it
$(document).on("click", function(e) {
    if (!$(e.target).closest(".lrn-search-wrap").length) {
        $("#lrn_search_results").removeClass("show");
    }
});

// =====================================================
// SAVE FLOW - validates and opens the confirm dialog.
// Nothing is sent to the server yet.
// =====================================================
$("#saveBtn").on("click", function() {
    const lrn = $("#lrn").val().trim();
    const personelId = $("#personel_id").val().trim();

    if (!lrn && !personelId) {
        showMsg('Enter at least an LRN or a Personnel ID.', 'error');
        return;
    }
    if (prescriptions.length === 0) {
        showMsg('Add at least one prescription before saving.', 'error');
        return;
    }

    $("#confirmOverlay").addClass("show");
});

$("#cancelBtn").on("click", function() {
    $("#confirmOverlay").removeClass("show");
});

// =====================================================
// CONFIRM - the only action that writes to the database
// =====================================================
$("#confirmBtn").on("click", function() {
    const lrn = $("#lrn").val().trim();
    const personelId = $("#personel_id").val().trim();
    const visitDate = $("#visit_date").val();

    $("#confirmBtn").prop("disabled", true).text("Saving...");

    $.post("save.php", {
        func_name: "SaveVisit",
        lrn: lrn,
        personelId: personelId,
        visitDate: visitDate,
        prescriptions: JSON.stringify(prescriptions)
    }, function(response) {
        let data = JSON.parse(response);

        $("#confirmOverlay").removeClass("show");
        $("#confirmBtn").prop("disabled", false).text("Confirm");

        if (data.status === "success") {
            showMsg('Visit saved successfully. Visit ID: ' + data.visit_id, 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            showMsg('Error: ' + data.message, 'error');
        }
    }).fail(function(xhr) {
        $("#confirmOverlay").removeClass("show");
        $("#confirmBtn").prop("disabled", false).text("Confirm");
        showMsg('Request failed: ' + xhr.statusText, 'error');
    });
});