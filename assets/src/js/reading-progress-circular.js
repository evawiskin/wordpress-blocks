
/**
*
* Initialises any reading progress bar on the page on document load.
*
**/

window.addEventListener("load", initReadingProgress)


/**
*
* Initialises reading Progress tracker. It fills and unfills an svg circle
* Based on your scroll position on the page.
*
**/

function initReadingProgress(){

	// Get the id of the progress circle and length
	const progressCircle = document.getElementById("reading-progress-circle")

	if(!progressCircle)
		return

	const circleLength = progressCircle.getTotalLength()

	// The start position of the drawing
	progressCircle.style.strokeDasharray = circleLength

	// Hide the circle by offsetting dash. Remove this line to show the circle before scroll draw
	progressCircle.style.strokeDashoffset = circleLength

	// remove hiding the circle now the dash offset is in place
	// has to be opacity because display hidden makes some svg funcs not work
	progressCircle.classList.remove("opacity-0")

	// Find scroll percentage on scroll (using cross-browser properties), and offset dash same amount as percentage scrolled
	window.addEventListener("scroll", () => {

		let scrollpercent = (document.body.scrollTop + document.documentElement.scrollTop) / (document.documentElement.scrollHeight - document.documentElement.clientHeight)

		let drawProgress = circleLength * scrollpercent

		// Reverse the drawing (when scrolling upwards)
		progressCircle.style.strokeDashoffset = circleLength - drawProgress

	}, progressCircle, circleLength)
}
