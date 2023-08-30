let Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000
});

// public/js/ajaxUtils.js
function handleAjaxRequest(url, method, data, successMessage, successCallback, errorCallback) {
    $.ajax({
        type: method,
        url: url,
        data: data,
        dataType: 'json',
        success: function (response) {
            if (successMessage) {
                Toast.fire({
                    icon: 'success',
                    title: 'Success',
                    text: successMessage,
                });
            }

            if (typeof successCallback === 'function') {
                successCallback(response);
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                let errorMessage = '';
                for (let errorField in errors) {
                    errorMessage += errors[errorField][0] + '<br>';
                }
                Toast.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    html: errorMessage,
                });
            } else {
                Toast.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred. Please try again later.',
                });
            }

            if (typeof errorCallback === 'function') {
                errorCallback();
            }
        }
    });
}

