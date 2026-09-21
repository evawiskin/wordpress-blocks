document.addEventListener("DOMContentLoaded", function() {
    // Access the SVG mapping passed from PHP
    const svgMapping = svgPreviewData.svgMapping;

    function getSVGPath(name) {
        if (name && svgMapping[name]) {
            return svgMapping[name];
        } else {
            console.warn(`SVG file not found for name: ${name}`);
            return null;
        }
    }

    // Function to update SVG preview
    function updateSVGPreview(selectField) {
        // Get the value of the selected option and find the svg in imgs folder
        let selectedOption = selectField.options[selectField.selectedIndex].value;

        const svgPath = getSVGPath(selectedOption);

        if (svgPath) {
            // Create a new image element
            const img = document.createElement("img");

            img.src = svgPath;
            img.classList.add("acf-svg-preview-img");
            img.style.width = "2rem";
            img.style.height = "2rem";
            img.style.margin = "1rem 0 0 0.25rem";

            // Remove the previous image element if it exists
            const prevImg = selectField.parentElement.querySelector(".acf-svg-preview-img");
            
            if (prevImg) {
                prevImg.remove();
            }

            // Append the new image element
            selectField.parentElement.appendChild(img);
        }
    }

    function initializeSelectField(selectField) {
        const fieldWrapper = selectField.parentElement;

        fieldWrapper.classList.add("acf-svg-preview-wrapper");

        updateSVGPreview(selectField);

        // Create an event listener to update preview every time the select field changes
        selectField.addEventListener("change", function() {
            updateSVGPreview(selectField);
        });
    }

    function initializeRepeater(repeater) {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                mutation.addedNodes.forEach((node) => {
                    if (node.classList && node.classList.contains("acf-row")) {
                        // Get the select field in the new row
                        let selectField = node.querySelector(".acf-svg-preview select");

                        if (selectField) {
                            initializeSelectField(selectField);
                        }
                    }
                });
            });
        });

        observer.observe(repeater, {
            childList: true,
            subtree: true
        });
    }

    // Observe the entire document for dynamically added repeaters
    const globalObserver = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.classList && node.classList.contains("acf-repeater")) {
                    initializeRepeater(node);

                    // Initialize any select fields already present in the repeater
                    const selectFields = node.querySelectorAll(".acf-svg-preview select");
                    selectFields.forEach((selectField) => {
                        initializeSelectField(selectField);
                    });
                }

                // Handle singular acf-field-select fields
                if (node.classList && node.classList.contains("acf-field-select")) {
                    const selectField = node.querySelector("select");
                    if (selectField) {
                        initializeSelectField(selectField);
                    }
                }

                // Handle mutations for acf-block-fields
                if (node.classList && node.classList.contains("acf-block-fields")) {
                    const repeaters = node.querySelectorAll(".acf-repeater");
                    repeaters.forEach((repeater) => {
                        initializeRepeater(repeater);

                        const selectFields = repeater.querySelectorAll(".acf-svg-preview select");
                        selectFields.forEach((selectField) => {
                            initializeSelectField(selectField);
                        });
                    });

                    const selectFields = node.querySelectorAll(".acf-field-select.acf-svg-preview select");
                    selectFields.forEach((selectField) => {
                        initializeSelectField(selectField);
                    });
                }
            });
        });
    });

    globalObserver.observe(document.body, {
        childList: true,
        subtree: true
    });

    // Initialize existing repeaters and select fields on page load
    let acfRepeaters = document.querySelectorAll(".acf-repeater");
    acfRepeaters.forEach((repeater) => {
        initializeRepeater(repeater);

        const selectFields = repeater.querySelectorAll(".acf-svg-preview select");
        selectFields.forEach((selectField) => {
            initializeSelectField(selectField);
        });
    });

    // Initialize singular acf-field-select fields on page load
    let acfSelectFields = document.querySelectorAll(".acf-field-select.acf-svg-preview");
    acfSelectFields.forEach((selectField) => {
        initializeSelectField(selectField);
    });
});