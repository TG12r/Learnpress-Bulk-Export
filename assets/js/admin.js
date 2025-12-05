jQuery(document).ready(function($) {
    // Search Users
    $('#lpbe-search-btn').on('click', function() {
        var term = $('#lpbe-user-search').val();
        if (term.length < 3) {
            alert('Please enter at least 3 characters.');
            return;
        }

        $.post(lpbe_vars.ajax_url, {
            action: 'lpbe_search_users',
            term: term,
            nonce: lpbe_vars.nonce
        }, function(response) {
            if (response.success) {
                var html = '';
                if (response.data.length === 0) {
                    html = '<p>No users found.</p>';
                } else {
                    html = '<ul>';
                    $.each(response.data, function(index, user) {
                        html += '<li class="lpbe-user-item" data-id="' + user.ID + '" data-name="' + user.display_name + '">' +
                            user.display_name + ' (' + user.user_email + ') <button type="button" class="button lpbe-add-user">Add</button></li>';
                    });
                    html += '</ul>';
                }
                $('#lpbe-search-results').html(html);
            } else {
                alert('Error: ' + response.data);
            }
        });
    });

    // Add User to Selection
    $(document).on('click', '.lpbe-add-user', function() {
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
            '<div class="lpbe-user-courses" style="display:none;"></div>' + // Container for courses
            '</div>';
        
        $('#lpbe-selected-users').append(html);
        
        // Auto fetch courses
        fetchCourses(userId);
    });

    // Remove User
    $(document).on('click', '.lpbe-remove-user', function() {
        $(this).closest('.lpbe-selected-user').remove();
        $('#lpbe-courses-container').empty();
    });

    // View Courses (populate right column)
    $(document).on('click', '.lpbe-view-courses', function() {
        var $userDiv = $(this).closest('.lpbe-selected-user');
        var userId = $userDiv.data('id');
        var userName = $userDiv.find('.lpbe-user-name').text();
        
        // Highlight selected
        $('.lpbe-selected-user').removeClass('active');
        $userDiv.addClass('active');

        // Show courses in right column
        var coursesHtml = '<h3>Courses for ' + userName + '</h3>';
        coursesHtml += '<label><input type="checkbox" class="lpbe-select-all-courses" checked> Select All</label><hr>';
        
        var storedCourses = $userDiv.data('courses');
        if (storedCourses) {
            renderCourses(storedCourses, userId);
        } else {
            fetchCourses(userId, true);
        }
    });

    function fetchCourses(userId, render) {
        $.post(lpbe_vars.ajax_url, {
            action: 'lpbe_get_user_courses',
            user_id: userId,
            nonce: lpbe_vars.nonce
        }, function(response) {
            if (response.success) {
                $('#lpbe-selected-user-' + userId).data('courses', response.data);
                if (render) {
                    renderCourses(response.data, userId);
                }
            } else {
                console.log('Error fetching courses');
            }
        });
    }

    function renderCourses(courses, userId) {
        var html = '<h3>Courses</h3><label><input type="checkbox" class="lpbe-select-all-courses" data-userid="'+userId+'" checked> Select All</label><hr><div class="lpbe-course-list">';
        if (courses.length === 0) {
            html += '<p>No enrolled courses found.</p>';
        } else {
            $.each(courses, function(index, course) {
                html += '<div class="lpbe-course-item">' +
                    '<label><input type="checkbox" class="lpbe-course-checkbox" name="user_' + userId + '_courses[]" value="' + course.id + '" checked> ' +
                    course.title + ' (' + course.status + ' - ' + course.graduation + ')</label>' +
                    '</div>';
            });
        }
        html += '</div>';
        $('#lpbe-courses-container').html(html);
    }

    // Select All Courses
    $(document).on('change', '.lpbe-select-all-courses', function() {
        var checked = $(this).is(':checked');
        $('.lpbe-course-checkbox').prop('checked', checked);
        // Update stored selection? 
        // For simplicity, we read from DOM when exporting, or we should update data attribute.
        // Let's rely on DOM state for the active user, but we need to persist it if they switch users.
        // Actually, to keep it simple: we only export what is currently "configured". 
        // But the requirement says "escoger a varios usuarios y ... escoger los cursos ... de cada usuario".
        // So we need to store the selection state per user.
        
        // Let's save selection back to the user div data when checkboxes change.
        updateUserSelection($(this).data('userid'));
    });
    
    $(document).on('change', '.lpbe-course-checkbox', function() {
         // Find current user ID from the container or context
         // We can infer it from the name attribute user_ID_courses[]
         var name = $(this).attr('name');
         var match = name.match(/user_(\d+)_courses/);
         if(match) {
             updateUserSelection(match[1]);
         }
    });

    function updateUserSelection(userId) {
        var selectedIds = [];
        $('.lpbe-course-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
        // Store in the user div
        $('#lpbe-selected-user-' + userId).data('selected-courses', selectedIds);
    }

    // Export PDF
    $('#lpbe-export-btn').on('click', function() {
        var data = [];
        
        $('.lpbe-selected-user').each(function() {
            var userId = $(this).data('id');
            var selectedCourses = $(this).data('selected-courses');
            
            // If user hasn't been "viewed" to select specific courses, assume ALL (or none? default ALL)
            // If we fetched courses but didn't render/edit, we should default to all.
            if (!selectedCourses) {
                var allCourses = $(this).data('courses');
                if (allCourses) {
                    selectedCourses = allCourses.map(function(c) { return c.id; });
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
            alert('Please select users and courses.');
            return;
        }

        $('#lpbe-loading').show();
        
        $.post(lpbe_vars.ajax_url, {
            action: 'lpbe_generate_pdf',
            data: data,
            nonce: lpbe_vars.nonce
        }, function(response) {
            $('#lpbe-loading').hide();
            if (response.success) {
                // Download PDF
                var link = document.createElement('a');
                link.href = 'data:application/pdf;base64,' + response.data.pdf;
                link.download = 'learnpress-export.pdf';
                link.click();
            } else {
                alert('Error generating PDF: ' + response.data);
            }
        });
    });
});
