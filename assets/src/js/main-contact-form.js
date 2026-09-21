function showContactForm(type) {

	const container = document.getElementById("contact-brief-forms");

	if (!container) {
		return;
	}

	const formToggle = container.querySelector("#form-toggle")
	const contactForm = container.querySelector("#contact-form")
	const briefForm = container.querySelector("#brief-form")

	if (!formToggle || !briefForm || !contactForm) {
		return;
	}

	if(type == "contact") {
		contactForm.classList.remove("hidden")
		briefForm.classList.add("hidden")
	}

	if(type == "brief") {
		contactForm.classList.add("hidden")
		briefForm.classList.remove("hidden")
	}

}

// Enable required form on load if query params setted up
window.addEventListener("load", () => {

	const container = document.getElementById("contact-brief-forms");

	if (!container) {
		return;
	}

	const contactForm = container.querySelector("#contact-form")
	const briefForm = container.querySelector("#brief-form")

	if (!briefForm || !contactForm) {
		return;
	}
	
	let active = container.dataset.show;
	let manualOffset = document.querySelector("header").offsetHeight;
	let scrollDistance = 0;

	if(active && active == "brief") {

		contactForm.classList.add("hidden")
		briefForm.classList.remove("hidden")

		scrollDistance = getTopOffset(briefForm);

		// scroll to this section
		window.scroll({
			top: scrollDistance - manualOffset,
			behavior: "smooth"
		});
		
	}
	
});

// if #contact_form_confirmation exists, execute the following
function contactFormConfirmation() {
	const contactFormConfirmation = document.getElementById("contact_form_confirmation");
	const contactBlock = document.getElementById("contact_block");
	const formTitle = document.getElementById("form_title");

	if (!contactFormConfirmation || !contactBlock || !formTitle)
		return

	if (contactFormConfirmation) {
		contactBlock.classList.add("hidden");
		formTitle.classList.add("hidden");
	}
}