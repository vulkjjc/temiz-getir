const userEditInfoForm = document.getElementById("user_edit_info_form");
const userEditInfoError = document.getElementById("user_edit_info_error");
if (userEditInfoForm && userEditInfoError)
    userEditInfoForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        await getFormRequestResponse(userEditInfoForm, {"method": "PATCH", "format": "json"})
            .then(async (response) => {
                let error = await getRequestErrorOrRedirect(response);
                if (error)
                    userEditInfoError.innerText = error;
            });
    });

const userEditPasswordForm = document.getElementById("user_edit_password_form");
const userEditPasswordError = document.getElementById("user_edit_password_error");
if (userEditPasswordForm && userEditInfoError)
    userEditPasswordForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        await getFormRequestResponse(userEditPasswordForm, {"method": "PATCH", "format": "json"})
            .then(async (response) => {
                let error = await getRequestErrorOrRedirect(response);
                if (error)
                    userEditPasswordError.innerHTML = error;
            });
    });

const userDeleteForm = document.getElementById("user_delete_form");
const userDeleteError = document.getElementById("user_delete_error");
if (userDeleteForm && userDeleteError)
    userDeleteForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        await getFormRequestResponse(userDeleteForm, {"method": "DELETE", "format": "json"})
            .then(async (response) => {
                let error = await getRequestErrorOrRedirect(response);
                if (error)
                    userDeleteError.innerHTML = error;
            });
    });

const customerSignupForm = document.getElementById("customer_signup_form");
const customerSignupError = document.getElementById("customer_signup_error");
if (customerSignupForm && customerSignupError)
    customerSignupForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        await getFormRequestResponse(customerSignupForm, {"method": "POST"})
            .then(async (response) => {
                let error = await getRequestErrorOrRedirect(response);
                if (error)
                    customerSignupError.innerHTML = error;
            });
    });

const providerSignupRequestForm = document.getElementById("provider_signup_request_form");
const providerSignupRequestError = document.getElementById("provider_signup_request_error");
if (providerSignupRequestForm && providerSignupRequestError)
    providerSignupRequestForm.addEventListener("submit", async (event) => {
        event.preventDefault();

        await getFormRequestResponse(providerSignupRequestForm, {"method": "POST"})
            .then(async (response) => {
                let error = await getRequestErrorOrRedirect(response);
                if (error)
                    providerSignupRequestError.innerHTML = error;
            });
    });

const countrySelect = document.getElementById("country-select");
const countrySelectForm = document.getElementById("country-select-form");
const citySelectCont = document.getElementById("city-select-cont");
const provinceSelectCont = document.getElementById("province-select-cont");
const citySelectForm = document.getElementById("city-select-form");

if (countrySelect && countrySelectForm && citySelectCont && citySelectForm && provinceSelectCont)
    countrySelect.addEventListener("change", async (event) => {
        countrySelectForm.action = "/cities/get/" + event.target.value;
        let cities = await locationSelectAction(countrySelectForm);

        provinceSelectCont.innerHTML = "";

        addLocationSelectElement(
            cities,
            citySelectCont,
            {
                "name": "city-id",
                "id": "city-select",
                "defaultOptionText": "(Şehir)"
            }
        );

        let citySelect = document.getElementById("city-select");

        if (citySelect)
            citySelect.addEventListener("change", async (event) => {
                citySelectForm.action = "/provinces/get/" + event.target.value;
                let provinces = await locationSelectAction(citySelectForm);

                addLocationSelectElement(
                    provinces,
                    provinceSelectCont,
                    {
                        "name": "province-id",
                        "id": "province-select",
                        "defaultOptionText": "(İlçe)"
                    }
                );
            });
    });
