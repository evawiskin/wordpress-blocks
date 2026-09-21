document.addEventListener("DOMContentLoaded", () => {

	let tabBlocks = document.getElementsByClassName("tabs-wrapper");

    let layout;
    
    for (let i = 0; i < tabBlocks.length; i++) {

        layout = tabBlocks[i].dataset.layout;

        if( layout == "hide") {
            tabsInitHidden(tabBlocks[i].id);
        }

        if( layout == "show") {
            tabsInitVisible(tabBlocks[i].id);
        }
        
    }
});

function tabsInitHidden(id) {

    // Define variables
    let wrapper = document.getElementById(id);
    let tabs = wrapper.getElementsByClassName("tab-body");
    let triggers = wrapper.getElementsByClassName("tab-trigger");

    // Hide all tabs
    for (let i=0; i < tabs.length; i++) {
        tabs[i].classList.add("hidden");
    }

    // Make first tab active
    tabs[0].classList.remove("hidden");
    tabs[0].classList.add("block", "active");

    // Make first trigger active
    triggers[0].classList.remove("border-transparent");
    triggers[0].classList.add("border-gray-500", "active");

    // Listen for clicks
    wrapper.addEventListener("click", (event) => {

        // Remove default scroll
        event.preventDefault();

        // Define click target
        let target = event.target;

        // Check if we click on trigger and if our trigger has data-for attribute
        if ( target.classList.contains("tab-trigger") && target.dataset.for !== "" ) {


            // Control Triggers behavior
            for (let i=0; i < triggers.length; i++) {
                triggers[i].classList.remove("border-gray-500", "active");
                triggers[i].classList.add("border-transparent");
            }

            // Control Tabs behavior
            for (let i=0; i < tabs.length; i++) {

                // Make all tabs hidden
                tabs[i].classList.remove("block", "active");
                tabs[i].classList.add("hidden");

                // If we have match between trigger and tab, start work
                if( target.dataset.for === tabs[i].id  ) {

                    // Make trigger active
                    target.classList.add("border-gray-500", "active");
                    target.classList.remove("border-transparent");

                    // Make tab active
                    tabs[i].classList.remove("hidden");
                    tabs[i].classList.add("block", "active");

                }

            }

         }

    });

}



function tabsInitVisible(id) {

    // Define variables
    let wrapper = document.getElementById(id);
    let tabs = wrapper.getElementsByClassName("tab-body");
    let triggers = wrapper.getElementsByClassName("tab-trigger");

    // Make first trigger active
    triggers[0].classList.add("!border-gray-500");

    // Listen for scroll
    window.addEventListener("scroll", (event) => {

        let current;

        if(window.scrollY < getTopOffset(tabs[0]) ) {
            triggers[0].classList.add("!border-gray-500");
        } else {

            for (let i = 0; i < tabs.length; i++) {
                const section_offset = getTopOffset(tabs[i]);
                // We count here height of header to reduce offset and +10px to be secure (to not be on the edge between sections)
                const manualOffset = document.querySelector("header").offsetHeight + triggers[0].parentElement.offsetHeight;
                if (scrollY >= section_offset - manualOffset - 10) {
                    current = tabs[i].getAttribute("id");
                }
            }
    
            for (let i = 0; i < triggers.length; i++) {
    
                triggers[i].classList.remove("!border-gray-500");
    
                if (triggers[i].dataset.for === current) {
                    triggers[i].classList.add("!border-gray-500");
                }
            }
        }
    });

}



// Tabs scroll on click
window.addEventListener("load", () => {

	const tabs = document.querySelectorAll(".tabs-wrapper.show");

    if(!tabs) {
        return;
    }

    for (let i = 0; i < tabs.length; i++) {

		const sections = tabs[i].querySelectorAll(".tab-body");
		const links = tabs[i].querySelectorAll(".tab-trigger");

		for (let n = 0; n < links.length; n++) {
			links[n].addEventListener("click",(event) => {

				event.preventDefault();

				section_id = links[n].dataset.for;
				
				section = document.getElementById(section_id);

                top_offset = getTopOffset(section);

				// We need it because we have sticky header and browsers can't make this offset on their own
                // + Add manual sticky offset to threshold
				manualOffset = document.querySelector("header").offsetHeight + links[n].parentElement.offsetHeight;
				
				window.scroll({
					top: top_offset - manualOffset,
					behavior: "smooth"
				});

			});
		}

	}
});