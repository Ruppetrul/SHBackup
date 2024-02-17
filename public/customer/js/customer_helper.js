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

function delete_shop(url, token, successCallback, errorCallback) {
    const options = {
        method: 'POST',
        url: url,
        headers: {
            'X-CSRF-TOKEN': token,
            'X-HTTP-Method-Override': 'DELETE',
            // 'Content-Type': 'application/json',
        },
        contentType: false,
        processData: false,
        success: function (response) {
            successCallback(response);
        },
        error: function (error) {
            errorCallback(error);
        }
    }

    customer_do_request(options);
}

function update_media(data, url, successCallback, errorCallback) {
    const options = {
        method: 'POST',
        url: url,
        data: data,
        contentType: false,
        processData: false,
        success: function (response) {
            successCallback(response);
        },
        error: function (error) {
            errorCallback(error);
        }
    }

    customer_do_request(options);
}

function delete_media(formData, url, token, successCallback, errorCallback) {
    const options = {
        method: 'POST',
        url: url,
        data: formData,
        headers: {
            'X-CSRF-TOKEN': token,
            'X-HTTP-Method-Override': 'DELETE',
            // 'Content-Type': 'application/json',
        },
        contentType: false,
        processData: false,
        success: function (response) {
            successCallback(response);
        },
        error: function (error) {
            errorCallback(error);
        }
    }

    customer_do_request(options);
}

function delete_item(formData, url, token, successCallback, errorCallback) {
    customer_do_request({
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': token,
            'X-HTTP-Method-Override': 'DELETE',
        },
        url: url,
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            successCallback(response);
        },
        error: function (error) {
            errorCallback(error);
        }
    });
}

function add_telegram_token(telegram_token, token, url, successCallback, errorCallback) {
    const formData = new FormData();
    formData.append('telegram_token', telegram_token);
    customer_do_request({
        method: 'POST',
        url: url,
        headers: {
            'X-CSRF-TOKEN': token,
        },
        data: formData,
        contentType: false,
        processData: false,
        success: function (response) {
            successCallback(response);
        },
        error: function (error) {
            errorCallback(error);
        }
    });
}

console.log('include');
