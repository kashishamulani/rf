document.addEventListener("DOMContentLoaded", function () {

    const stateDropdown =
        document.getElementById(window.stateDropdownId || "filterState");

    const districtDropdown =
        document.getElementById(window.cityDropdownId || "filterDistrict");

    if (!stateDropdown || !districtDropdown) {
        return;
    }

    const selectedState = window.selectedState || "";
    const selectedDistrict = window.selectedDistrict || "";

    // Load states
    fetch("/states")
        .then(res => {

            if (!res.ok) {
                throw new Error("Failed to fetch states");
            }

            return res.json();
        })
        .then(states => {

            stateDropdown.innerHTML =
                '<option value="">Select State</option>';

            let selectedStateId = null;

            states.forEach(state => {

                const option = document.createElement("option");

                option.value = state.name;
                option.textContent = state.name;
                option.dataset.id = state.id;
                if (String(selectedState) === String(state.name)) {
                    option.selected = true;
                    selectedStateId = state.id;
                }

                stateDropdown.appendChild(option);
            });

            if (selectedStateId) {
                loadDistricts(selectedStateId, selectedDistrict);
            }
        })
        .catch(error => {

            console.error("State Error:", error);

            stateDropdown.innerHTML =
                '<option value="">Failed to load states</option>';
        });

    // Load districts
    function loadDistricts(stateId, selectedDistrictValue = "") {

        if (!stateId) {

            districtDropdown.innerHTML =
                '<option value="">Select District</option>';

            return;
        }

        districtDropdown.innerHTML =
            '<option value="">Loading...</option>';

        districtDropdown.disabled = true;

        fetch(`/districts/${stateId}`)
    .then(res => {

        if (!res.ok) {
            throw new Error("Failed to fetch districts");
        }

        return res.json();
    })
    .then(response => {

        console.log("District Response:", response);

        const districts =
            response.data ||
            response.districts ||
            response;

        districtDropdown.innerHTML =
            '<option value="">Select District</option>';

        districtDropdown.disabled = false;

        districts.forEach(district => {

            const option = document.createElement("option");

            option.value = district.name;
            option.textContent = district.name;

            if (
                String(selectedDistrictValue) ===
                String(district.name)
            ) {
                option.selected = true;
            }

            districtDropdown.appendChild(option);
        });
    })
}

    stateDropdown.addEventListener("change", function () {

    const stateId =
        this.options[this.selectedIndex].dataset.id;

    loadDistricts(stateId);
});
});