/* SPECIFIC FUNCTIONS

*/

// FILTERS FORM SUBMISSION USING FETCH API - FINAL
// add an event listener to submission of the form, interupt it, take the form data, do some stuff to it...
// then use that new stuff to do a fetch api call & get the resuling new products html, and replace the old products...
// this code needs to differenciate for each field type we want to use in the form submission (the filter fields wont work by default)
// this code also needs to know which parent - child groupings there will be, in advance
function filtersFormSubmitCustom(event, form){

	// prevent default submit event
	// event.preventDefault();

	// disable any checked parent checkboxes when subs are also selected.
	// must do this for each parent -> sub grouping. e.g: categories/sub-categories & series/model
	disableParentWhenParentAndChildSelected('product_cat_group', 'product_subcat_group');
	disableParentWhenParentAndChildSelected('product_range_group', 'product_subrange_group');

	// console.log('hello');

	// die();

	// process form data into URLSearchParams
	const formData = new FormData(form);
	const formParams = new URLSearchParams(formData);

	// loop thru the formParams with key -> value if necessary
	// for (const [key, value] of formParams) {
	//   console.log(key + ' - ' + value);
	// }

	// instead, we use set on formsParams data for comma separated values. see URLSearchParams.set()
	// we check they exist first.. dont want to add a param to the url when it has no selections
	// do this for every field grouping
	if(formParams.has('product_cat')) formParams.set('product_cat', formParams.getAll('product_cat'));
	if(formParams.has('pa_range')) formParams.set('pa_range', formParams.getAll('pa_range'));
	// if(formParams.has('product_series')) formParams.set('product_series', formParams.getAll('product_series'));

	// the encoded/decoded uri & query strings
	const encodedParams = formParams.toString();
	const decodedParams = decodeURIComponent(encodedParams);
	const queryString = form.action + '?' + decodedParams;
	// console.log('query string: ' + queryString);

	// the loaders: start
	document.getElementById('DemoProductsGrid').classList.remove("uk-hidden");
	document.getElementById('ProductsGrid').textContent = '';
	// document.getElementById('ProductsGrid').classList.remove();

	// the fetch request.
	// we need to grab only a certain HTML element from the returned request...
	// and then replace the equivalent HTML element on the current page with that...
	// whilst adding in the loading animation effect in between the transitions...
	// we will also want to replace the url in the browser when we're done...
	fetch(queryString)
	.then(function(response) {
		// window.history.pushState({}, '', queryString);
		return response.text();
	})
	.then(function(html) {
		// console.log('success!!!');
		var parser = new DOMParser();
		var newDocObj = parser.parseFromString(html, 'text/html');
		var newContent = newDocObj.querySelector('#TheLoop');
		document.getElementById('DemoProductsGrid').classList.add("uk-hidden");
		document.getElementById("TheLoopContainer").replaceChild(newContent, document.querySelector('#TheLoop'));
		wooGlobalStyles();
		reEnableDisabledCheckboxes('FiltersFormDropArea');
		var themePagination = document.getElementById("themePagination");
		if(themePagination){
			shopPagination();
		}
		// console.log('the content has been replaced');
	})
	.catch(error => {
		// console.error('Somethings gone wrong...', error);
	});

}

// SHOP FILTERS FUNCTION
// FILTERS CHILD DROPDOWNS USING FETCH API
// this function will get the relevant child dropdowns based on the parent selections & place the resulting html into the correct places on the filters header (the dropdown selectors)
// the html needs to obvs be correct & follow the initial pattern as done for the categroy -> sub category setup
// this function defaults with values for the categroy -> sub category setup; calling the function for other tax types etc will require new values for args
function getSubDropsFromParentSelection(event, type_vars, location = '/wp-json/get_subcats', group_id = 'product_cat_group', sub_group_id = 'product_subcat_group', sub_id = 'product_cat_sub'){

	var route = document.location.origin + location

	// Create an Array.
	var selected = new Array();
	var id = new Array();

	// Reference the Table.
	var groupTable = document.getElementById(group_id);

	// Reference all the CheckBoxes in Table.
	var chks = groupTable.getElementsByTagName("input");
	// var chks = groupTable.getElementsByClassName("has-children"); // make sure its a checkbox that has children

	// Loop and push the checked CheckBox value in Array.
	for (var i = 0; i < chks.length; i++) {
		if (chks[i].checked) {
			selected.push(chks[i].value);
			id.push(chks[i].id);
		}
	}

	// console.log(selected); // this should be an array!

	if (selected.length > 0 && Array.isArray(selected)) {

		document.getElementById(sub_id).classList.add("uk-hidden"); // hide the sub if not already hidden
		document.getElementById('loader_' + sub_id).classList.remove("uk-hidden"); // unhide the loader, will be hidden after success or failure below!!!

		const parent_data = {
			slug: selected,
			id: id,
			type_vars: type_vars
		};

		fetch(route, {
			method: "POST",
			credentials: 'same-origin',
			headers: {
				"Content-Type": "application/json"
			},
			body: JSON.stringify(parent_data),
		})

		.then((response) => response.json())

		.then((data) => {
			if (data) {
				// console.log('Success, sub-terms have been found');
				document.getElementById(sub_group_id).innerHTML = data;
				document.getElementById('loader_' + sub_id).classList.add("uk-hidden");
				document.getElementById(sub_id).classList.remove("uk-hidden");
				// sel2.disabled = false;
				// sel2.parentElement.hidden = false; // we should reenable sel2 only when we have valid subs, on successful fetch()
				const jingo = new Event('jingo');
				document.getElementById(sub_id).dispatchEvent(jingo);
			}
		})

		.catch((error) => {
			// console.log('No sub-terms found for your query');
			document.getElementById('loader_' + sub_id).classList.add("uk-hidden");
			document.getElementById(sub_id).classList.add("uk-hidden");
		});

	}

}

// FILTERS BUTTONS TO DROP AREAS - TOGGLE FUNCTIONALITY
// this function makes the filter buttons (dropdowns) toggle'able with the filter drop areas
function toggleDropsAndAreas(btn_sel = '.filter-btn', drop_target = 'drop_target', hide_cls = 'theme-hidden', area_sel = '.filter-area'){

	const filterButtons = document.querySelectorAll(btn_sel);

	// loop thru filter buttons to add custom js UIkit.toggle's to them
	for (var i = 0; i < filterButtons.length; i++) {
		(function () {

			var id = filterButtons[i].id;
			var target = filterButtons[i].getAttribute(drop_target);

			// add UIkit.toggle to the filterButton item
			UIkit.toggle('#' + id, {
				target: target,
				cls: hide_cls
			});

			// add events listners related to the UIkit.toggle
			document.querySelector(target).addEventListener('beforeshow', function(event) {
				// function which loops thru the filter drop areas & hides them where they are not hidden already.
				// this should be fired prior to the toggling on/off of a particular drop area
				addClassToElements(area_sel, hide_cls)
				// console.log('beforeshow');
			})
			document.querySelector(target).addEventListener('beforehide', function(event) {
				// function which loops thru the filter drop areas & hides them where they are not hidden already.
				// this should be fired prior to the toggling on/off of a particular drop area
				addClassToElements(area_sel, hide_cls)
				// console.log('beforehide: ' + event.target.id);
			})
			document.querySelector(target).addEventListener('click', function(event) {
				// console.log('click');
			})
			document.querySelector(target).addEventListener('shown', function(event) {
				// console.log('shown');
			})
			document.querySelector(target).addEventListener('hidden', function(event) {
				// console.log('hidden');
			});

		}()); // immediate invocation
	}

}

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
function shopPagination() {
	// console.log('hello');
	let elem = document.querySelector('.archive-posts');
	let infScroll = new InfiniteScroll( elem, {
		// options
		path: '.next',
		append: '.product-item',
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

	console.log('youve just tried to do a filtering, but youve been stopped in your tracks...')

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

	console.log(arr) // should be now an array just with perfect fit in it

	// need to now get second array of checked boxes's values in parent group
	// and look thru that, for any matches to the first array...
	// if any items in second arr match any of the items in first arr, those items in second arr should be disabled

	// loop thru for the second array (the parent group)
	var arrTwo = [];
	for (var i = 0; i < chks_parent_group.length; i++) {
		if(arr.includes(chks_parent_group[i].value) &&  chks_parent_group[i].checked){
			console.log('its checked');
			chks_parent_group[i].disabled = true;
			console.log('its disabled');
			console.log(chks_parent_group[i]);
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
