jQuery(document).ready(function ($) {
    // Search Users
    $('#lpbe-search-btn').on('click', function () {
        performSearch();
    });

    // Allow search with Enter key
    $('#lpbe-user-search').on('keypress', function (e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            performSearch();
        }
    });

    function performSearch() {
        var term = $('#lpbe-user-search').val();
        if (term.length < 3) {
            alert('Please enter at least 3 characters.');
            return;
        }

        // Show loading state
        $('#lpbe-search-results').html('<p style="text-align:center; padding:20px;"><span class="spinner is-active" style="float:none;"></span> Searching...</p>');

        $.post(lpbe_vars.ajax_url, {
            action: 'lpbe_search_users',
            term: term,
            nonce: lpbe_vars.nonce
        }, function (response) {
            if (response.success) {
                var html = '';
                if (response.data.length === 0) {
                    html = '<p>No users found.</p>';
                } else {
                    html = '<ul>';
                    $.each(response.data, function (index, user) {
                        html += '<li class="lpbe-user-item" data-id="' + user.ID + '" data-name="' + user.display_name + '">' +
                            user.display_name + ' (' + user.user_email + ') <button type="button" class="button lpbe-add-user">Add</button></li>';
                    });
                    html += '</ul>';
                }
                $('#lpbe-search-results').html(html);
            } else {
                $('#lpbe-search-results').html('<p style="color:red;">Error: ' + response.data + '</p>');
            }
        }).fail(function () {
            $('#lpbe-search-results').html('<p style="color:red;">Error connecting to server</p>');
        });
    }

    // Add User to Selection
    $(document).on('click', '.lpbe-add-user', function () {
        var $item = $(this).closest('.lpbe-user-item');
        var userId = $item.data('id');
        var userName = $item.data('name');

        if ($('#lpbe-selected-user-' + userId).length > 0) {
            return; // Already selected
        }

        var html = '<div class="lpbe-selected-user" id="lpbe-selected-user-' + userId + '" data-id="' + userId + '">' +
            '<span class="lpbe-user-name">' + userName + '</span>' +
            '<button type="button" class="button lpbe-remove-user">Remove</button>' +
            '<button type="button" class="button lpbe-view-courses">View Courses</button>' +
            '<div class="lpbe-user-courses" style="display:none;"></div>' +
            '</div>';

        $('#lpbe-selected-users').append(html);

        // Auto fetch courses
        fetchCourses(userId);

        // Show Clear All button
        updateClearAllButton();
    });

    // Remove User
    $(document).on('click', '.lpbe-remove-user', function () {
        $(this).closest('.lpbe-selected-user').remove();
        $('#lpbe-courses-container').empty();

        // Update Clear All button visibility
        updateClearAllButton();
    });

    // Clear All Users
    $('#lpbe-clear-all-btn').on('click', function () {
        if (confirm('Are you sure you want to remove all selected users?')) {
            $('#lpbe-selected-users').empty();
            $('#lpbe-courses-container').empty();
            updateClearAllButton();
        }
    });

    // Update Clear All button visibility
    function updateClearAllButton() {
        var count = $('.lpbe-selected-user').length;
        if (count > 0) {
            $('#lpbe-clear-all-btn').text('Clear All (' + count + ')').show();
        } else {
            $('#lpbe-clear-all-btn').hide();
        }
    }

    // View Courses (populate right column)
    $(document).on('click', '.lpbe-view-courses', function () {
        var $userDiv = $(this).closest('.lpbe-selected-user');
        var userId = $userDiv.data('id');
        var userName = $userDiv.find('.lpbe-user-name').text();

        // Highlight selected
        $('.lpbe-selected-user').removeClass('active');
        $userDiv.addClass('active');

        // Show loading state with user name
        $('#lpbe-courses-container').html(
            '<h3>Courses for ' + userName + '</h3>' +
            '<p style="text-align:center; padding:20px;"><span class="spinner is-active" style="float:none;"></span> Loading courses...</p>'
        );

        var storedCourses = $userDiv.data('courses');
        if (storedCourses) {
            renderCourses(storedCourses, userId, userName);
        } else {
            fetchCourses(userId, true, userName);
        }
    });

    function fetchCourses(userId, render, userName) {
        $.post(lpbe_vars.ajax_url, {
            action: 'lpbe_get_user_courses',
            user_id: userId,
            nonce: lpbe_vars.nonce
        }, function (response) {
            if (response.success) {
                $('#lpbe-selected-user-' + userId).data('courses', response.data);
                if (render) {
                    renderCourses(response.data, userId, userName);
                }
            } else {
                console.log('Error fetching courses');
                $('#lpbe-courses-container').html('<p style="color:red;">Error loading courses</p>');
            }
        }).fail(function () {
            $('#lpbe-courses-container').html('<p style="color:red;">Error connecting to server</p>');
        });
    }

    function renderCourses(courses, userId, userName) {
        // If userName not provided, try to get it from the DOM
        if (!userName) {
            userName = $('#lpbe-selected-user-' + userId).find('.lpbe-user-name').text() || 'User';
        }

        var html = '<h3 style="color: #2271b1; border-bottom: 2px solid #2271b1; padding-bottom: 8px; margin-bottom: 15px;">Courses for ' + userName + '</h3>';
        html += '<label style="margin-bottom: 10px; display: block;"><input type="checkbox" class="lpbe-select-all-courses" data-userid="' + userId + '" checked> <strong>Select All</strong></label>';
        html += '<div class="lpbe-course-list" style="max-height: 400px; overflow-y: auto;">';

        if (courses.length === 0) {
            html += '<p style="color: #666; font-style: italic;">No enrolled courses found.</p>';
        } else {
            $.each(courses, function (index, course) {
                html += '<div class="lpbe-course-item" style="padding: 8px; margin: 5px 0; background: #f9f9f9; border-left: 3px solid #2271b1;">' +
                    '<label><input type="checkbox" class="lpbe-course-checkbox" name="user_' + userId + '_courses[]" value="' + course.id + '" checked> ' +
                    '<strong>' + course.title + '</strong> <span style="color: #666; font-size: 12px;">(' + course.status + ' - ' + course.graduation + ')</span></label>' +
                    '</div>';
            });
        }
        html += '</div>';
        $('#lpbe-courses-container').html(html);
    }

    // Select All Courses
    $(document).on('change', '.lpbe-select-all-courses', function () {
        var checked = $(this).is(':checked');
        $('.lpbe-course-checkbox').prop('checked', checked);
        updateUserSelection($(this).data('userid'));
    });

    $(document).on('change', '.lpbe-course-checkbox', function () {
        var name = $(this).attr('name');
        var match = name.match(/user_(\d+)_courses/);
        if (match) {
            updateUserSelection(match[1]);
        }
    });

    function updateUserSelection(userId) {
        var selectedIds = [];
        $('.lpbe-course-checkbox:checked').each(function () {
            selectedIds.push($(this).val());
        });
        $('#lpbe-selected-user-' + userId).data('selected-courses', selectedIds);
    }

    // Export PDF
    $('#lpbe-export-btn').on('click', function () {
        var result = collectData();
        if (!result) return;

        $('#lpbe-loading').text('Processing...').show();

        $.post(lpbe_vars.ajax_url, {
            action: 'lpbe_generate_pdf',
            data: result.data,
            include_comparison: result.include_comparison,
            debug_mode: result.debug_mode,
            nonce: lpbe_vars.nonce
        }, function (response) {
            $('#lpbe-loading').hide();
            if (response.success) {
                // Show debug logs if debug mode is enabled
                if (response.data.debug_logs) {
                    alert('DEBUG LOGS:\n\n' + response.data.debug_logs.join('\n'));
                }

                var link = document.createElement('a');
                link.href = 'data:application/pdf;base64,' + response.data.pdf;
                link.download = 'learnpress-export.pdf';
                link.click();
            } else {
                alert('Error generating PDF: ' + response.data);
            }
        });
    });

    // Send Email
    $('#lpbe-email-btn').on('click', function () {
        var result = collectData();
        if (!result) return;

        $('#lpbe-loading').text('Sending Emails...').show();

        $.post(lpbe_vars.ajax_url, {
            action: 'lpbe_send_email',
            data: result.data,
            include_comparison: result.include_comparison,
            debug_mode: result.debug_mode,
            nonce: lpbe_vars.nonce
        }, function (response) {
            $('#lpbe-loading').hide();
            if (response.success) {
                alert(response.data.message);
            } else {
                alert('Error sending emails: ' + response.data);
            }
        });
    });

    function collectData() {
        var data = [];
        $('.lpbe-selected-user').each(function () {
            var userId = $(this).data('id');
            var selectedCourses = $(this).data('selected-courses');

            if (!selectedCourses) {
                var allCourses = $(this).data('courses');
                if (allCourses) {
                    selectedCourses = allCourses.map(function (c) { return c.id; });
                } else {
                    selectedCourses = [];
                }
            }

            if (selectedCourses.length > 0) {
                data.push({
                    user_id: userId,
                    courses: selectedCourses
                });
            }
        });

        if (data.length === 0) {
            alert('Please select users and courses.\n\nNote: Users without enrolled courses or with no courses selected will not be included.');
            return null;
        }

        return {
            data: data,
            include_comparison: $('#lpbe-include-comparison').is(':checked'),
            debug_mode: $('#lpbe-debug-mode').is(':checked')
        };
    }
});