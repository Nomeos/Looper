$('form').submit(function (event) {
    event.preventDefault();

    let current_url = window.location.href;

    // Get all the forms elements and their values in one step
    let form_data_array = $(this).serializeArray();
    let form_data = $(this).serialize();
    let http_method = form_data_array.find(o => o.name === '_method').value;

    $.ajax({
        url: $(this).attr('action'),
        type: http_method,
        data: form_data,
        success: function (response) {
            window.location.replace(current_url);
        }
    });
});