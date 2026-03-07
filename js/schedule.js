console.log('Schedule module loaded - UPDATED WITH EXCEL IMPORT');

// Global variables
let scheduleModal = null;
let importExcelModal = null;
let currentFilters = {
    course: 'all',
    day: 'all',
    faculty: 'all',
    room: 'all',
    search: ''
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing schedule system...');
    
    // Initialize Bootstrap modals
    if (typeof bootstrap !== 'undefined') {
        scheduleModal = new bootstrap.Modal(document.getElementById('scheduleModal'));
        importExcelModal = new bootstrap.Modal(document.getElementById('importExcelModal'));
    } else {
        console.error('Bootstrap not loaded');
    }
    
    // Setup event listeners
    setupEventListeners();
    
    // Load initial schedules
    loadSchedules();
    
    console.log('Schedule system ready');
});

// Helper: parse response as JSON
function parseJsonSafe(response) {
    return response.text().then(text => {
        try {
            // Check if response is empty
            if (!text || text.trim() === '') {
                return { ok: false, data: null, error: 'Empty response from server' };
            }
            
            const json = JSON.parse(text);
            return { ok: true, data: json };
        } catch (e) {
            console.error('Failed to parse JSON:', text.substring(0, 500));
            return { ok: false, data: null, error: 'Invalid JSON response', text: text.substring(0, 500) };
        }
    });
}

// Setup event listeners
function setupEventListeners() {
    // Search input with debounce
    let searchTimeout;
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentFilters.search = this.value;
                loadSchedules();
            }, 500);
        });
        
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                currentFilters.search = this.value;
                loadSchedules();
            }
        });
    }
    
    // Filter change listeners
    const courseFilter = document.getElementById('courseFilter');
    if (courseFilter) {
        courseFilter.addEventListener('change', function() {
            currentFilters.course = this.value;
            loadSchedules();
        });
    }
    
    const dayFilter = document.getElementById('dayFilter');
    if (dayFilter) {
        dayFilter.addEventListener('change', function() {
            currentFilters.day = this.value;
            loadSchedules();
        });
    }
    
    const facultyFilter = document.getElementById('facultyFilter');
    if (facultyFilter) {
        facultyFilter.addEventListener('change', function() {
            currentFilters.faculty = this.value;
            loadSchedules();
        });
    }
    
    const roomFilter = document.getElementById('roomFilter');
    if (roomFilter) {
        roomFilter.addEventListener('change', function() {
            currentFilters.room = this.value;
            loadSchedules();
        });
    }
    
    // Clear filters
    const clearFilters = document.getElementById('clearFilters');
    if (clearFilters) {
        clearFilters.addEventListener('click', clearAllFilters);
    }
    
    // Add schedule
    const addScheduleBtn = document.getElementById('addScheduleBtn');
    if (addScheduleBtn) {
        addScheduleBtn.addEventListener('click', openAddScheduleModal);
    }
    
    // Import Excel
    const importExcelBtn = document.getElementById('importExcelBtn');
    if (importExcelBtn) {
        importExcelBtn.addEventListener('click', openImportModal);
    }
    
    // Download template
    const downloadTemplateBtn = document.getElementById('downloadTemplateBtn');
    if (downloadTemplateBtn) {
        downloadTemplateBtn.addEventListener('click', downloadTemplate);
    }
    
    // File input change
    const excelFile = document.getElementById('excelFile');
    if (excelFile) {
        excelFile.addEventListener('change', function() {
            const validateBtn = document.getElementById('validateImportBtn');
            const importBtn = document.getElementById('processImportBtn');
            
            if (this.files.length > 0) {
                if (validateBtn) validateBtn.disabled = false;
                if (importBtn) importBtn.disabled = true;
                const preview = document.getElementById('importPreview');
                if (preview) preview.style.display = 'none';
            } else {
                if (validateBtn) validateBtn.disabled = true;
                if (importBtn) importBtn.disabled = true;
            }
        });
    }
    
    // Validate button
    const validateBtn = document.getElementById('validateImportBtn');
    if (validateBtn) {
        validateBtn.addEventListener('click', validateImport);
    }
    
    // Process import button
    const processBtn = document.getElementById('processImportBtn');
    if (processBtn) {
        processBtn.addEventListener('click', processImport);
    }
    
    // Save schedule
    const saveBtn = document.getElementById('saveScheduleBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', saveSchedule);
    }
    
    // Modal hidden event
    const scheduleModalEl = document.getElementById('scheduleModal');
    if (scheduleModalEl) {
        scheduleModalEl.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('scheduleForm');
            if (form) form.reset();
            const scheduleId = document.getElementById('scheduleId');
            if (scheduleId) scheduleId.value = '';
        });
    }
    
    const importModalEl = document.getElementById('importExcelModal');
    if (importModalEl) {
        importModalEl.addEventListener('hidden.bs.modal', function() {
            const form = document.getElementById('importExcelForm');
            if (form) form.reset();
            const preview = document.getElementById('importPreview');
            if (preview) preview.style.display = 'none';
            const progress = document.getElementById('importProgress');
            if (progress) progress.style.display = 'none';
            const validateBtn = document.getElementById('validateImportBtn');
            if (validateBtn) validateBtn.disabled = true;
            const processBtn = document.getElementById('processImportBtn');
            if (processBtn) processBtn.disabled = true;
        });
    }
}

// Load schedules
function loadSchedules() {
    console.log('Loading schedules with filters:', currentFilters);
    
    // Show loading
    const loadingSpinner = document.getElementById('loadingSpinner');
    const noResults = document.getElementById('noResults');
    const schedulesContainer = document.getElementById('schedulesContainer');
    
    if (loadingSpinner) loadingSpinner.style.display = 'block';
    if (noResults) noResults.style.display = 'none';
    if (schedulesContainer) schedulesContainer.innerHTML = '';
    
    // Build query
    let query = 'action=get_schedules';
    if (currentFilters.course !== 'all') query += '&course=' + currentFilters.course;
    if (currentFilters.day !== 'all') query += '&day=' + currentFilters.day;
    if (currentFilters.faculty !== 'all') query += '&faculty=' + currentFilters.faculty;
    if (currentFilters.room !== 'all') query += '&room=' + currentFilters.room;
    if (currentFilters.search) query += '&search=' + encodeURIComponent(currentFilters.search);
    
    const url = 'ajax/schedule_ajax.php?' + query;
    console.log('Fetching:', url);
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return parseJsonSafe(response);
        })
        .then(result => {
            console.log('Parsed result:', result);
            
            if (loadingSpinner) loadingSpinner.style.display = 'none';
            
            if (!result.ok) {
                console.error('Invalid response from server:', result.error);
                if (result.text) {
                    console.error('Response text:', result.text);
                }
                showError('Server error: Invalid response format');
                return;
            }
            
            const data = result.data;
            console.log('Data received:', data);
            
            if (data.success && data.schedules) {
                if (Object.keys(data.schedules).length > 0) {
                    displaySchedules(data.schedules);
                } else {
                    showNoResults();
                }
            } else {
                console.error('Server returned error:', data.message);
                showError(data.message || 'Failed to load schedules');
            }
        })
        .catch(error => {
            console.error('Error loading schedules:', error);
            if (loadingSpinner) loadingSpinner.style.display = 'none';
            showError('Error loading schedules: ' + error.message);
        });
}

// Display schedules
function displaySchedules(schedules) {
    console.log('Displaying schedules:', schedules);
    const container = document.getElementById('schedulesContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    // Check if schedules is empty
    if (!schedules || Object.keys(schedules).length === 0) {
        showNoResults();
        return;
    }
    
    // Process each course section
    let hasContent = false;
    
    for (const [courseId, courseSchedules] of Object.entries(schedules)) {
        if (courseSchedules && courseSchedules.length > 0) {
            hasContent = true;
            
            // Sort schedules by day and time
            courseSchedules.sort((a, b) => {
                const days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                const dayA = days.indexOf(a.Day);
                const dayB = days.indexOf(b.Day);
                
                if (dayA !== dayB) return dayA - dayB;
                return (a.Start_time || '').localeCompare(b.Start_time || '');
            });
            
            const courseName = courseSchedules[0].CourseSection || 'Uncategorized';
            const table = createScheduleTable(courseName, courseSchedules);
            container.appendChild(table);
        }
    }
    
    if (!hasContent) {
        showNoResults();
    }
    
    console.log('Schedules displayed');
}

// Create schedule table
function createScheduleTable(courseName, schedules) {
    const div = document.createElement('div');
    div.className = 'card mb-4 shadow-sm schedule-card';
    
    const cardHeader = document.createElement('div');
    cardHeader.className = 'card-header bg-white border-bottom-0 pb-0';
    cardHeader.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">${escapeHtml(courseName)}</h5>
            <span class="badge bg-primary">${schedules.length} schedule(s)</span>
        </div>
    `;
    div.appendChild(cardHeader);
    
    const cardBody = document.createElement('div');
    cardBody.className = 'card-body pt-2';
    
    const table = document.createElement('table');
    table.className = 'table table-hover align-middle mb-0';
    table.innerHTML = `
        <thead class="table-light">
            <tr>
                <th width="10%">CODE</th>
                <th width="20%">DESCRIPTION</th>
                <th width="8%">DAY</th>
                <th width="10%">START</th>
                <th width="10%">END</th>
                <th width="10%">ROOM</th>
                <th width="15%">FACULTY</th>
                <th width="17%">ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            ${schedules.map(schedule => `
                <tr>
                    <td><strong>${escapeHtml(schedule.Code || '')}</strong></td>
                    <td>${escapeHtml(schedule.Description || '-')}</td>
                    <td><span class="badge bg-info">${escapeHtml(schedule.Day || '')}</span></td>
                    <td>${formatTime(schedule.Start_time)}</td>
                    <td>${formatTime(schedule.End_time)}</td>
                    <td><span class="badge bg-secondary">${escapeHtml(schedule.Room_code || '')}</span></td>
                    <td>${schedule.Faculty_Status === 'Inactive' 
                        ? `<span class="text-danger" title="Faculty is Inactive">
                            <i class="fas fa-exclamation-circle me-1"></i>${escapeHtml(schedule.Faculty_name || '')}
                           </span>` 
                        : escapeHtml(schedule.Faculty_name || '')
                    }</td>
                    <td>
                        <button class="btn btn-success btn-sm edit-btn me-1" data-id="${schedule.Schedule_id}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${schedule.Schedule_id}" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `).join('')}
        </tbody>
    `;
    
    cardBody.appendChild(table);
    div.appendChild(cardBody);
    
    // Add event listeners to buttons after a short delay
    setTimeout(() => {
        div.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                editSchedule(this.dataset.id);
            });
        });
        
        div.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                deleteSchedule(this.dataset.id);
            });
        });
    }, 100);
    
    return div;
}

// Helper function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Format time for display
function formatTime(timeString) {
    if (!timeString) return '';
    try {
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minutes} ${ampm}`;
    } catch (e) {
        return timeString;
    }
}

// Clear filters
function clearAllFilters() {
    const courseFilter = document.getElementById('courseFilter');
    const dayFilter = document.getElementById('dayFilter');
    const facultyFilter = document.getElementById('facultyFilter');
    const roomFilter = document.getElementById('roomFilter');
    const searchInput = document.getElementById('searchInput');
    
    if (courseFilter) courseFilter.value = 'all';
    if (dayFilter) dayFilter.value = 'all';
    if (facultyFilter) facultyFilter.value = 'all';
    if (roomFilter) roomFilter.value = 'all';
    if (searchInput) searchInput.value = '';
    
    currentFilters = {
        course: 'all',
        day: 'all',
        faculty: 'all',
        room: 'all',
        search: ''
    };
    
    loadSchedules();
}

// Open add modal
function openAddScheduleModal() {
    const form = document.getElementById('scheduleForm');
    if (form) form.reset();
    
    const scheduleId = document.getElementById('scheduleId');
    if (scheduleId) scheduleId.value = '';
    
    const modalLabel = document.getElementById('scheduleModalLabel');
    if (modalLabel) modalLabel.textContent = 'Add New Schedule';
    
    const sections = document.getElementById('courseSections');
    if (sections) {
        for (let option of sections.options) {
            option.selected = false;
        }
    }
    
    if (scheduleModal) scheduleModal.show();
}

// Open import modal
function openImportModal() {
    const form = document.getElementById('importExcelForm');
    if (form) form.reset();
    
    const preview = document.getElementById('importPreview');
    if (preview) preview.style.display = 'none';
    
    const progress = document.getElementById('importProgress');
    if (progress) progress.style.display = 'none';
    
    const validateBtn = document.getElementById('validateImportBtn');
    if (validateBtn) validateBtn.disabled = true;
    
    const processBtn = document.getElementById('processImportBtn');
    if (processBtn) processBtn.disabled = true;
    
    if (importExcelModal) importExcelModal.show();
}

// Download template
function downloadTemplate() {
    const headers = ['Subject Code', 'Course Section', 'Day', 'Start Time', 'End Time', 'Room Code', 'Faculty Email'];
    const sampleData = [
        ['IT101', 'BSCS-2A', 'Mon', '08:00', '09:30', 'LAB101', 'john.doe@lyceum.edu'],
        ['ENGL101', 'BSIT-1B', 'Wed', '10:00', '11:30', 'RM203', 'jane.smith@lyceum.edu']
    ];
    
    let csvContent = headers.join(',') + '\n';
    sampleData.forEach(row => {
        csvContent += row.join(',') + '\n';
    });
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'schedule_template.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// Validate import
function validateImport() {
    const file = document.getElementById('excelFile').files[0];
    if (!file) {
        Swal.fire('Error', 'Please select a file', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('excel_file', file);
    formData.append('action', 'validate');
    formData.append('skip_first_row', document.getElementById('skipFirstRow').checked);
    
    Swal.fire({
        title: 'Validating...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    fetch('ajax/import_schedule.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success) {
            displayImportPreview(data);
            document.getElementById('processImportBtn').disabled = false;
        } else {
            Swal.fire('Validation Failed', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Validation error:', error);
        Swal.fire('Error', 'Failed to validate file: ' + error.message, 'error');
    });
}

// Display import preview
function displayImportPreview(data) {
    const previewDiv = document.getElementById('importPreview');
    const previewHeader = document.getElementById('previewHeader');
    const previewBody = document.getElementById('previewBody');
    const previewCount = document.getElementById('previewCount');
    const previewSummary = document.getElementById('previewSummary');
    
    if (!previewDiv || !previewHeader || !previewBody) return;
    
    // Set header
    previewHeader.innerHTML = `
        <th>#</th>
        <th>Subject</th>
        <th>Section</th>
        <th>Day</th>
        <th>Start</th>
        <th>End</th>
        <th>Room</th>
        <th>Faculty</th>
        <th>Status</th>
    `;
    
    // Set body
    previewBody.innerHTML = '';
    let validCount = 0;
    let invalidCount = 0;
    
    if (data.data && Array.isArray(data.data)) {
        data.data.forEach(row => {
            const tr = document.createElement('tr');
            tr.className = row.valid ? 'valid-row' : 'invalid-row';
            
            let statusHtml = row.valid 
                ? '<span class="badge bg-success">Valid</span>'
                : `<span class="badge bg-danger">Invalid</span>
                   <small class="d-block text-danger">${(row.errors || []).join(', ')}</small>`;
            
            if (row.valid) validCount++;
            else invalidCount++;
            
            tr.innerHTML = `
                <td>${row.row || ''}</td>
                <td>${escapeHtml(row.subject_code || '')}</td>
                <td>${escapeHtml(row.course_section || '')}</td>
                <td>${escapeHtml(row.day || '')}</td>
                <td>${escapeHtml(row.start_time || '')}</td>
                <td>${escapeHtml(row.end_time || '')}</td>
                <td>${escapeHtml(row.room_code || '')}</td>
                <td>${escapeHtml(row.faculty_email || '')}</td>
                <td>${statusHtml}</td>
            `;
            previewBody.appendChild(tr);
        });
    }
    
    if (previewCount) {
        previewCount.textContent = `${(data.data || []).length} rows`;
    }
    
    if (previewSummary) {
        previewSummary.innerHTML = `
            <div class="d-flex gap-3">
                <span class="text-success"><i class="fas fa-check-circle"></i> Valid: ${validCount}</span>
                <span class="text-danger"><i class="fas fa-times-circle"></i> Invalid: ${invalidCount}</span>
            </div>
        `;
    }
    
    previewDiv.style.display = 'block';
}

// Process import
function processImport() {
    const file = document.getElementById('excelFile').files[0];
    if (!file) {
        Swal.fire('Error', 'Please select a file', 'error');
        return;
    }
    
    const formData = new FormData();
    formData.append('excel_file', file);
    formData.append('action', 'import');
    formData.append('skip_first_row', document.getElementById('skipFirstRow').checked);
    formData.append('update_existing', document.getElementById('updateExisting').checked);
    
    Swal.fire({
        title: 'Importing...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    fetch('ajax/import_schedule.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Import Successful',
                html: `
                    <p>${data.message || ''}</p>
                    <div class="text-start mt-3">
                        <strong>Summary:</strong><br>
                        ✅ Imported: ${data.imported || 0}<br>
                        ⚠️ Skipped: ${data.skipped || 0}<br>
                        ❌ Failed: ${data.failed || 0}
                    </div>
                `,
                confirmButtonText: 'OK'
            }).then(() => {
                if (importExcelModal) importExcelModal.hide();
                loadSchedules();
            });
        } else {
            Swal.fire('Import Failed', data.message || 'Unknown error', 'error');
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Import error:', error);
        Swal.fire('Error', 'Failed to import file: ' + error.message, 'error');
    });
}

// Edit schedule
function editSchedule(scheduleId) {
    if (!scheduleId) return;
    
    Swal.fire({
        title: 'Loading schedule details...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    fetch('ajax/schedule_ajax.php?action=get_schedule&id=' + scheduleId)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return parseJsonSafe(response);
        })
        .then(result => {
            Swal.close();
            
            if (!result.ok) {
                Swal.fire('Error', 'Failed to load schedule details', 'error');
                return;
            }
            
            const data = result.data;
            if (data.success) {
                populateEditForm(data);
                if (scheduleModal) scheduleModal.show();
            } else {
                Swal.fire('Error', data.message || 'Failed to load schedule details', 'error');
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Error loading schedule:', error);
            Swal.fire('Error', 'Failed to load schedule details: ' + error.message, 'error');
        });
}

// Populate edit form
function populateEditForm(data) {
    const modalLabel = document.getElementById('scheduleModalLabel');
    if (modalLabel) modalLabel.textContent = 'Edit Schedule';
    
    const scheduleId = document.getElementById('scheduleId');
    if (scheduleId) scheduleId.value = data.schedule.Schedule_id || '';
    
    const subject = document.getElementById('subject');
    if (subject) subject.value = data.schedule.Subject_id || '';
    
    const room = document.getElementById('room');
    if (room) room.value = data.schedule.Room_id || '';
    
    const faculty = document.getElementById('faculty');
    if (faculty) faculty.value = data.schedule.Faculty_id || '';
    
    const day = document.getElementById('day');
    if (day) day.value = data.schedule.Day || '';
    
    const startTime = document.getElementById('startTime');
    if (startTime) startTime.value = data.schedule.Start_time || '';
    
    const endTime = document.getElementById('endTime');
    if (endTime) endTime.value = data.schedule.End_time || '';
    
    const sections = document.getElementById('courseSections');
    if (sections) {
        const courseSections = data.course_sections || [];
        for (let option of sections.options) {
            option.selected = courseSections.includes(parseInt(option.value));
        }
    }
}

// Save schedule
function saveSchedule() {
    const form = document.getElementById('scheduleForm');
    if (!form) return;
    
    if (!form.checkValidity()) {
        form.classList.add('was-validated');
        const invalidField = form.querySelector(':invalid');
        if (invalidField) invalidField.focus();
        return;
    }
    
    const sections = document.getElementById('courseSections');
    const selectedSections = [];
    if (sections) {
        for (let option of sections.selectedOptions) {
            if (option.value) selectedSections.push(option.value);
        }
    }
    
    if (selectedSections.length === 0) {
        Swal.fire('Validation Error', 'Please select at least one course section', 'error');
        if (sections) sections.focus();
        return;
    }
    
    const startTime = document.getElementById('startTime');
    const endTime = document.getElementById('endTime');
    
    if (startTime && endTime && startTime.value >= endTime.value) {
        Swal.fire('Time Error', 'End time must be after start time', 'error');
        if (endTime) endTime.focus();
        return;
    }
    
    const formData = new FormData(form);
    selectedSections.forEach(section => formData.append('course_sections[]', section));
    formData.append('action', 'save_schedule');
    
    const saveBtn = document.getElementById('saveScheduleBtn');
    const originalText = saveBtn ? saveBtn.innerHTML : 'Save Schedule';
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    }
    
    Swal.fire({
        title: 'Saving Schedule...',
        text: 'Please wait',
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
    });
    
    fetch('ajax/schedule_ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Network response was not ok');
        return parseJsonSafe(response);
    })
    .then(result => {
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
        Swal.close();

        if (!result.ok) {
            Swal.fire('Server Error', 'Failed to save schedule', 'error');
            return;
        }

        const data = result.data;
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                if (scheduleModal) scheduleModal.hide();
                loadSchedules();
            });
        } else {
            Swal.fire('Error', data.message || 'Failed to save schedule', 'error');
        }
    })
    .catch(error => {
        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.innerHTML = originalText;
        }
        Swal.close();
        console.error('Error saving schedule:', error);
        Swal.fire('Error', 'Failed to save schedule: ' + error.message, 'error');
    });
}

// Delete schedule
function deleteSchedule(scheduleId) {
    if (!scheduleId) return;
    
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Deleting...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });
            
            const formData = new FormData();
            formData.append('action', 'delete_schedule');
            formData.append('schedule_id', scheduleId);
            
            fetch('ajax/schedule_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return parseJsonSafe(response);
            })
            .then(result => {
                Swal.close();
                
                if (!result.ok) {
                    Swal.fire('Server Error', 'Failed to delete schedule', 'error');
                    return;
                }
                
                const data = result.data;
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => loadSchedules());
                } else {
                    Swal.fire('Error', data.message || 'Failed to delete schedule', 'error');
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Error deleting schedule:', error);
                Swal.fire('Error', 'Failed to delete schedule: ' + error.message, 'error');
            });
        }
    });
}

// Show no results
function showNoResults() {
    const noResults = document.getElementById('noResults');
    if (!noResults) return;
    
    noResults.style.display = 'block';
    noResults.innerHTML = `
        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
        <p class="text-muted">No schedules found matching your criteria.</p>
        <button class="btn btn-outline-primary mt-2" onclick="clearAllFilters()">
            <i class="fas fa-times me-1"></i>Clear filters
        </button>
    `;
}

// Show error
function showError(message) {
    const noResults = document.getElementById('noResults');
    if (!noResults) return;
    
    noResults.style.display = 'block';
    noResults.innerHTML = `
        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
        <p class="text-danger">${escapeHtml(message)}</p>
        <button class="btn btn-outline-primary mt-2" onclick="loadSchedules()">
            <i class="fas fa-redo me-1"></i>Try Again
        </button>
    `;
}