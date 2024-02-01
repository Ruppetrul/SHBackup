function customer_do_request(options) {
    options = options || {};

    if (options.data === null || typeof options.data !== 'object') {
        options.data = new FormData();
    }

    if (options.csrfToken) {
        options.data.append('_token', options.csrfToken);
    }

    options.contentType = typeof options.contentType !== 'undefined' ? options.contentType : true;
    options.processData = typeof options.processData !== 'undefined' ? options.processData : true;

    $.ajax({
        type: options.method || 'GET',
        url: options.url || '',
        data: options.data,
        headers: options.headers || {},
        contentType: options.contentType,
        processData: options.processData,
        success: function (responseData) {
            console.log("Success:", responseData);
            if (options.success && typeof options.success === 'function') {
                options.success(responseData);
            }
        },
        error: function (errorData) {
            console.log('Error:', errorData);
            if (options.error && typeof options.error === 'function') {
                options.error(errorData);
            }
        }
    });
}

function create_shop(data, token, url, successCallback, errorCallback) {

    const options = {
        method: 'POST',
        url: url,
        data: data,
        headers: {},
        contentType: false,
        processData: false,
        success: function (response) {
            successCallback(response);
        },
        error: function (error) {
            errorCallback(error);
        }
    }

    options.csrfToken = token;
    customer_do_request(options, token);
}

function test() {
    console.log('test');
}

console.log('include');
