//initialises all countup objects on a page
document.addEventListener("DOMContentLoaded", function(){

	const options = {
		duration: 2.5,
		useEasing: true,
		enableScrollSpy: true,
		scrollSpyOnce: true
	}

	const countUpTargets = document.querySelectorAll(".countup-item");

	if (!countUpTargets)
		return;

	countUpTargets.forEach((countUpTarget) => {
		const value = countUpTarget.textContent;
		if (isNaN(value))
			return;

		const countupInstance = new countUp.CountUp(countUpTarget, countUpTarget.textContent, options);
		
		if (!countupInstance.error) {
			countupInstance.handleScroll();
		} else {
			console.error(countupInstance.error);
		}
	})
})