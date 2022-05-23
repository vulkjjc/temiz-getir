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

const locationSelectAction = (locationSelectForm) => {
    return getFormRequestResponse(locationSelectForm)
        .then(async (response) => await parseRequestResult(response, "json"));
};

const createLocationSelectElement = (name, id) => {
    let locationSelectElement = document.createElement("select");

    locationSelectElement.className = "input border-gray color-heading placeholder-heading w-full";
    locationSelectElement.name = name;
    locationSelectElement.id = id;

    return locationSelectElement;
};

const addLocationSelectElementOptions = (locations, locationSelectElement, defaultOptionText) => {
    let option = document.createElement("option");

    option.text = defaultOptionText;
    option.selected = true;

    locationSelectElement.appendChild(option);

    for (let location of locations) {
        let option = document.createElement("option");

        option.value = location.id;
        option.text = location.name;
        option.required = true;

        locationSelectElement.appendChild(option);
    }
};

const addLocationSelectElement = (locations, locationSelectCont, attrs) => {
    locationSelectCont.innerHTML = "";

    let locationSelectElement = createLocationSelectElement(attrs.name, attrs.id);
    addLocationSelectElementOptions(locations, locationSelectElement, attrs.defaultOptionText);

    locationSelectCont.appendChild(locationSelectElement);
};
