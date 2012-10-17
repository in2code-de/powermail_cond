jQuery(document).ready(function() {
	base = getBaseUrl();
	if ($('form.powermail_form').length > 0) { // only if the powermail form is on the page (not for confirmation page)
		checkConditions(0); // check if something should be changed
	}
});

/**
 * Main function to check conditions and do something (if necessary)
 *
 * @param	integer	uid: Field uid (if available)
 * @return	void
 */
function checkConditions(uid) {
	var url = base + '/index.php';
	var params = '';
	if (uid > 0) {
		params += '&tx_powermailcond_pi1[uid]=' + uid;
	}
	$.ajax({
		type: 'GET', // type
		url: url, // send to this url
		data: 'eID=' + 'powermailcond_getFieldStatus' + params + '&tx_powermailcond_pi1[formUid]=' + $('input[name="tx_powermail_pi1[form]"]').val(), // add params
		cache: false, // disable cache (for ie)
		beforeSend: function() {
			document.body.style.cursor = 'progress'; // change cursor to busy
		},
		complete: function() {
			document.body.style.cursor = 'auto'; // normal cursor
		},
		success: function(data) { // return values
			$('form.powermail_form').append(data);
//			if (data != 'nochange') {
//				$('.powermail_select option').show(); // show all options at the beginning
//				$('.powermail_select option').removeAttr('disabled'); // enable all options at the beginning
//				if (data != '') { // if there is a response
//					if (data.length < 500) { // stop if wrong result (maybe complete t3 page)
//						doAction(data); // hide all given fields
//					}
//				} else { // if there is no response
//					$('.tx_powermail_pi1_fieldwrap_html').show(); // show all fields
//					$('.tx-powermail-pi1_fieldset').show(); // show all fieldsets at the beginning
//				}
//			}
		},
		error: function() {
			$('form.powermail_form').append('Error in PowermailCond.js in checkCondtions function by opening the given url');
		}
	});
}

/**
 * Read BaseUrl
 *
 * @return string	BaseUrl from Tag in DOM
 */
function getBaseUrl() {
	var base = $('base').attr('href');
	if (!base || base == undefined) {
		base = '';
	}
	return base;
}