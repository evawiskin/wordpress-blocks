document.addEventListener("DOMContentLoaded", () => {
	document.querySelectorAll(".resource-download-modal").forEach((modal) => {
		if (typeof addModalDismiss === "function") {
			addModalDismiss(modal)
		}
	})
})

function triggerResourceModal(modalId) {
	if (typeof triggerModal === "function") {
		triggerModal("#" + modalId)
	}
}
