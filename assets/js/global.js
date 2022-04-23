window.PreloadMe = function() {
	document.getElementById("Preloader").classList.add("hidden");
	document.getElementsByTagName("BODY")[0].classList.remove("no-overflow");
}

window.HeaderSize = function() {
	var element = document.getElementById("SiteHeader");
	let computedStyle = getComputedStyle(element);
	var height = computedStyle.height;
	document.getElementById("PageHeaderWrap").style.marginTop = height;
}