// website preloader animation
preloadMe = function() {
	document.getElementById("Preloader").classList.add("hidden");
	document.getElementsByTagName("BODY")[0].classList.remove("no-overflow");
}

// when using an absolutely top-positioned site Header, we can use this to add margin to the top of the Page header content to compensate
headerSize = function() {
	var element = document.getElementById("SiteHeader");
	let computedStyle = getComputedStyle(element);
	var height = computedStyle.height;
	document.getElementById("PageHeaderWrap").style.marginTop = height;
}

// dealing with mailchimp4wp's checkboxes for forms. this needs firing on document.ready downstream
mailchimp4WpStyles = function() {
	jQuery(function($) {
		$(".mc4wp-checkbox input").addClass("uk-checkbox uk-margin-small-right");
		$(".mc4wp-checkbox label").addClass("uk-margin-small-top uk-width-large");
	});
}

// helper for checking for node changes within the given element/s & fire the given callback when changes occur
// https://developer.mozilla.org/en-US/docs/Web/API/MutationObserver
function whenNodesChange(selector, observer_callback){
	
	// Select the node that will be observed for mutations
	const targetNode = document.querySelector(selector);
	
	// Options for the observer (which mutations to observe)
	const config = { attributes: false, childList: true, subtree: true };
	
	// Callback function to execute when mutations are observed
	const callback = function(mutationList, observer) {
			// Use traditional 'for loops' for IE 11
			for(const mutation of mutationList) {
					if (mutation) {
							// console.log('Something has changed');
							observer_callback();
					}
					// if (mutation.type === 'childList') {
					//     console.log('A child node has been added or removed.');
					// }
					// else if (mutation.type === 'attributes') {
					//     console.log('The ' + mutation.attributeName + ' attribute was modified.');
					// }
			}
	};
	
	// Create an observer instance linked to the callback function
	const observer = new MutationObserver(callback);
	
	// Start observing the target node for configured mutations
	observer.observe(targetNode, config);
	
	// Later, you can stop observing
	// observer.disconnect();
	
}