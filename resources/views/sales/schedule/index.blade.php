@extends('layouts.dashboard')

@section('CSS')
<!-- Add this to your CSS -->
<style>
    .modal {
        display: none; /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    .modal-content {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        width: 450px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    .modal-buttons {
        display: flex;
        justify-content: space-around;
        align-items: center;
        margin-top: 20px;
    }
    .btn-confirm {
        background: #f44336;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn-cancel {
        background: #ccc;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    .btn-confirm:hover {
        background: #d32f2f;
    }
    .btn-cancel:hover {
        background: #b0b0b0;
    }
    .loader {
        border: 4px solid #f3f3f3; /* Light grey */
        border-top: 4px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 24px;
        height: 24px;
        animation: spin 2s linear infinite;
    }
    @keyframes spin {
        0% {
        transform: rotate(0deg);
        }
        100% {
        transform: rotate(360deg);
        }
    }
    .updateModal .modal-header {
        border-bottom: 1px solid #dee2e6;
    }
    .updateModal .modal-footer {
        border-top: 1px solid #dee2e6;
    }
    .btn-outline-secondary {
        border: 1px solid #dcdcdc;
        background-color: #f8f9fa;
        color: #333;
        border-radius: 10px;
        padding: 12px 24px;
        font-weight: 500;
        font-size: 14px;
    }

    .btn-outline-secondary:hover {
        background-color: #e9ecef;
        color: #000;
    }
</style>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border shadow-xs mb-4">
            <div class="card-header border-bottom pb-0">
                <div class="d-sm-flex align-items-center">
                    <div>
                        <h6 class="font-weight-semibold text-lg mb-0">Sales Schedule</h6>
                        <p class="text-sm">See information about all Sales Schedules</p>
                    </div>
                    <div class="ms-auto">
                        <a href="{{ route('schedules.create') }}" class="btn btn-outline-secondary btn-sm d-flex align-items-center" style="gap: 5px; white-space: nowrap;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                            Create
                        </a>
                    </div>
                </div>
            </div>
            <!-- Delete Confirmation Modal -->
            <div id="delete-modal" class="modal">
                <div class="modal-content">
                    <h3>Confirm Deletion</h3>
                    <p>Are you sure you want to delete this schedule?</p>
                    <div class="modal-buttons">
                        <button id="confirm-delete" class="btn-confirm">Confirm</button>
                        <span id="loader" class="loader" style="display: none;"></span>
                        <button id="cancel-delete" class="btn-cancel">Cancel</button>
                    </div>
                </div>
            </div>
            <!-- Update Modal -->
            <div class="modal fade updateModal" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="updateModalLabel">Update Visit Date</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="updateForm">
                                <div class="mb-3">
                                    <label for="visitDateInput" class="form-label">Representative: </label>
                                    <select class="form-select" id="representativeSelect">
                                        <option value="" selected>Select Representative</option>
                                        @foreach($sales as $salesman)
                                            <option value="{{ $salesman->id }}">{{ $salesman->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="visitDateInput" class="form-label">New Visit Date</label>
                                    <input type="date" class="form-control" id="visitDateInput" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="updateVisitDateButton">
                                <span id="updateLoader" class="loader" style="display: none;"></span>
                                Update
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 py-0">
                <div class="border-bottom py-3 px-3 d-sm-flex align-items-center">
                    <div class="py-3 px-3 d-sm-flex align-items-center">
                        <!-- Date Filtration -->
                        <select id="yearFilter" class="form-select" style="width: 150px;">
                            <option value="" selected>Year</option>
                            <!-- Populate with years dynamically -->
                        </select>

                        <select id="monthFilter" class="form-select" style="width: 150px;">
                            <option value="" selected>Month</option>
                            <option value="01">January</option>
                            <option value="02">February</option>
                            <option value="03">March</option>
                            <option value="04">April</option>
                            <option value="05">May</option>
                            <option value="06">June</option>
                            <option value="07">July</option>
                            <option value="08">August</option>
                            <option value="09">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>

                        <button id="filterByDate" class="btn btn-primary mx-2 mb-0">Filter</button>
                        <button id="resetFilters" class="btn btn-secondary mr-2 mb-0">Reset</button>
                    </div>
                    <div class="input-group w-sm-25 ms-auto">
                        <span class="input-group-text text-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                        </svg>
                        </span>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search">
                    </div>
                </div>
                <div class="table-responsive p-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    <table class="table align-items-center mb-0" id="scheduleTable">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7">Salesman</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">Customer</th>
                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Visit At</th>
                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Status</th>
                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">Created At</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(empty($schedules))
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-secondary mb-3">
                                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7v-5z" fill="currentColor" opacity="0.3"/>
                                        </svg>
                                        <h6 class="text-secondary mb-1">No schedules found</h6>
                                        <p class="text-sm text-secondary mb-0">There are no schedules to display at the moment.</p>
                                    </div>
                                </td>
                            </tr>
                            @else
                            @foreach($schedules as $schedule)
                            <tr data-schedule-id="{{ $schedule->id }}" id="row-{{$schedule->id}}">
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex align-items-center">
                                            <img src="../assets/img/team-2.jpg" class="avatar avatar-sm rounded-circle me-2" alt="user1">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center ms-1">
                                            <h6 class="mb-0 text-sm font-weight-semibold" data-sales-id="{{ $schedule->sales->id }}">{{ $schedule->sales->name }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm text-dark font-weight-semibold mb-0">{{ $schedule->customer->name }}</p>
                                </td>
                                <td class="text-center" data-date="{{ date('Y-m-d', strtotime($schedule->visit_at)) }}">
                                    <p class="text-sm text-dark font-weight-semibold mb-0">{{ $schedule->visit_at ? \Carbon\Carbon::parse($schedule->visit_at)->format('Y-m-d') : 'Not Set' }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if($schedule->status == 'pending')
                                    <span class="badge badge-sm border border-secondary text-secondary bg-secondary reservation">
                                        Pending
                                    </span>
                                    @elseif($schedule->status == 'completed')
                                    <span class="badge badge-sm border border-success text-success bg-success reservation">
                                        Delivered
                                    </span>
                                    @elseif($schedule->status == 'canceled')
                                    <span class="badge badge-sm border border-danger text-danger bg-danger reservation">
                                        Canceled
                                    </span>
                                    @endif
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-sm font-weight-normal">{{ $schedule->created_at->format('Y-m-d') }}</span>
                                </td>
                                <td class="align-middle">
                                    @if($schedule->status == 'pending')
                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs m-2 edit cursor-pointer" data-bs-toggle="tooltip" data-bs-title="Edit Visit Date">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 3L3 11.207V13h1.793L13 4.793 11.207 3zM14 4.5 11.5 2 12.5 1 15 3.5 14 4.5z"/>
                                        </svg>
                                    </a>
                                    @else
                                    <span class="text-secondary font-weight-bold text-xs m-2" style="visibility: hidden;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 3L3 11.207V13h1.793L13 4.793 11.207 3zM14 4.5 11.5 2 12.5 1 15 3.5 14 4.5z"/>
                                        </svg>
                                    </span>
                                    @endif
                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs m-2 delete cursor-pointer" data-bs-toggle="tooltip" data-bs-title="Delete Schedule">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                
                <div class="border-top py-3 px-3 d-flex align-items-center">
                    <p class="font-weight-semibold mb-0 text-dark text-sm paging"></p>
                    <div class="ms-auto">
                        <button class="btn btn-sm btn-white mb-0 previous">Previous</button>
                        <button class="btn btn-sm btn-white mb-0 next">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('JavaScript')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput    = document.getElementById('searchInput');
        const table          = document.getElementById('scheduleTable');
        const tableRows      = Array.from(table.querySelectorAll('tbody tr'));

        let filteredRows     = [...tableRows];
        const rowsPerPage    = 10;
        let currentPage      = 1;

        const yearFilter     = document.getElementById("yearFilter");
        const monthFilter    = document.getElementById("monthFilter");
        const filterButton   = document.getElementById("filterByDate");

        const pageInfo       = document.querySelector('.paging');
        const prevButton     = document.querySelector('.previous');
        const nextButton     = document.querySelector('.next');

        // Populate year dropdown based on available data
        const populateYears = () => {
            const years = new Set();
            tableRows.forEach(row => {
                const dateCell = row.querySelector("td[data-date]");
                if (dateCell) {
                    const year = dateCell.getAttribute("data-date").slice(0, 4); // Extract year from "YYYY-MM-DD"
                    years.add(year);
                }
            });

            // Sort years in descending order
            Array.from(years).sort((a, b) => b - a).forEach(year => {
                const option = document.createElement("option");
                option.value = year;
                option.textContent = year;
                yearFilter.appendChild(option);
            });
        };

        function updateTable() {
            if (filteredRows.length === 0) {
                // Hide pagination if no rows
                pageInfo.textContent = '';
                prevButton.style.display = 'none';
                nextButton.style.display = 'none';
                return;
            }

            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = currentPage * rowsPerPage;

            tableRows.forEach(row => (row.style.display = 'none'));
            filteredRows.slice(startIndex, endIndex).forEach(row => (row.style.display = ''));

            pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;

            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages || totalPages === 0;
            prevButton.style.display = '';
            nextButton.style.display = '';
        }

        function searchTable() {
            const query = searchInput.value.toLowerCase().trim();

            filteredRows = tableRows.filter(row => {
                const salesmanName = row.cells[0].textContent.toLowerCase();
                const customerName = row.cells[1].textContent.toLowerCase();
                const matchesSearch = salesmanName.includes(query) || customerName.includes(query);

                return matchesSearch;
            });

            currentPage = 1;
            updateTable();
        }

        // Filter table by date
        const filterTableByDate = (year, month) => {
            const formattedDate = `${year}-${month}`;
            filteredRows = filteredRows.filter(row => {
                const dateCell = row.querySelector("td[data-date]");
                if (dateCell) {
                    const cellDate = dateCell.getAttribute("data-date").slice(0, 7); // Get "YYYY-MM"
                    return cellDate === formattedDate;
                }
                return false;
            });

            currentPage = 1;
            updateTable();
        };

        // Reset filters to show the original table
        const resetFilters = () => {
            yearFilter.value = "";
            monthFilter.value = "";
            
            // Reapply the current filter and search
            filterTable(currentFilter);
            searchTable();
            currentPage = 1;
            updateTable();
        };

        searchInput.addEventListener('input', function () {
            searchTable();
        });

        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                updateTable();
            }
        });

        document.getElementById("resetFilters").addEventListener("click", resetFilters);

        nextButton.addEventListener('click', () => {
            const totalPages = Math.ceil(filteredRows.length / rowsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                updateTable();
            }
        });

        // Event listener for date filter
        filterButton.addEventListener("click", () => {
            const selectedYear = yearFilter.value;
            const selectedMonth = monthFilter.value;

            if (!selectedYear || !selectedMonth) {
                alert("Please select both a year and a month!");
                return;
            }

            filterTableByDate(selectedYear, selectedMonth);
        });

        // Initialize table and populate year filter
        populateYears();

        updateTable();

        let currentScheduleId = null;
        const updateModal = new bootstrap.Modal(document.getElementById("updateModal"));
        const visitDateInput = document.getElementById("visitDateInput");

        // Edit button click handler
        document.querySelectorAll(".edit").forEach(button => {
            button.addEventListener("click", function () {
                const scheduleRow = this.closest("tr");
                currentScheduleId = scheduleRow.getAttribute("data-schedule-id");
                const currentVisitDate = scheduleRow.cells[2].textContent.trim();
                const currentRepresentative = scheduleRow.querySelector("h6[data-sales-id]").getAttribute("data-sales-id");

                // Set input value
                if (currentVisitDate !== 'Not Set') {
                    visitDateInput.value = currentVisitDate;
                } else {
                    visitDateInput.value = "";
                }

                // Set representative name in the select (disabled)
                const representativeSelect = document.getElementById("representativeSelect");
                representativeSelect.value = currentRepresentative;

                // Show modal
                updateModal.show();
            });
        });

        const updateVisitDateButton = document.getElementById('updateVisitDateButton');
        const updateLoader = document.getElementById('updateLoader');

        // Update button click handler
        updateVisitDateButton.addEventListener("click", function () {
            const newVisitDate = visitDateInput.value;
            const newRepresentative = document.getElementById("representativeSelect").value;

            // Validate input
            if (newVisitDate === "") {
                alert("Please select a visit date.");
                return;
            }

            // Show loader and hide the update button
            updateVisitDateButton.style.display = 'none';
            updateLoader.style.display = 'inline-block';

            // Make API call to update visit date
            fetch(`/api/sales/update_visit_date`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({schedule_id: currentScheduleId, visit_date: newVisitDate, sales_id: newRepresentative })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Failed to update visit date");
                    }
                    return response.json();
                })
                .then(data => {
                    // Close modal and refresh page
                    updateLoader.style.display = 'none';
                    updateVisitDateButton.style.display = 'inline-block';
                    updateModal.hide();
                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                    alert("Error updating visit date. Please try again.");
                    updateLoader.style.display = 'none';
                    updateVisitDateButton.style.display = 'inline-block';
                });
        });

        // Variables for the delete modal
        const deleteModal = document.getElementById('delete-modal');
        const confirmDeleteButton = document.getElementById('confirm-delete');
        const cancelDeleteButton = document.getElementById('cancel-delete');
        const loader = document.getElementById('loader');

        let scheduleIdToDelete = null;

        // Add event listener to the delete buttons
        document.querySelectorAll('.delete').forEach(button => {
            button.addEventListener('click', function () {
                scheduleIdToDelete = this.closest('tr').getAttribute('data-schedule-id');
                deleteModal.style.display = 'flex';
            });
        });

        // Handle the confirm button click
        confirmDeleteButton.addEventListener('click', function () {
            if (scheduleIdToDelete) {
                confirmDeleteButton.style.display = 'none';
                loader.style.display = 'inline-block';

                fetch('/api/sales/delete_schedule', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ schedule_id: scheduleIdToDelete })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.response_code === 200) {
                        const row = document.getElementById(`row-${scheduleIdToDelete}`);
                        row.remove();
                    } else {
                        alert(data.response_message || 'Failed to delete schedule');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                })
                .finally(() => {
                    deleteModal.style.display = 'none';
                    loader.style.display = 'none';
                    confirmDeleteButton.style.display = 'inline-block';
                    scheduleIdToDelete = null;
                });
            }
        });

        // Handle the cancel button click
        cancelDeleteButton.addEventListener('click', function () {
            deleteModal.style.display = 'none';
            scheduleIdToDelete = null;
        });
    });
</script>
@stop