

// Dynamic Accordion title, works ONLY for repeater / accordions
window.addEventListener("load", (event) => {

    // If we want to show field value in repeater's accordion tab, we need call function like on this example for every single repeater
    // Function has 4 arguments:
    // 1. Repeater name
    // 2. Accordion field where new label supposed to be shown
    // 3. Field where we're getting value to show
    // 4. Type of ACF field tag which was in #3 (where we get data) - currently setted up for "input" and "select"
    
    //dynamic_title_repeater_accordion("repeater_field_with_accordion", "repeater_subfield_accordion", "random_field_we_are_getting_data_from", "select");

});

// Executable function for Accordion title
function dynamic_title_repeater_accordion(repeater_name, accordion_field, field_value, field_type) {

    // Look for container
    let container = document.querySelector("div[data-name='" + repeater_name + "']");

    // If we have one - start work
    if (container) {

        // Looks for all tabs for our repeater
        let tabs = container.querySelectorAll("div[data-name='" + accordion_field + "']");

        // add event Listener
        for (let i = 0; i < tabs.length; i++) {

            // Find label for futher manipulations
            let label = tabs[i].querySelector(".acf-accordion-title label");

            let input = false;
            let value = false;

            // Trying to find needed field value if field type is <input>
            if(field_type == "input") {
                input = tabs[i].querySelector("div[data-name='" + field_value + "'] input");
                if(input) {
                    value = input.value;
                }
            }
            // Trying to find needed field value if field type is <select> and take it's labels by current value
            else if(field_type == "select") {
                input = tabs[i].querySelector("div[data-name='" + field_value + "'] select");
                if(input) {
                    let options = input.querySelectorAll("option");
                    for (let n = 0; n < options.length; n++) {
                        if(options[n].value == input.value) {
                            value = options[n].innerText;
                        }
                    }
                }
            }

            // If we recieve any values before this, we can add our new text to old label text :)
            if(value) {
                currentlabel = label.innerText;
                newlabel = label.innerText + " - " + value;
                label.innerText = newlabel;
            }

        }

    }
}