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
    .carousel-control-prev-icon, .carousel-control-next-icon {
        background-color: black !important; /* Ensures the arrows are black */
        border-radius: 50%; /* Makes the arrows circular */
    }
    .carousel-control-prev-icon::after, .carousel-control-next-icon::after {
        color: white; /* White arrow inside the black circle */
    }
    .updateModal .modal-header {
        border-bottom: 1px solid #dee2e6;
    }
    .updateModal .modal-footer {
        border-top: 1px solid #dee2e6;
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
                        <h6 class="font-weight-semibold text-lg mb-0">{{ __('customers.customer_list') }}</h6>
                        <p class="text-sm">{{ __('customers.customer_list_description') }}</p>
                    </div>
                </div>
            </div>
            <!-- Confirmation Modal -->
            <div id="delete-modal" class="modal">
                <div class="modal-content">
                    <h3>{{ __('sales.confirm_deletion') }}</h3>
                    <p>{{ __('sales.delete_confirmation_message') }}</p>
                    <div class="modal-buttons">
                        <button id="confirm-delete" class="btn-confirm">{{ __('sales.confirm') }}</button>
                        <span id="loader" class="loader" style="display: none;"></span>
                        <button id="cancel-delete" class="btn-cancel">{{ __('sales.cancel') }}</button>
                    </div>
                </div>
            </div>
            <!-- Confirmation Modal -->
            <div class="card-body px-0 py-0">
                <div class="border-bottom py-3 px-3 d-sm-flex align-items-center">
                    <!-- <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                        <input id="filter-all" type="radio" class="btn-check" name="btnradiotable" autocomplete="off" checked>
                        <label class="btn btn-white px-3 mb-0" for="filter-all">All</label>
                        <input id="filter-verified" type="radio" class="btn-check" name="btnradiotable" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="filter-verified">Verified</label>
                        <input id="filter-pending" type="radio" class="btn-check" name="btnradiotable" autocomplete="off">
                        <label class="btn btn-white px-3 mb-0" for="filter-pending">Pending</label>
                    </div> -->
                    <div class="input-group w-sm-25 ms-auto">
                        <span class="input-group-text text-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"></path>
                        </svg>
                        </span>
                        <input type="text" class="form-control" id="searchInput" placeholder="{{ __('customers.search_by_name') }}">
                    </div>
                </div>
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="companiesTable">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7">{{ __('customers.customer') }}</th>
                                <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('customers.registration_no') }}</th>
                                <!-- <th class="text-secondary text-xs font-weight-semibold opacity-7 ps-2">{{ __('customers.registration_files') }}</th> -->
                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">{{ __('customers.address') }}</th>
                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">{{ __('customers.zone') }}</th>
                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">{{ __('customers.city') }}</th>
                                <th class="text-center text-secondary text-xs font-weight-semibold opacity-7">{{ __('customers.registered_at') }}</th>
                                <th class="text-secondary opacity-7"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers ?? [] as $key => $customer)
                            <tr data-status="all" data-customer-id="{{ $customer['id'] }}" id="row-{{$customer['id']}}">
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        <div class="d-flex align-items-center">
                                            <img src="../assets/img/team-2.jpg" class="avatar avatar-sm rounded-circle me-2" alt="user1">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center ms-1">
                                            <h6 class="mb-0 text-sm font-weight-semibold">{{ $customer['name'] ?? '-' }}</h6>
                                            <p class="text-sm text-secondary mb-0">{{ $customer['email'] ?? '-' }}</p>
                                            <p class="text-sm text-secondary mb-0">{{ $customer['phone'] ?: ($customer['mobile'] ?? '-') }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm text-dark font-weight-semibold mb-0">ID: {{ $customer['id'] }}</p>
                                    <p class="text-sm text-secondary mb-0">Country: {{ $customer['country'] ?? '-' }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-sm text-dark font-weight-semibold mb-0">{{ $customer['address'] ?? '-' }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-sm text-dark font-weight-semibold mb-0">{{ $customer['state'] ?? '-' }}</p>
                                </td>
                                <td class="text-center">
                                    <p class="text-sm text-dark font-weight-semibold mb-0">{{ $customer['city'] ?? '-' }}</p>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="text-secondary text-sm font-weight-normal">{{ !empty($customer['created_at']) ? \Illuminate\Support\Carbon::parse($customer['created_at'])->toDateString() : '-' }}</span>
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('customers.show', ['id' => $customer['id']]) }}" class="text-secondary font-weight-bold text-xs m-2 view cursor-pointer" data-bs-toggle="tooltip" data-bs-title="View Customer">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                    </a>
                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs m-2 edit cursor-pointer" data-bs-toggle="tooltip" data-bs-title="{{ __('customers.edit_customer') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                            <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 3L3 11.207V13h1.793L13 4.793 11.207 3zM14 4.5 11.5 2 12.5 1 15 3.5 14 4.5z"/>
                                        </svg>
                                    </a>
                                    <a href="javascript:;" class="text-secondary font-weight-bold text-xs m-2 delete cursor-pointer" data-bs-toggle="tooltip" data-bs-title="{{ __('customers.delete_customer') }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="3 6 5 6 21 6"></polyline>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                            <line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-secondary">
                                    {{ __('customers.no_customers_found') ?? 'No customers found.' }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- FilesModal -->
                <div class="modal" id="imageModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Company Files</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: #b0b0b0;"></button>
                            </div>
                            <div class="modal-body position-relative">
                                <!-- Carousel -->
                                <div id="imageCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <!-- Images will be dynamically inserted here -->
                                    </div>
                                    <!-- Carousel Controls -->
                                    <button class="carousel-control-prev" type="button" data-bs-target="#imageCarousel" data-bs-slide="prev">
                                        <span class="carousel-control-prev-icon bg-black rounded-circle p-2" aria-hidden="true"></span>
                                        <span class="visually-hidden">Previous</span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#imageCarousel" data-bs-slide="next">
                                        <span class="carousel-control-next-icon bg-black rounded-circle p-2" aria-hidden="true"></span>
                                        <span class="visually-hidden">Next</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- files modal -->
                <!-- Update Modal -->
                <div class="modal fade updateModal" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateModalLabel">{{ __('customers.update_customer') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="updatePercentageForm">
                                    <div class="mb-3">
                                        <label for="percentageInput" class="form-label">{{ __('customers.percentage') }}</label>
                                        <input type="number" class="form-control" id="percentageInput" placeholder="Enter percentage">
                                        @csrf
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('customers.close') }}</button>
                                <span id="updateLoader" class="loader" style="display: none;"></span>
                                <button type="button" class="btn btn-primary" id="updatePercentageButton">{{ __('customers.update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Update Modal -->
                <div class="border-top py-3 px-3 d-flex align-items-center">
                    <p class="font-weight-semibold mb-0 text-dark text-sm paging"></p>
                    <div class="ms-auto">
                        <button class="btn btn-sm btn-white mb-0 previous">{{ __('sales.previous') }}</button>
                        <button class="btn btn-sm btn-white mb-0 next">{{ __('sales.next') }}</button>
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
        // Select all filter buttons (optional; these are currently commented out in the markup)
        const filterAll      = document.getElementById('filter-all');
        const filterVerified = document.getElementById('filter-verified');
        const filterPending  = document.getElementById('filter-pending');

        const searchInput = document.getElementById('searchInput');
        const pageInfo = document.querySelector('.paging');
        const prevButton = document.querySelector('.previous');
        const nextButton = document.querySelector('.next');
        const pagination = JSON.parse('{{ addslashes(json_encode($pagination ?? [])) }}');
        const currentPage = Number(pagination.page || 1);
        const totalPages = Number(pagination.total_pages || 1);

        function loadPage(page) {
            const params = new URLSearchParams(window.location.search);
            params.set('page', page);

            const search = searchInput.value.trim();
            if (search) {
                params.set('search', search);
            } else {
                params.delete('search');
            }

            window.location.href = `${window.location.pathname}?${params.toString()}`;
        }

        pageInfo.textContent = `Page ${currentPage} of ${totalPages}`;
        prevButton.disabled = currentPage <= 1;
        nextButton.disabled = currentPage >= totalPages;

        // Add event listeners to the filter buttons only when they exist
        if (filterAll && filterVerified && filterPending) {
            filterAll.addEventListener('change', () => filterTable('all'));
            filterVerified.addEventListener('change', () => filterTable('verified'));
            filterPending.addEventListener('change', () => filterTable('pending'));
        }

        // Add event listener to the search input
        if (searchInput) {
            searchInput.value = new URLSearchParams(window.location.search).get('search') || '';
            searchInput.addEventListener('keydown', function (event) {
                if (event.key === 'Enter') {
                    loadPage(1);
                }
            });
        }

        // Event listener for the "Previous" button
        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                loadPage(currentPage - 1);
            }
        });

        // Event listener for the "Next" button
        nextButton.addEventListener('click', () => {
            if (currentPage < totalPages) {
                loadPage(currentPage + 1);
            }
        });

        const modal         = document.getElementById('imageModal');
        const carouselInner = document.querySelector('#imageCarousel .carousel-inner');

        document.querySelectorAll('.view-images-btn').forEach(button => {
            button.addEventListener('click', function () {
                const companyId = this.dataset.companyId;
                const imageContainer = document.getElementById(`company-images-${companyId}`);
                const images = imageContainer.querySelectorAll('.company-image');

                // Populate carousel
                carouselInner.innerHTML = Array.from(images).map((img, index) => `
                    <div class="carousel-item ${index === 0 ? 'active' : ''}">
                        <img src="${img.src}" class="d-block w-100" alt="Company Image">
                    </div>
                `).join('');

                // Show modal
                modal.style.display = 'block';
            });
        });

        modal.querySelector('.btn-close').addEventListener('click', function () {
            modal.style.display = 'none';
        });

        let currentCompanyId  = null;
        const updateModal     = new bootstrap.Modal(document.getElementById("updateModal"));
        const percentageInput = document.getElementById("percentageInput");

        // Edit button click handler
        document.querySelectorAll(".edit").forEach(button => {
            button.addEventListener("click", function () {
                const companyRow = this.closest("tr");
                currentCompanyId = companyRow.getAttribute("data-company-id");
                const currentPercentage = companyRow.getAttribute("data-percentage");

                // Set input value
                percentageInput.value = currentPercentage || "";

                // Show modal
                updateModal.show();
            });
        });

        const confirmPercentageButton = document.getElementById('updatePercentageButton');
        const updateLoader            = document.getElementById('updateLoader');

        // Update button click handler
        confirmPercentageButton.addEventListener("click", function () {
            // Show loader and hide the confirm button
            confirmPercentageButton.style.display = 'none';
            updateLoader.style.display = 'inline-block';

            const newPercentage = percentageInput.value;

            // Validate input
            if (newPercentage === "" || isNaN(newPercentage)) {
                alert("Please enter a valid percentage.");
                return;
            }

            // Make API call to update percentage
            fetch(`/api/companies/update_percentage`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector("input[name='_token']").value
                },
                body: JSON.stringify({company_id: currentCompanyId, percentage: newPercentage })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Failed to update percentage");
                    }
                    return response.json();
                })
                .then(data => {
                    // Close modal and refresh page
                    updateLoader.style.display            = 'none';
                    confirmPercentageButton.style.display = 'inline-block';
                    updateModal.hide();
                    location.reload();
                })
                .catch(error => {
                    console.error(error);
                    alert("Error updating percentage. Please try again.");
                });
        });
    });


    // Add event listener to the status tags
    document.querySelectorAll('.status-tag').forEach(tag => {
        tag.addEventListener('click', function() {
            // Get the company ID and current status
            const companyId     = this.closest('tr').dataset.companyId;
            const currentStatus = this.closest('tr').dataset.status;

            if (currentStatus === 'pending') {
                // Send POST request to update the company status to 'verified'
                fetch('/api/companies/update_status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ company_id: companyId, new_status: 'verify' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // On success, update the tag text and status
                        this.innerHTML = '<svg width="9" height="9" viewBox="0 0 10 9" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" class="me-1"><path d="M1 4.42857L3.28571 6.71429L9 1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>Verified';
                        this.classList.remove('border-warning');
                        this.classList.remove('text-warning');
                        this.classList.remove('bg-warning');
                        this.classList.add('border-success');
                        this.classList.add('text-success');
                        this.classList.add('bg-success');
                        this.dataset.status = 'verified';
                    } else {
                        // Handle failure case (optional)
                        alert('Failed to update status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
            else if (currentStatus === 'verified') {
                // Send POST request to update the company status to 'verified'
                fetch('/api/companies/update_status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ company_id: companyId, new_status: 'unverify' })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // On success, update the tag text and status
                        this.innerHTML = '<svg width="12" height="12" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="me-1ca"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" /></svg>Pending';
                        this.classList.remove('border-success');
                        this.classList.remove('text-success');
                        this.classList.remove('bg-success');
                        this.classList.add('border-warning');
                        this.classList.add('text-warning');
                        this.classList.add('bg-warning');
                        this.dataset.status = 'Pending';
                    } else {
                        // Handle failure case (optional)
                        alert('Failed to update status');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    });

    // Variables for the modal and buttons
    const deleteModal         = document.getElementById('delete-modal');
    const confirmDeleteButton = document.getElementById('confirm-delete');
    const cancelDeleteButton  = document.getElementById('cancel-delete');
    const loader              = document.getElementById('loader');

    // Store the company ID to delete
    let companyIdToDelete = null;

    // Add event listener to the delete buttons
    document.querySelectorAll('.delete').forEach(tag => {
        tag.addEventListener('click', function () {
            // Get the company ID
            companyIdToDelete = this.closest('tr').dataset.companyId;

            // Show the modal
            deleteModal.style.display = 'flex';
        });
    });

    // Handle the confirm button click
    confirmDeleteButton.addEventListener('click', function () {
        if (companyIdToDelete) {
            // Show loader and hide the confirm button
            confirmDeleteButton.style.display = 'none';
            loader.style.display = 'inline-block';

            // Send POST request to delete the company
            fetch('/api/companies/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ company_id: companyIdToDelete })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // On success, remove the row from the table
                    const row = document.getElementById(`row-${companyIdToDelete}`);
                    row.remove();
                } else {
                    // Handle failure case
                    alert('Failed to delete company');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            })
            .finally(() => {
                // Hide the modal and reset loader and confirm button
                deleteModal.style.display = 'none';
                loader.style.display = 'none';
                confirmDeleteButton.style.display = 'inline-block';
                companyIdToDelete = null;
            });
        }
    });

    // Handle the cancel button click
    cancelDeleteButton.addEventListener('click', function () {
        // Hide the modal and reset the ID
        deleteModal.style.display = 'none';
        companyIdToDelete = null;
    });
</script>
@stop