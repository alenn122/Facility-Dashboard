
<?php
include "session_auth.php";

// Prevent browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FAVICON -->
    <link rel="shortcut icon" href="img/loalogo.png" type="image/x-icon">
    <!-- ICON CDN FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <!-- CSS -->
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <!-- ANALYTICS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>ACCESS LOGS</title>
</head>

<body>

    <!-- NAVIGATION SIDEBAR -->
    <?php include 'sidebar.php'; ?>
    
    <div class="main-content p-4">
        <!-- Header -->
        <div class="card shadow-sm mb-4">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <h3 class="fw-bold mb-0">Access Logs</h3>

                <div class="input-group room-search">
                    <span class="input-group-text bg-white">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" class="form-control" 
                           placeholder="Search by Name, RFID, Room...">
                    <!-- <button type="button" id="searchBtn">
                        <i class="fas fa-search"></i>
                    </button> -->
                </div>
            </div>
        </div>
        
        <!-- ANALYTICS -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm border-0 bg-primary text-white h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase small mb-1">Total Taps (Today)</h6>
                            <h2 class="mb-0 fw-bold" id="todayTaps">0</h2>
                        </div>
                        <i class="fas fa-fingerprint fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 bg-success text-white h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase small mb-1">Energy Saving Events</h6>
                            <h2 class="mb-0 fw-bold" id="energyEvents">0</h2>
                        </div>
                        <i class="fas fa-leaf fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 bg-danger text-white h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase small mb-1">Unauthorized Attempts</h6>
                            <h2 class="mb-0 fw-bold" id="deniedTaps">0</h2>
                        </div>
                        <i class="fas fa-shield-alt fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow-sm border-0 bg-dark text-white h-100">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-uppercase small mb-1">Peak Room</h6>
                            <h2 class="mb-0 fw-bold" id="peakRoom" style="font-size: 1.2rem;">Loading...</h2>
                        </div>
                        <i class="fas fa-door-open fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold"><i class="fas fa-chart-line me-2 text-primary"></i> 24-Hour Usage Trend</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="usageChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTER -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                    <div class="d-flex flex-wrap gap-2">
                        <!-- Status Filter -->
                        <select id="statusFilter" class="form-select w-auto">
                            <option value="all">All Status</option>
                            <option value="granted">Granted</option>
                            <option value="denied">Denied</option>
                        </select>
                        
                        <!-- Room Filter -->
                        <select id="roomFilter" class="form-select w-auto">
                            <option value="all">All Rooms</option>
                            <!-- Rooms will be loaded via AJAX -->
                        </select>
                        
                        <!-- Access Type Filter -->
                        <select id="typeFilter" class="form-select w-auto">
                            <option value="all">All Access Type</option>
                            <option value="Entry">Entry</option>
                            <option value="Exit">Exit</option>
                        </select>
                        
                        <button type="button" id="applyFilters" class="main-btn px-4">
                            <i class="fas fa-filter me-2"></i>Apply Filters
                        </button>
                        
                        <button type="button" id="clearFilters" class="secondary-btn px-4" style="display: none;">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </button>
                    </div>
                    
                    <button type="button" class="print" id="printBtn">
                        <i class="fas fa-print me-2"></i>Print
                    </button>
                </div>

                <h4 class="fw-bold mb-3">Access logs 
                    <span id="recordCount" class="badge bg-primary">0 records</span>
                </h4>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading access logs...</p>
                </div>

                <!-- No Results Message -->
                <div id="noResults" class="text-center py-4" style="display: none;">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No access logs found.</p>
                </div>

                <!-- Logs Table -->
                <div id="logsContainer" class="table-responsive" style="max-height: 500px; overflow-y: auto; display: none;">
                    <table class="table table-hover align-middle">
                        <thead class="table-light sticky-top">
                            <tr>
                                <!-- <th>Log_id</th> -->
                                <th>User Name</th>
                                <th>Role</th>
                                <th>Room</th>
                                <th>Access_Type</th>
                                <th>Access_time</th>
                                <th>Access</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="logsTableBody">
                            <!-- Logs will be loaded here via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav id="pagination" class="mt-3" style="display: none;">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled" id="prevPage">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item active"><span class="page-link" id="currentPage">1</span></li>
                        <li class="page-item" id="nextPage">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Print Modal (Copied from your old code format) -->
    <div class="print-modal" id="printModal" style="display: none;">
        <div class="print-modal-content">
            <h3>Print Access Logs</h3>
            <div class="print-options">
                <div class="print-option-group">
                    <label for="printDateFrom">Date From</label>
                    <input type="date" id="printDateFrom">
                </div>
                <div class="print-option-group">
                    <label for="printDateTo">Date To</label>
                    <input type="date" id="printDateTo">
                </div>
                <div class="print-option-group">
                    <label for="printAccessType">Access Type</label>
                    <select id="printAccessType">
                        <option value="all">All Types</option>
                        <option value="Entry">Entry</option>
                        <option value="Exit">Exit</option>
                    </select>
                </div>
                <div class="print-option-group">
                    <label for="printStatus">Status</label>
                    <select id="printStatus">
                        <option value="all">All Status</option>
                        <option value="granted">Granted</option>
                        <option value="denied">Denied</option>
                    </select>
                </div>
                <div class="print-option-group">
                    <label for="printRoom">Room</label>
                    <select id="printRoom">
                        <option value="all">All Rooms</option>
                    </select>
                </div>
            </div>
            <div class="print-modal-buttons">
                <button class="print-cancel" id="printCancel">Cancel</button>
                <button class="print-confirm" id="printConfirm">Print</button>
            </div>
        </div>
    </div>

    <!-- Hidden print section (only visible during printing) -->
    <div id="printSection"></div>

    <!-- JAVASCRIPT -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
    
    <script>
    let myChart = null; 
    let lastLogId = null;
    document.addEventListener('DOMContentLoaded', function() {
        // DOM Elements
        const searchInput = document.getElementById('searchInput');
        // const searchBtn = document.getElementById('searchBtn');
        const statusFilter = document.getElementById('statusFilter');
        const roomFilter = document.getElementById('roomFilter');
        const typeFilter = document.getElementById('typeFilter');
        const applyFiltersBtn = document.getElementById('applyFilters');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const logsContainer = document.getElementById('logsContainer');
        const logsTableBody = document.getElementById('logsTableBody');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const noResults = document.getElementById('noResults');
        const recordCount = document.getElementById('recordCount');
        const pagination = document.getElementById('pagination');
        const prevPageBtn = document.getElementById('prevPage');
        const nextPageBtn = document.getElementById('nextPage');
        const currentPageSpan = document.getElementById('currentPage');
        const printBtn = document.getElementById('printBtn');
        const printModal = document.getElementById('printModal');
        const printCancel = document.getElementById('printCancel');
        const printConfirm = document.getElementById('printConfirm');
        const printSection = document.getElementById('printSection');
        
        // Print dialog elements
        const printDateFrom = document.getElementById('printDateFrom');
        const printDateTo = document.getElementById('printDateTo');
        const printAccessType = document.getElementById('printAccessType');
        const printStatus = document.getElementById('printStatus');
        const printRoom = document.getElementById('printRoom');

        // State variables
        let currentPage = 1;
        const itemsPerPage = 50;
        let totalRecords = 0;
        let currentFilters = {
            status: 'all',
            room: 'all',
            access_type: 'all',
            search: '',
            from_date: '',
            to_date: ''
        };

        // Initialize
        loadRoomOptions();
        loadPrintRoomOptions();
        loadLogs();
        
        // Set default dates for print modal (today and last 7 days)
        const today = new Date();
        const oneWeekAgo = new Date();
        oneWeekAgo.setDate(today.getDate() - 7);
        
        printDateFrom.valueAsDate = oneWeekAgo;
        printDateTo.valueAsDate = today;

        // Event Listeners
        // searchBtn.addEventListener('click', function() {
        //     currentPage = 1;
        //     currentFilters.search = searchInput.value;
        //     console.debug('Search button clicked, search term:', currentFilters.search);
        //     loadLogs();
        // });

        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                currentPage = 1;
                currentFilters.search = this.value.trim();
                loadLogs();
            }
        });

        // Debounce helper for real-time search
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        // Real-time search (debounced)
        const debouncedRealtimeSearch = debounce(function() {
            currentPage = 1;
            currentFilters.search = searchInput.value.trim();
            console.debug('Realtime search triggered:', currentFilters.search);
            loadLogs();
        }, 300); // 300ms delay after typing stops

        searchInput.addEventListener('input', debouncedRealtimeSearch);

        applyFiltersBtn.addEventListener('click', function() {
            currentPage = 1;
            currentFilters.status = statusFilter.value;
            currentFilters.room = roomFilter.value;
            currentFilters.access_type = typeFilter.value;
            currentFilters.search = searchInput.value;
            loadLogs();
        });

        clearFiltersBtn.addEventListener('click', function() {
            // Reset all filters
            statusFilter.value = 'all';
            roomFilter.value = 'all';
            typeFilter.value = 'all';
            searchInput.value = '';
            
            currentFilters = {
                status: 'all',
                room: 'all',
                access_type: 'all',
                search: '',
                from_date: '',
                to_date: ''
            };
            
            currentPage = 1;
            loadLogs();
        });

        prevPageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                loadLogs();
            }
        });

        nextPageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (currentPage * itemsPerPage < totalRecords) {
                currentPage++;
                loadLogs();
            }
        });

        // Print functionality (Copied from your old code)
        printBtn.addEventListener('click', function() {
            printModal.style.display = 'flex';
        });

        printCancel.addEventListener('click', function() {
            printModal.style.display = 'none';
        });

        printConfirm.addEventListener('click', function() {
            const filters = {
                status: printStatus.value,
                room: printRoom.value,
                access_type: printAccessType.value,
                from_date: printDateFrom.value,
                to_date: printDateTo.value
            };

            const queryParams = new URLSearchParams(filters).toString();
            
            // Change button state to show it's working
            printConfirm.disabled = true;
            printConfirm.textContent = 'Preparing...';

            // Change the URL line to this:
            fetch(`ajax/get_access_logs.php?action=print&${queryParams}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        renderPrintSection(data.logs, filters);
                        printModal.style.display = 'none';
                        
                        // Small delay to let browser render the hidden div
                        setTimeout(() => {
                            window.print();
                            printConfirm.disabled = false;
                            printConfirm.textContent = 'Print';
                        }, 500);
                    }
                })
                .catch(err => {
                    console.error("Print Error:", err);
                    alert("Failed to generate report.");
                    printConfirm.disabled = false;
                });
        });

       function renderPrintSection(logs, filters) {
        const printSection = document.getElementById('printSection');
        printSection.innerHTML = `
            <div class="print-header">
                <img src="img/loalogo.png" style="width: 80px;">
                <h2 style="margin-top: 10px;">Access Logs Report</h2>
                <p>Lyceum of San Pedro - Facility Control System</p>
                <p>Generated on: ${new Date().toLocaleString()}</p>
            </div>
            <div class="print-filters" style="margin-bottom: 20px; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                <strong>Filters applied:</strong> 
                Room: ${filters.room}, Status: ${filters.status}, Type: ${filters.access_type} 
                ${filters.from_date ? `| Range: ${filters.from_date} to ${filters.to_date}` : ''}
            </div>
            <table class="print-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background-color: #eee;">
                        <th style="border: 1px solid #ddd; padding: 8px;">User Name</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Role</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Room</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Device</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Time</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Access</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    ${logs.map(log => {
                        // Fallback logic: Try names first, then RFID, then Guest
                        let displayName = "Guest";
                        if (log.F_name || log.L_name) {
                            displayName = `${log.F_name || ''} ${log.L_name || ''}`.trim();
                        } else if (log.Rfid_tag) {
                            displayName = `Unknown (${log.Rfid_tag})`;
                        }

                        return `
                            <tr>
                                <td style="border: 1px solid #ddd; padding: 8px;"><strong>${displayName}</strong></td>
                                <td style="border: 1px solid #ddd; padding: 8px;">${log.Role || 'N/A'}</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">${log.Room_code}</td> 
                                <td style="border: 1px solid #ddd; padding: 8px;"><small>${log.device_type || 'N/A'}</small></td>
                                <td style="border: 1px solid #ddd; padding: 8px;">${log.Access_time}</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">${log.Access_type}</td>
                                <td style="border: 1px solid #ddd; padding: 8px;">
                                    <span style="text-transform: uppercase; font-weight: bold; color: ${log.Status === 'granted' ? '#28a745' : '#dc3545'};">
                                        ${log.Status}
                                    </span>
                                </td>
                            </tr>
                        `;
                    }).join('')}
                </tbody>
            </table>
            <div class="print-footer" style="margin-top: 30px; text-align: center; border-top: 1px solid #eee; padding-top: 10px;">
                <p>Total Records: <strong>${logs.length}</strong></p>
                <p style="font-size: 12px; color: #666;">End of Report - Lyceum of San Pedro Smart Classroom System</p>
            </div>
        `;
    }
        // Functions
        function loadRoomOptions() {
            fetch('ajax/get_rooms.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.rooms && data.rooms.length > 0) {
                        // Clear existing options except 'All Rooms'
                        while (roomFilter.options.length > 1) {
                            roomFilter.remove(1);
                        }
                        // Add room options
                        data.rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.Room_code;        // access property
                            option.textContent = room.Room_code;
                            roomFilter.appendChild(option);
                        });
                    } else {
                        console.warn('No rooms found or invalid response:', data);
                    }
                })
                .catch(error => {
                    console.error('Error loading room options:', error);
                });
        }

        function loadPrintRoomOptions() {
            fetch('ajax/get_rooms.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.rooms) {
                        // Clear existing options except "All Rooms"
                        while (printRoom.options.length > 1) {
                            printRoom.remove(1);
                        }
                        
                        // Add room options
                        data.rooms.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.Room_code;        // access property
                            option.textContent = room.Room_code;
                            printRoom.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading print room options:', error);
                });
        }

        function loadLogs() {
            // Force a visual reset when filters are active so the user knows it's loading
            loadingSpinner.style.display = 'block';
            
            const filters = {
                status: currentFilters.status,
                room: currentFilters.room,
                access_type: currentFilters.access_type,
                search: currentFilters.search,
                page: currentPage,
                limit: itemsPerPage
            };

            const queryParams = new URLSearchParams(filters).toString();
            
            // Change the URL line to this:
            fetch(`ajax/get_access_logs.php?action=table&${queryParams}`)
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.style.display = 'none';
                    
                    // If no data found, show the 'No Results' div
                    if (!data.success || !data.logs || data.logs.length === 0) {
                        logsContainer.style.display = 'none';
                        noResults.style.display = 'block';
                        recordCount.textContent = `0 records`;
                        return;
                    }

                    noResults.style.display = 'none';
                    logsContainer.style.display = 'block';
                    
                    // Update tracker and count
                    totalRecords = data.total;
                    recordCount.textContent = `${data.total} records`;
                    
                    // Clear and rebuild
                    logsTableBody.innerHTML = '';
                    
                    data.logs.forEach(log => {
                        const statusClass = log.Status.toLowerCase() === 'granted' ? 'granted' : 'denied';
                        
                        // Combine names. If both are missing, show Guest/Unknown
                        let displayName = "Guest";
                        if (log.F_name || log.L_name) {
                            displayName = `${log.F_name || ''} ${log.L_name || ''}`.trim();
                        } else if (log.Rfid_tag) {
                            displayName = `Unknown (${log.Rfid_tag})`;
                        }

                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><strong>${displayName}</strong></td>
                            <td>${log.Role || 'N/A'}</td>
                            <td>${log.Room_code}</td> 
                            <td><small>${log.device_type || 'N/A'}</small></td> 
                            <td>${log.Access_time}</td>
                            <td>${log.Access_type}</td>
                            <td><span class="status ${statusClass}">${log.Status}</span></td>
                        `;
                        logsTableBody.appendChild(row);
                    });

                    updatePagination();
                });
        }

        function updatePagination() {
            const totalPages = Math.ceil(totalRecords / itemsPerPage);
            
            if (totalPages > 1) {
                pagination.style.display = 'block';
                
                // Update current page
                currentPageSpan.textContent = currentPage;
                
                // Update previous button
                if (currentPage > 1) {
                    prevPageBtn.classList.remove('disabled');
                } else {
                    prevPageBtn.classList.add('disabled');
                }
                
                // Update next button
                if (currentPage < totalPages) {
                    nextPageBtn.classList.remove('disabled');
                } else {
                    nextPageBtn.classList.add('disabled');
                }
            } else {
                pagination.style.display = 'none';
            }
        }

        // Generate print view (Copied from your old code with adjustments)
        function generatePrintView(dateFrom, dateTo, accessType, status, room) {
            const printSection = document.getElementById('printSection');
            printSection.innerHTML = '';
            
            // Filter data based on print options
            const filteredData = filterDataForPrint(dateFrom, dateTo, accessType, status, room);
            
            // Create print header
            const printHeader = document.createElement('div');
            printHeader.className = 'print-header';
            printHeader.innerHTML = `
                <h2>Access Logs Report</h2>
                <p>Lyceum of San Pedro</p>
                <p>Generated on: ${new Date().toLocaleDateString()}</p>
            `;
            printSection.appendChild(printHeader);
            
            // Create print filters info
            const printFilters = document.createElement('div');
            printFilters.className = 'print-filters';
            
            let filtersText = 'Filters: ';
            const filters = [];
            
            if (dateFrom || dateTo) {
                filters.push(`Date: ${dateFrom || 'Any'} to ${dateTo || 'Any'}`);
            }
            if (accessType !== 'all') {
                filters.push(`Access Type: ${accessType}`);
            }
            if (status !== 'all') {
                filters.push(`Status: ${status}`);
            }
            if (room !== 'all') {
                filters.push(`Room: ${room}`);
            }
            
            if (filters.length === 0) {
                filtersText += 'All records';
            } else {
                filtersText += filters.join(', ');
            }
            
            printFilters.textContent = filtersText;
            printSection.appendChild(printFilters);
            
            // Create print table
            const printTable = document.createElement('table');
            printTable.className = 'print-table';
            
            // Add table header
            const tableHeader = document.createElement('thead');
            tableHeader.innerHTML = `
                <tr>
                    <!-- <th>Log_id</th> -->
                    <th>User Name</th>
                    <th>Role</th>
                    <th>Room</th>
                    <th>Access_type</th>
                    <th>Access_time</th>
                    <th>Access</th>
                    <th>Status</th>
                </tr>
            `;
            printTable.appendChild(tableHeader);
            
            // Add table body with filtered rows
            const tableBody = document.createElement('tbody');
            
            if (filteredData.length > 0) {
                filteredData.forEach(row => {
                    tableBody.appendChild(row);
                });
            } else {
                const noDataRow = document.createElement('tr');
                noDataRow.innerHTML = `<td colspan="7" style="text-align: center;">No records found matching the selected criteria</td>`;
                tableBody.appendChild(noDataRow);
            }
            
            printTable.appendChild(tableBody);
            printSection.appendChild(printTable);
            
            // Create print footer
            const printFooter = document.createElement('div');
            printFooter.className = 'print-footer';
            printFooter.innerHTML = `
                <p>Total Records: ${filteredData.length}</p>
                <p>Lyceum of San Pedro Facility Control System Access Logs</p>
            `;
            printSection.appendChild(printFooter);
            
            // Show the print section
            printSection.style.display = 'block';
        }

        // Filter data for printing (Copied from your old code with adjustments)
        function filterDataForPrint(dateFrom, dateTo, accessType, status, room) {
            const rows = document.querySelectorAll('#logsTableBody tr');
            const filteredRows = [];
            
            rows.forEach(row => {
                // Skip hidden or empty rows
                if (row.style.display === 'none') return;
                const cells = row.cells;
                if (!cells || cells.length < 7) return;

                // 1. Capture the Row Data
                const accessTimeStr = cells[4].textContent; 
                const rowDate = new Date(accessTimeStr.split(' ')[0]);
                const rowAccessType = cells[5].textContent.trim().toLowerCase(); 
                const rowStatus = cells[6].textContent.trim().toLowerCase();
                const rowRoom = cells[2].textContent.trim(); 

                // 2. Date Filtering Logic (Consolidated)
                let dateMatch = true;
                if (dateFrom || dateTo) {
                    const rowTime = rowDate.getTime();
                    
                    if (dateFrom) {
                        const fromDate = new Date(dateFrom).setHours(0, 0, 0, 0);
                        if (rowTime < fromDate) dateMatch = false;
                    }
                    
                    if (dateTo) {
                        const toDate = new Date(dateTo).setHours(23, 59, 59, 999);
                        if (rowTime > toDate) dateMatch = false;
                    }
                }

                // 3. Dropdown Filtering Logic
                const accessTypeMatch = (accessType === 'all' || rowAccessType === accessType.toLowerCase());
                const statusMatch = (status === 'all' || rowStatus === status.toLowerCase());
                const roomMatch = (room === 'all' || rowRoom === room);

                // 4. Final Verification
                if (dateMatch && accessTypeMatch && statusMatch && roomMatch) {
                    filteredRows.push(row.cloneNode(true));
                }
            });
            
            return filteredRows;
        }

        // Sidebar toggle (from your script.js)
        const sidebar = document.getElementById('sidebar');
        const openSidebarBtn = document.getElementById('openSidebar');
        const closeSidebarBtn = document.getElementById('closeSidebar');
        
        if (openSidebarBtn) {
            openSidebarBtn.addEventListener('click', function() {
                sidebar.classList.add('show');
            });
        }
        
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', function() {
                sidebar.classList.remove('show');
            });
        }
        
        // Add CSS for print section
        const printCSS = document.createElement('style');
        printCSS.textContent = `
            .print-modal {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: none;
                justify-content: center;
                align-items: center;
                z-index: 2000; /* Increased so it sits above Bootstrap's .sticky-top (z-index:1020) */
            }
            
            .print-modal-content {
                background: white;
                padding: 30px;
                border-radius: 10px;
                width: 500px;
                max-width: 90%;
            }
            
            .print-options {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 20px;
                margin: 20px 0;
            }
            
            .print-option-group {
                display: flex;
                flex-direction: column;
            }
            
            .print-option-group label {
                font-weight: 500;
                margin-bottom: 5px;
                color: #333;
            }
            
            .print-option-group input,
            .print-option-group select {
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            
            .print-modal-buttons {
                display: flex;
                justify-content: flex-end;
                gap: 10px;
                margin-top: 20px;
            }
            
            .print-cancel,
            .print-confirm {
                padding: 10px 20px;
                border: none;
                border-radius: 4px;
                cursor: pointer;
                font-weight: 500;
            }
            
            .print-cancel {
                background: #6c757d;
                color: white;
            }
            
            .print-confirm {
                background: #28a745;
                color: white;
            }
            
            #printSection {
                display: none;
            }

            @media print {
                @page {
                    margin: 20px; /* This removes the date and title at the top */
                }
                body * {
                    visibility: hidden;
                    height: 0;
                    margin: 0;
                    padding: 0;
                }

                #printSection, #printSection * {
                    visibility: visible;
                    
                    height: auto;
                }

                #printSection {
                    display: block;
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                    margin: 0;
                    padding: 20px;
                }
                
                .print-header {
                    text-align: center;
                    margin-bottom: 20px;
                }
                
                .print-filters {
                    margin-bottom: 15px;
                    font-size: 14px;
                }
                
                .print-table {
                    width: 100% !important;
                    border-collapse: collapse !important;
                    table-layout: auto !important;
                    margin-top: 20px;
                }
                
                .print-table th,
                .print-table td {
                    border: 1px solid #000 !important;
                    padding: 6px 8px !important;
                    text-align: left !important;
                    font-size: 14px !important;
                    display: table-cell !important;
                }
                .print-table thead { 
                    display: table-header-group !important; 
                }
                .print-table tr { 
                    display: table-row !important; 
                }

                .print-table th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                
                .print-footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 12px;
                    color: #666;
                }
            }
        `;
        document.head.appendChild(printCSS);

        function loadAnalytics() {
            // Change the URL line to this:
            fetch('ajax/get_access_logs.php?action=analytics')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('todayTaps').textContent = data.today_count;
                    document.getElementById('energyEvents').textContent = data.energy_events;
                    document.getElementById('deniedTaps').textContent = data.denied_count;
                    document.getElementById('peakRoom').textContent = data.peak_room;

                    // FIX: If chart doesn't exist, create it once.
                    if (!myChart) {
                        const ctx = document.getElementById('usageChart').getContext('2d');
                        myChart = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: data.chart_labels,
                                datasets: [{
                                    label: 'Activity (Taps)',
                                    data: data.chart_data,
                                    borderColor: '#007bff',
                                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                                    fill: true,
                                    tension: 0.4
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: { y: { beginAtZero: true } }
                            }
                        });
                    } else {
                        // FIX: If chart exists, just update the data and labels
                        myChart.data.labels = data.chart_labels;
                        myChart.data.datasets[0].data = data.chart_data;
                        myChart.update('none'); // 'none' prevents the "glitchy" reset animation
                    }
                })
                .catch(err => console.error("Analytics Error:", err));
        }

        // 3. Initial execution
        loadRoomOptions();
        loadPrintRoomOptions();
        loadLogs();
        loadAnalytics();

        // 4. Set the auto-refresh for both table and analytics
        setInterval(function() {
            loadLogs();
            loadAnalytics();
        }, 5000);
    });

    </script>
</body>
</html>