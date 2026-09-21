/*
* Determines whether to open or close an accordion, 
* based on the current state of the accordion.
* and what to do with other accordions based on a data-attr.
*
* @param {event} Click event on a given accordion toggle
* @return Side Effect - Accordion is opened or closed, other accordions on page may be closed
*/


function accordionHandler(event) {

	if (!event)
		return

	/*
		Setup variables
	*/
	const accordionToggle = event.currentTarget
	const accordionGroup = event.currentTarget.closest(".accordion-group")
	const accordionBody = accordionGroup.querySelector(".accordion-body")
	const accordionsWrapper = accordionGroup.parentElement

	/* 
		Check for data attribute on parent above
		If it is true then multiple accordions can be open at once
		else any accordion should be shut before another is opened.
	*/
	if (accordionsWrapper.dataset.multipleOpen !== "true") {

		for (let y = 0; y < accordionsWrapper.children.length; y++) {
			const childToggle = accordionsWrapper.children[y].querySelector(".accordion-toggle")
			
			if(accordionToggle != childToggle)
				toggleAccordion(childToggle, "close")
		}
	}

	// Call Close or Open based on current status derived from class.
	if(accordionBody.classList.contains("open")){
		toggleAccordion(accordionToggle, "close")
	} else {
		toggleAccordion(accordionToggle, "open")
	}
}

/*
* Toggles a given accordion open or closed.
* @param {DOM Element} Accordion Toggle Element
* @param {string} Type of toggle, only 'open' or 'close'
* @return Side Effect - Accordion is opened or closed.
*/

function toggleAccordion(accordionToggle, toggleType) {

	if (!accordionToggle || !toggleType)
		return

	//get elements that are needed for the accordion
	const accordionGroup = accordionToggle.closest(".accordion-group")
	const accordionBody = accordionGroup.querySelector(".accordion-body")
	const icon = accordionToggle.querySelector(".accordion-icon")
		
	//toggle the accordion. any styling modifications that need to be done can be done here
	if(toggleType == "open") {

		//open accordion body and update toggle styles
		accordionToggle.classList.add("bg-teal-500")
		accordionToggle.classList.remove("hover:bg-teal-300")
		accordionBody.classList.add("open")
		accordionBody.style.maxHeight = accordionBody.scrollHeight + "px"

		//if we have a icon, do something to it (rotate it)
		if(icon)
			icon.classList.add("rotate-180")

		//update aria role
		accordionToggle.setAttribute("aria-expanded", "true")

	} else {
		//close accordion group. undoes the above.

		accordionToggle.classList.remove("bg-teal-500")
		accordionToggle.classList.add("hover:bg-teal-300")
		accordionBody.classList.remove("open")
		accordionBody.style.maxHeight = 0

		if(icon)
			icon.classList.remove("rotate-180")

			accordionToggle.setAttribute("aria-expanded", "false")
	}
}

/*
* Initialize the accordions on a page
*/

function setupAccordions() {
	const accordions = document.getElementsByClassName("accordion-toggle")

	for (let x = 0; x < accordions.length; x++) {
		accordions[x].addEventListener("click", accordionHandler)
	}
}
window.addEventListener("load", () => setupAccordions())
