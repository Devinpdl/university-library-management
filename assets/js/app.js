// App initialization
$(document).ready(function() {
    // Initialize Bootstrap components
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
});

(function loadFines() {
    const search = $('#fineSearch').val();
    const page = $('#finesPagination .page-item.active').data('page') || 1;
    
    $.get('api/fines.php', { action: 'list', search: search, page: page }, function(data) {
        app.fines = data.fines;
        const $tableBody = $('#finesTableBody');
        $tableBody.empty();
        app.fines.forEach(fine => {
            $tableBody.append(`
                <tr>
                    <td>${fine.id}</td>
                    <td>${fine.user_name} (${fine.student_id})</td>
                    <td>${fine.title || 'N/A'}</td>
                    <td>$${parseFloat(fine.amount).toFixed(2)}</td>
                    <td>${fine.reason}</td>
                    <td><span class="badge badge-${fine.status === 'pending' ? 'warning' : fine.status === 'paid' ? 'success' : 'info'}">${fine.status}</span></td>
                    <td>
                        ${fine.status === 'pending' ? `
                            <button class="btn btn-sm btn-success pay-fine" data-id="${fine.id}"><i class="fas fa-money-bill"></i></button>
                            <button class="btn btn-sm btn-info waive-fine" data-id="${fine.id}"><i class="fas fa-hand-paper"></i></button>
                        ` : ''}
                    </td>
                </tr>
            `);
        });
        
        updatePagination('fines', data.total_pages, page);
        
        $('.pay-fine').on('click', function() {
            const fineId = $(this).data('id');
            payFine(fineId);
        });
        
        $('.waive-fine').on('click', function() {
            const fineId = $(this).data('id');
            waiveFine(fineId);
        });
    });
})();

function payFine(fineId) {
    swal({
        title: 'Pay Fine',
        text: 'Select payment method:',
        type: 'input',
        inputType: 'select',
        inputOptions: {
            'cash': 'Cash',
            'card': 'Credit/Debit Card',
            'online': 'Online Payment'
        },
        showCancelButton: true,
        confirmButtonText: 'Confirm Payment'
    }, function(paymentMethod) {
        if (paymentMethod) {
            $.post('api/fines.php?action=pay', { id: fineId, payment_method: paymentMethod }, function(response) {
                if (response.success) {
                    loadFines();
                    swal('Success', 'Fine paid successfully!', 'success');
                } else {
                    swal('Error', response.message, 'error');
                }
            });
        }
    });
}

function waiveFine(fineId) {
    swal({
        title: 'Are you sure?',
        text: 'This will waive the fine!',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, waive it!'
    }, function(isConfirm) {
        if (isConfirm) {
            $.post('api/fines.php?action=waive', { id: fineId }, function(response) {
                if (response.success) {
                    loadFines();
                    swal('Success', 'Fine waived successfully!', 'success');
                } else {
                    swal('Error', response.message, 'error');
                }
            });
        }
    });
}

function loadReports() {
    $.get('api/reports.php?action=overdue', function(data) {
        const $tableBody = $('#overdueReportTableBody');
        $tableBody.empty();
        data.overdue_issues.forEach(issue => {
            $tableBody.append(`
                <tr>
                    <td>${issue.id}</td>
                    <td>${issue.title}</td>
                    <td>${issue.user_name} (${issue.student_id})</td>
                    <td>${new Date(issue.due_date).toLocaleDateString()}</td>
                    <td>${issue.days_overdue}</td>
                    <td>
                        <button class="btn btn-sm btn-primary notify-user" data-id="${issue.user_id}"><i class="fas fa-envelope"></i></button>
                    </td>
                </tr>
            `);
        });
    });
}

function loadSettings() {
    $.get('api/settings.php?action=list', function(data) {
        const $settingsList = $('#settingsList');
        $settingsList.empty();
        data.settings.forEach(setting => {
            $settingsList.append(`
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">${setting.description} (${setting.setting_key})</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control setting-value" data-key="${setting.setting_key}" value="${setting.setting_value}">
                    </div>
                    <div class="col-sm-4">
                        <button class="btn btn-primary update-setting" data-key="${setting.setting_key}"><i class="fas fa-save"></i> Update</button>
                    </div>
                </div>
            `);
        });
        
        $('.update-setting').on('click', function() {
            const key = $(this).data('key');
            const value = $(`.setting-value[data-key="${key}"]`).val();
            updateSetting(key, value);
        });
    });
}

function updateSetting(key, value) {
    $.post('api/settings.php?action=update', { key: key, value: value }, function(response) {
        if (response.success) {
            swal('Success', 'Setting updated successfully!', 'success');
        } else {
            swal('Error', response.message, 'error');
        }
    });
}

function loadProfile() {
    $.get('api/users.php?action=profile', function(data) {
        $('#profileForm [name="first_name"]').val(data.first_name);
        $('#profileForm [name="last_name"]').val(data.last_name);
        $('#profileForm [name="email"]').val(data.email);
        $('#profileForm [name="phone"]').val(data.phone);
        $('#profileForm [name="address"]').val(data.address);
    });
}

function updatePagination(type, totalPages, currentPage) {
    const $pagination = $(`#${type}Pagination`);
    $pagination.empty();
    
    if (totalPages <= 1) return;
    
    $pagination.append(`
        <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
        </li>
    `);
    
    for (let i = 1; i <= totalPages; i++) {
        $pagination.append(`
            <li class="page-item ${i === currentPage ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a>
            </li>
        `);
    }
    
    $pagination.append(`
        <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
        </li>
    `);
    
    $pagination.find('.page-link').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            $(`#${type}Pagination .page-item`).removeClass('active');
            $(`#${type}Pagination .page-item:has(a[data-page="${page}"])`).addClass('active');
            switch(type) {
                case 'books': loadBooks(); break;
                case 'users': loadUsers(); break;
                case 'issues': loadIssues(); break;
                case 'reservations': loadReservations(); break;
                case 'fines': loadFines(); break;
            }
        }
    });
}

function initializeAddBookForm() {
    $.get('api/books.php?action=filters', function(data) {
        app.categories = data.categories;
        app.authors = data.authors;
        app.publishers = data.publishers;
        
        const $authorSelect = $('#addBookForm [name="author_id"]');
        const $categorySelect = $('#addBookForm [name="category_id"]');
        const $publisherSelect = $('#addBookForm [name="publisher_id"]');
        
        $authorSelect.append('<option value="">Select Author</option>');
        app.authors.forEach(author => {
            $authorSelect.append(`<option value="${author.id}">${author.name}</option>`);
        });
        
        $categorySelect.append('<option value="">Select Category</option>');
        app.categories.forEach(category => {
            $categorySelect.append(`<option value="${category.id}">${category.name}</option>`);
        });
        
        $publisherSelect.append('<option value="">Select Publisher</option>');
        app.publishers.forEach(publisher => {
            $publisherSelect.append(`<option value="${publisher.id}">${publisher.name}</option>`);
        });
    });
}

function handleLogin(event) {
    event.preventDefault();
    const email = $('#email').val();
    const password = $('#password').val();

    $.post('api/auth.php?action=login', { email: email, password: password }, function(response) {
        if (response.success) {
            window.location.href = 'dashboard.php'; // Redirect to dashboard on success
        } else {
            swal('Error', response.message, 'error');
        }
    }).fail(function() {
        swal('Error', 'An unexpected error occurred. Please try again.', 'error');
    });
}

$(document).ready(function() {
    initializeAddBookForm();
});