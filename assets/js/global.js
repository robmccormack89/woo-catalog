/* VAR NAMED FUNCTIONS

*/

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
	// Add the integer values of the left values together
	var new_height = parseInt( height, 10 ) + parseInt( "20px", 10 ) + "px";
	document.getElementById("PageHeaderWrap").style.paddingTop = new_height;
}

// dealing with mailchimp4wp's checkboxes for forms. this needs firing on document.ready downstream
mailchimp4WpStyles = function() {
	jQuery(function($) {
		$(".mc4wp-checkbox input").addClass("uk-checkbox uk-margin-small-right");
		$(".mc4wp-checkbox label").addClass("uk-margin-small-top uk-width-large");
	});
}

/* PAGINATION FUNCTIONS

*/

// ajax loads (fetch api) for elements with data-link attributes (like pagination)
function addQuickLoadToDataLinkAttrs(){
	// console.log('hi there!');
	document.querySelector('main').addEventListener('click', function(event) {
		if (event.target.hasAttribute('data-link')) {

			// prevent default submit event
			event.preventDefault();

			// 1. set var for 'data_link', the data-link attribute we need the value of (should be a valid url)
			var data_link = event.target.getAttribute("data-link");

			// document.getElementById('DemoProductsGrid').classList.remove("uk-hidden");
			// document.getElementById('ProductsGrid').textContent = '';

			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			})

			// await delay(1000);

			document.getElementById('DemoProductsGrid').classList.remove("uk-hidden");
			document.getElementById('ProductsGrid').textContent = '';

			fetch(data_link)
			.then((response) => {
				// document.getElementById('DemoProductsGrid').classList.remove("uk-hidden");
				// document.getElementById('ProductsGrid').textContent = '';
				return response.text();
			})
			.then(function(html) { // then we...
				// console.log('success!!!');
				var parser = new DOMParser();
				var newDocObj = parser.parseFromString(html, 'text/html');
				var newContent = newDocObj.querySelector('#TheLoop');
				document.getElementById('DemoProductsGrid').classList.add("uk-hidden");
				document.getElementById("TheLoopContainer").replaceChild(newContent, document.querySelector('#TheLoop'));
				wooGlobalStyles();
				reEnableDisabledCheckboxes('FiltersFormDropArea');
				// console.log('the content has been replaced');
			})
			.catch(error => {
				// console.error('Somethings gone wrong...', error);
			});

		};
	});
}

// just the inf scroll pagination init
function archivePagination() {
	// console.log('hello');
	let elem = document.querySelector('.archive-posts');
	let infScroll = new InfiniteScroll( elem, {
		// options
		path: '.next',
		append: '.item',
		history: false,
		// button: '.view-more-button', // load pages on button click
		// scrollThreshold: false, // disable loading on scroll
		status: '.page-load-status',
		hideNav: '.pagination'
	});
}

/* GENERAL HELPER FUNCTIONS

*/

function ifArrayContainsCheckedItem(theNodes){
	for (var i = 0; i < theNodes.length; i++) {
		if(theNodes[i].checked){
			return true;
		}
	}
	return false;
}

// helpers relating to the SHOP FILTERS
function disableParentWhenParentAndChildSelected(parent_group, child_group){

	//
	// THIS
	// IS
	// NEW
	//

	// console.log('youve just tried to do a filtering, but youve been stopped in your tracks...')

	var _parent_group = document.getElementById(parent_group); // Reference the Table.
	var chks_parent_group = _parent_group.getElementsByTagName("INPUT"); // Reference all the CheckBoxes in Table.

	var _child_group = document.getElementById(child_group); // Reference the Table.
	var chks_child_group = _child_group.getElementsByTagName("INPUT"); // Reference all the CheckBoxes in Table.

	var arr = [];
	for (var i = 0; i < chks_child_group.length; i++) {
		if(chks_child_group[i].checked){
			// console.log(chks_child_group[i].getAttribute('data-parent')) // should be perfict fit now
			arr.push(chks_child_group[i].getAttribute('data-parent'));
		}
	}

	// console.log(arr) // should be now an array just with perfect fit in it

	// need to now get second array of checked boxes's values in parent group
	// and look thru that, for any matches to the first array...
	// if any items in second arr match any of the items in first arr, those items in second arr should be disabled

	// loop thru for the second array (the parent group)
	var arrTwo = [];
	for (var i = 0; i < chks_parent_group.length; i++) {
		if(arr.includes(chks_parent_group[i].value) &&  chks_parent_group[i].checked){
			// console.log('its checked');
			chks_parent_group[i].disabled = true;
			// console.log('its disabled');
			// console.log(chks_parent_group[i]);
		}
		// if (chks_parent_group[i].value) {
		// 	checked_parent++;
		// }
	}

	//
	// THIS
	// IS
	// OLD
	//

	// find if product_cat_group has checked checkboxes (PARENT)
	// var checked_parent = 0;
	// var _parent_group = document.getElementById(parent_group); // Reference the Table.
	// var chks_parent_group = _parent_group.getElementsByTagName("INPUT"); // Reference all the CheckBoxes in Table.
	//
	// // Loop and count the number of checked CheckBoxes. (PARENT) checked_parent = integer
	// for (var i = 0; i < chks_parent_group.length; i++) {
	// 	if (chks_parent_group[i].checked) {
	// 		checked_parent++;
	// 	}
	// }
	//
	// // find if product_subcat_group has checked checkboxes (CHILD)
	// var checked_child = 0;
	// var _child_group = document.getElementById(child_group); // Reference the Table.
	// var chks_child_group = _child_group.getElementsByTagName("INPUT"); // Reference all the CheckBoxes in Table.
	//
	// // Loop and count the number of checked CheckBoxes. (CHILD) checked_child = integer
	// for (var i = 0; i < chks_child_group.length; i++) {
	// 	if (chks_child_group[i].checked) {
	// 		checked_child++;
	// 	}
	// }
	//
	// if(checked_parent > 0){
	// 	if(checked_child > 0){
	//
	// 		// Loop and push the checked CheckBox value in Array.
	// 		for (var i = 0; i < chks_parent_group.length; i++) {
	// 			if (chks_parent_group[i].checked) {
	// 				chks_parent_group[i].disabled = true;
	// 				// console.log(chks_parent_group[i]);
	// 			}
	// 		}
	//
	// 		// alert("Both " + parent_group + " and " + child_group + " have been selected");
	// 		// fuck();
	// 	}
	// 	// alert("Only " + parent_group + " have been selected");
	// 	// fuck();
	// } else {
	// 	// alert("Neither " + parent_group + " and " + child_group + " has been selected");
	// }

}
function reEnableDisabledCheckboxes(id){
	var group = document.getElementById(id); // Reference the Table.
	var chks = group.getElementsByTagName("INPUT"); // Reference all the CheckBoxes in Table.

	// Loop and push the checked CheckBox value in Array.
	for (var i = 0; i < chks.length; i++) {
		if (chks[i].disabled) {
			chks[i].disabled = false;
			// alert('reenabled');
		}
	}
}
function addClassToElements(ele, cls){
	var elementsToTarget = document.querySelectorAll(ele);
	for (var i = 0; i < elementsToTarget.length; i++) {
		if(!(elementsToTarget[i].classList.contains(cls))){
			elementsToTarget[i].classList.add(cls)
		}
	}
}
function removeClassFromElements(ele, cls){
	var elementsToTarget = document.querySelectorAll(ele);
	for (var i = 0; i < elementsToTarget.length; i++) {
		if(elementsToTarget[i].classList.contains(cls)){
			elementsToTarget[i].classList.remove(cls)
		}
	}
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
