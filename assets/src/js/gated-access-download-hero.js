/*
* Verifies a gated access token from the URL query string and reveals the
* download button if valid, or shows the locked message if not.
*
* The token is read from ?access=TOKEN then immediately stripped from the URL
* via history.replaceState so it cannot be bookmarked or shared. Verification
* is sent as a POST to keep the token out of AJAX server logs. The
* Referrer-Policy: no-referrer header set server-side prevents leakage via
* the Referer header to any third-party assets on the page.
*/

function verifyGatedAccess(hero) {

	const locked   = hero.querySelector(".gated-download-hero__locked")
	const download = hero.querySelector(".gated-download-hero__download")

	const params = new URLSearchParams(window.location.search)
	const token  = params.get("access")

	if (!token) {
		if (locked) locked.removeAttribute("hidden")
		return
	}

	// Strip the token from the URL immediately — before the AJAX call —
	// so it cannot be bookmarked, shared, or logged via Referer.
	params.delete("access")
	const cleanSearch = params.toString() ? "?" + params.toString() : ""
	history.replaceState(null, "", window.location.pathname + cleanSearch + window.location.hash)

	const pageId  = hero.dataset.pageId
	const ajaxUrl = (typeof gatedAccessData !== "undefined") ? gatedAccessData.ajaxUrl : "/wp-admin/admin-ajax.php"

	fetch(ajaxUrl + "?action=hy_verify_gated_access", {
		method: "POST",
		headers: { "Content-Type": "application/x-www-form-urlencoded" },
		body: "token=" + encodeURIComponent(token) + "&page_id=" + encodeURIComponent(pageId)
	})
		.then(function(r) { return r.json() })
		.then(function(data) {
			if (data.success && data.data.valid) {
				const btn = hero.querySelector(".gated-download-hero__btn")
				if (btn) btn.href = data.data.download_url
				if (download) download.removeAttribute("hidden")
			} else {
				if (locked) locked.removeAttribute("hidden")
			}
		})
		.catch(function() {
			if (locked) locked.removeAttribute("hidden")
		})
}

function setupGatedAccessDownloadHero() {
	document.querySelectorAll(".gated-download-hero").forEach(verifyGatedAccess)
}

document.addEventListener("DOMContentLoaded", () => setupGatedAccessDownloadHero())
