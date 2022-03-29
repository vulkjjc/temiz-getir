const getFormRequestResponse = async (form, options={"method": "POST"}) => {
    let formData = new FormData(form);

    if (options.format == "json") {
        formData = JSON.stringify(Object.fromEntries(formData));
    }

    options = Object.assign(options, {"body": formData});
    let response = await fetch(form.action, options);

    return response;
};

const parseRequestResult = async (response, format="text") => {
    let result = "";
    if (format == "text") result = await response.text();
    else if (format == "json") result = await response.json();

    return result;
};

const getRequestError = async (response, format="text") => {
    if (!response.ok) return parseRequestResult(response, format);
};

const getRequestErrorOrRedirect = async (response, format="text") => {
    if (!response.redirected) return await getRequestError(response, format);

    window.location.href = response.url;
};
