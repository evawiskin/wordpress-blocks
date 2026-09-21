/**
 * Initialises any reading progress bars on the page on document load.
 */
window.addEventListener("load", initReadingProgress);


/**
 * Calculates the reading progress of the user and updates the progress bar(s).
 * @param {HTMLElement[]} progressBars - An array of progress bar elements.
 */
function calculateReadingProgress(progressBars) {
	const readingProgressContainer = document.getElementById("reading-progress-container");

	document.addEventListener("scroll", function() {
		let scrollPosition = window.scrollY + window.innerHeight;

		let progress = ((scrollPosition - readingProgressContainer.offsetTop) / readingProgressContainer.offsetHeight) * 100;

		progress = Math.min(Math.max(progress, 0), 100);

		progressBars.forEach(bar => {
			bar.style.setProperty("width", progress + "%");
		});
	});
}


/**
 * Initialises the reading progress bars for both desktop and mobile.
 */
function initReadingProgress() {
	const progressBars = [
		document.querySelector("#reading-progress-fill"),
		document.querySelector("#reading-progress-fill-mobile")
	].filter(Boolean);

	if (progressBars.length > 0) {
		calculateReadingProgress(progressBars);
	}
}
