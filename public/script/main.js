let countrySelect = document.getElementById("country-select");
let countrySelectForm = document.getElementById("country-select-form");
let citySelectCont = document.getElementById("city-select-cont");
let provinceSelectCont = document.getElementById("province-select-cont");
let citySelectForm = document.getElementById("city-select-form");

if (countrySelect && countrySelectForm && citySelectCont && citySelectForm && provinceSelectCont)
    countrySelect.addEventListener("change", async (event) => {
        countrySelectForm.action = "/cities/get/" + event.target.value;
        let cities = await locationSelectAction(countrySelectForm);

        provinceSelectCont.innerHTML = "";

        addLocationSelectElement(
            cities,
            citySelectCont,
            {
                "name": "city",
                "id": "city-select",
                "defaultOptionText": "(Cities)"
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
                        "name": "province",
                        "id": "province-select",
                        "defaultOptionText": "(Provinces)"
                    }
                );
            });
    });
