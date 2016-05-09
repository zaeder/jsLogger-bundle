var errorLogger = function (url, level) {
    var oldErrorHandler = window.onerror;

    window.onerror = function(errorMsg, file, line) {
        var key,
            e = encodeURIComponent,
            customContext = window.zaeder_js_logger_custom_context,
            customContextStr = '';

        if (oldErrorHandler) {
            oldErrorHandler(errorMsg, file, line);
        }

        if ('object' === typeof customContext) {
            for (key in customContext) {
                customContextStr += '&context[' + e(key) + ']=' + e(customContext[key]);
            }
        }

        (new Image()).src = url + '?msg=' + e(errorMsg) +
            '&level=' + level +
            '&context[file]=' + e(file) +
            '&context[line]=' + e(line) +
            '&context[browser]=' + e(navigator.userAgent) +
            '&context[page]=' + e(document.location.href) + customContextStr;
    };
};

var log = function(url, level, message, contextData) {
    var key,
        context = '',
        customContext = window.zaeder_js_logger_custom_context,
        e = encodeURIComponent;

    if (contextData) {
        for (key in contextData) {
            context += '&context[' + e(key) + ']=' + e(contextData[key]);
        }
    }
    if ('object' === typeof customContext) {
        for (key in customContext) {
            context += '&context[' + e(key) + ']=' + e(customContext[key]);
        }
    }
    (new Image()).src = url + '?msg=' + e(message) + '&level=' + e(level) + context;
};