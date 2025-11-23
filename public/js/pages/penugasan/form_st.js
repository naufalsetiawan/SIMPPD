

    function submitForm() {
        document.getElementById("formPenugasan").submit();
    }

    let index = window.indexValue;

    document.addEventListener("DOMContentLoaded", initializeDraggableRows());
    document.getElementById("addMenimbang").addEventListener("click", function() {
        let tableBody = document.getElementById("tableBody");
        let newRow = document.createElement("tr");
        newRow.classList.add('draggable-row'); // Add draggable class
        newRow.setAttribute('draggable', true); // Enable drag for the row

        newRow.innerHTML = `
            <td class="px-6 py-3 border w-10 indexCell">${index}</td>
            <td class="px-6 py-3 border">
                <textarea name="menimbang[]" class="border-none p-0 w-full resize-none" rows="3" placeholder="Ketikan Butir"></textarea>
            </td>
            <td class="px-6 py-3 border w-16 text-center">
                <button type="button" id="deleteFileBtn"
                onclick="removeRow(this)"
                class="text-red-500">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(newRow);
        index++;
        updateIndexes();
        initializeDraggableRows();
    });

    function removeRow(button) {
        button.closest("tr").remove();
        updateIndexes();
    }

    function updateIndexes() {
        let indexCells = document.querySelectorAll(".indexCell");
        let alphabet = 'a'.charCodeAt(0); // Start from 'a'

        indexCells.forEach((cell, i) => {
            cell.textContent = String.fromCharCode(alphabet + i);
        });
    }

    // Initialize the drag-and-drop functionality
    function initializeDraggableRows() {
        const rows = document.querySelectorAll(".draggable-row");
        const tbody = document.querySelector("#menimbangTable tbody"); // Correct the table ID here
        let draggedRow = null;

        rows.forEach(row => {
            row.addEventListener("dragstart", (e) => {
                draggedRow = row;
                e.dataTransfer.effectAllowed = "move";
                row.classList.add("opacity-50");
            });

            row.addEventListener("dragover", (e) => {
                e.preventDefault();
                const bounding = row.getBoundingClientRect();
                const offset = e.clientY - bounding.top + (bounding.height / 2);
                if (offset > 0) {
                    row.style["border-bottom"] = "2px solid #00f";
                } else {
                    row.style["border-top"] = "2px solid #00f";
                }
            });

            row.addEventListener("dragleave", () => {
                row.style["border-top"] = "";
                row.style["border-bottom"] = "";
            });

            row.addEventListener("drop", () => {
                row.style["border-top"] = "";
                row.style["border-bottom"] = "";

                // Ensure the draggedRow is not the same as the target row
                if (draggedRow !== row) {
                    const rows = Array.from(tbody.children);
                    const draggedIndex = rows.indexOf(draggedRow);
                    const targetIndex = rows.indexOf(row);

                    // Reorder rows based on drag-and-drop
                    if (draggedIndex > targetIndex) {
                        tbody.insertBefore(draggedRow, row);
                    } else {
                        tbody.insertBefore(draggedRow, row.nextSibling);
                    }


                    updateIndexes(); // Update the row numbers
                }
            });

            row.addEventListener("dragend", () => {
                row.classList.remove("opacity-50");
            });
        });
        // Update row numbers initially
        updateIndexes();
    }

    // Call updateIndexes initially to set the correct order
    updateIndexes();

    // Start from the last used index
    document.addEventListener("DOMContentLoaded", initializeDraggableRowsD());

    document.getElementById("addDasar").addEventListener("click", function() {
        let tableBody = document.getElementById("tableBodyD");
        let newRow = document.createElement("tr");
        newRow.classList.add('draggable-row'); // Add draggable class
        newRow.setAttribute('draggable', true); // Enable drag for the row

        newRow.innerHTML = `
            <td class="px-6 py-3 border w-10 indexCellD"> ${index++}</td>
            <td class="px-6 py-3 border">
                <textarea name="dasar[]" class="border-none p-0 w-full resize-none" rows="3" placeholder="Ketikan Butir"></textarea>
            </td>
            <td class="px-6 py-3 border w-16 text-center">
                <button type="button" id="deleteFileBtn"
                onclick="removeRow(this)"
                class="text-red-500">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </td>
        `;

        tableBody.appendChild(newRow);
        index++;
        updateIndexesD();
        initializeDraggableRowsD();
    });

    function removeRowD(button) {
        button.closest("tr").remove();
        updateIndexesD();
    }

    function updateIndexesD() {
        let indexCells = document.querySelectorAll(".indexCellD");

        indexCells.forEach((cell, i) => {
            cell.textContent = i + 1; // Use numbers starting from 1
        });
    }
    // Initialize the drag-and-drop functionality
    function initializeDraggableRowsD() {
        const rows = document.querySelectorAll(".draggable-row");
        const tbody = document.querySelector("#dasarTable tbody"); // Correct the table ID here
        let draggedRow = null;

        rows.forEach(row => {
            row.addEventListener("dragstart", (e) => {
                draggedRow = row;
                e.dataTransfer.effectAllowed = "move";
                row.classList.add("opacity-50");
            });

            row.addEventListener("dragover", (e) => {
                e.preventDefault();
                const bounding = row.getBoundingClientRect();
                const offset = e.clientY - bounding.top + (bounding.height / 2);
                if (offset > 0) {
                    row.style["border-bottom"] = "2px solid #00f";
                } else {
                    row.style["border-top"] = "2px solid #00f";
                }
            });

            row.addEventListener("dragleave", () => {
                row.style["border-top"] = "";
                row.style["border-bottom"] = "";
            });

            row.addEventListener("drop", () => {
                row.style["border-top"] = "";
                row.style["border-bottom"] = "";

                // Ensure the draggedRow is not the same as the target row
                if (draggedRow !== row) {
                    const rows = Array.from(tbody.children);
                    const draggedIndex = rows.indexOf(draggedRow);
                    const targetIndex = rows.indexOf(row);

                    // Reorder rows based on drag-and-drop
                    if (draggedIndex > targetIndex) {
                        tbody.insertBefore(draggedRow, row);
                    } else {
                        tbody.insertBefore(draggedRow, row.nextSibling);
                    }


                    updateIndexesD(); // Update the row numbers
                }
            });

            row.addEventListener("dragend", () => {
                row.classList.remove("opacity-50");
            });
        });
        // Update row numbers initially
        updateIndexesD();
    }

    // Call updateIndexes initially to set the correct order
    updateIndexesD();
