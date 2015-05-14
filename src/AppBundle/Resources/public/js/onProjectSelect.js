$(document).ready(function() {
    var project = $('#app_issue_project');
    project.change(function() {
        var $form = $(this).closest('form');
        var data = {};
        data[project.attr('name')] = project.val();
        $.ajax({
            url : $form.attr('action'),
            type: $form.attr('method'),
            data : data,
            success: function(html) {
                // Replace current position field ...
                $('#app_issue_assignee').replaceWith(
                    // ... with the returned one from the AJAX response.
                    $(html).find('#app_issue_assignee')
                );
            }
        });
    });
});
