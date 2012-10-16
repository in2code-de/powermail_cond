jQuery(document).ready(function() {
	var fieldsOnChange = '.powermail_text, .powermail_textarea, .powermail_select, .powermail_radio, .powermail_check'; // all fields with events
	if ($('form.tx_powermail_pi1_form').length > 0) { // only if the powermail form is on the page (not for confirmation page)
		checkConditions(0); // check if something should be changed
	}
	
	// save values via ajax to session
	$(fieldsOnChange).change(function() {
		//var url = self.location.href;
		var pid = $('#powermail_cond_pid_container').val();
		var url = '/index.php';
		var timestamp = Number(new Date()); // timestamp is needed for a internet explorer workarround (always change a parameter)
		var value = $(this).val(); // current value
		var uid = $(this).attr('id').substr(3); // current uid (without "uid")
		if ($(this).attr('type') == 'radio') { // if field is a radiobutton
			var tmp_uid = uid.split('_'); // split on _
			uid = tmp_uid[0]; // we want only the uid (not the subuid)
		}
		var name = $(this).attr('name');
		if (this.type == 'checkbox' && this.checked == false) { // no checkbox workarround
			value = '';
		}
		if (name.indexOf('tx_powermail_pi1') == '-1') { // if checkbox workarround from powermail
			var value = $('#' + name.substr(6)).val();
			var uid = $('#' + name.substr(6)).attr('id').substr(3);
		}
		var params = 'eID=' + 'powermailcond_saveToSession' + '&id=' + pid + '&tx_powermailcond_pi1[uid]=' + uid + '&tx_powermailcond_pi1[value]=' + value + '&ts=' + timestamp;
		
		$.ajax({
			type: 'GET', // type
			url: url, // send to this url
			data: params, // add params
			cache: false, // disable cache (for ie)
			success: function(data) { // return values
				if (data != '') { // if there is a response
					//alert(data); // alert the response
					$('form.tx_powermail_pi1_form').append('Error in powermail_cond.js in change function:' + data);
				}
				checkConditions(uid); // check if something should be changed
			}
		});
	});
	
});

/**
 * Main function to check conditions and do something (if necessary)
 *
 * @param	integer	uid: Field uid (if available)
 * @return	void
 */
function checkConditions(uid) {
	//var url = self.location.href;
	var pid = $('#powermail_cond_pid_container').val();
	var url = '/index.php';
	var params = '';
	if (uid > 0) {
		params += '&tx_powermailcond_pi1[uid]=' + uid;
	}
	$.ajax({
		type: 'GET', // type
		url: url, // send to this url
		data: 'eID=' + 'powermailcond_getFieldStatus' + params + '&id=' + pid, // add params
		cache: false, // disable cache (for ie)
		beforeSend: function() {
			document.body.style.cursor = 'progress'; // change cursor to busy
		},
		complete: function() {
			document.body.style.cursor = 'auto'; // normal cursor
		},
		success: function(data) { // return values
			if (data != 'nochange') {
				$('.powermail_select option').show(); // show all options at the beginning
				$('.powermail_select option').removeAttr('disabled'); // enable all options at the beginning
				if (data != '') { // if there is a response
					if (data.length < 500) { // stop if wrong result (maybe complete t3 page)
						doAction(data); // hide all given fields
					}
				} else { // if there is no response
					$('.tx_powermail_pi1_fieldwrap_html').show(); // show all fields
					$('.tx-powermail-pi1_fieldset').show(); // show all fieldsets at the beginning
				}
			}
		},
		error: function() {
			//alert("Error in powermail_cond.js:\n");
			$('form.tx_powermail_pi1_form').append('Error in powermail_cond.js in checkCondtions function by opening the url ' + url + '?' + data);
		}
	});
}

/**
 * Do some actions (hide and/or filter)
 *
 * @param	string	list: commaseparated list with uids (1,2,3)
 * @return	void
 */
function doAction(list) {
	$('.tx_powermail_pi1_fieldwrap_html').show(); // show all fields at the beginning
	$('.tx-powermail-pi1_fieldset').show(); // show all fieldsets at the beginning
	
	var uid = list.split(',');
	if (uid.length < 1) { 
		return false; // stop process	
	}
	for (i=0; i<uid.length; i++) { // one loop for every affected field
		if (uid[i].indexOf('fieldset:') != '-1') { // fieldset part
			hideFieldset(uid[i]);
		} else if (uid[i].indexOf('filter:') != '-1') { // filter part
			filterSelection(uid[i]);
		} else { // fields part
			hideField(uid[i]);
		}
	}
}

/**
 * Hide some fields and clear there value
 *
 * @param	string	string: mix of uid and values (fieldset:5:12;13;14)
 * @return	void
 */
function hideFieldset(string) {
	var params = string.split(':'); // filter / uid / values
	var values = params[2].split(';'); // value1 / value2 / value3
	$('fieldset.tx-powermail-pi1_fieldset_' + params[1]).hide(); // hide current fieldset
	for (k=0; k<values.length; k++) { // one loop for every field inside the fieldset
		clearValue('.powermail_uid' + values[k]); // clear value of current field
	}
}

/**
 * Hide some fields and clear there value
 *
 * @param	integer	uid: uid of the element
 * @return	void
 */
function hideField(uid) {
	$('div.tx_powermail_pi1_fieldwrap_html_' + uid).hide(); // hide current field
	if ($('.powermail_uid' + uid).val() != '') { // only if value is not yet empty
		clearValue('.powermail_uid' + uid); // clear value of current field
		clearSession(uid); // clear value of current field
	}
}

/**
 * Hide some fields and clear there value
 *
 * @param	string	string: mix of uid and values (filter:123:Value1;Value2;Value3)
 * @return	void
 */
function filterSelection(string) {
	var params = string.split(':'); // filter / uid / values
	var values = params[2].split(';'); // value1 / value2 / value3
	$('select.powermail_uid' + params[1] + ' option').hide(); // disable all options
	$('select.powermail_uid' + params[1] + ' option').attr('disabled', 'disabled'); // disable all options
	
	for (j=0; j<values.length; j++) { // one loop for every option in select field
		$('select.powermail_uid' + params[1] + ' option:contains(' + values[j] + ')').show(); // show this option
		$('select.powermail_uid' + params[1] + ' option:contains(' + values[j] + ')').removeAttr('disabled'); // enable this option
	}
	
	var valueSelected = $('select.powermail_uid' + params[1] + ' option:selected').val(); // give me the value of the selected option
	if (params[2].indexOf(valueSelected) == '-1') { // if current selected value is one of the not allowed options
		$('select.powermail_uid' + params[1]).get(0).selectedIndex = 0; // remove selection (because the selected option is not allowed)
	}
}

/**
 * Clear value of an inputfield, set selectedIndex to 0 for selection and so on
 *
 * @param	string	selection: selection for jQuery (e.g. input.powermail)
 * @return	void
 */
function clearValue(selection) {
	if ($(selection).attr('type') == 'radio' || $(selection).attr('type') == 'checkbox') {
		$(selection).attr('checked', false);
	} else {
		$(selection).val('');
	}
}

/**
 * Clear session of a uid
 *
 * @param	integer	uid: uid of the element
 * @return	void
 */
function clearSession(uid) {
	//var url = self.location.href;
	var pid = $('#powermail_cond_pid_container').val();
	var url = '/index.php';
	var timestamp = Number(new Date()); // timestamp is needed for a internet explorer workarround (always change a parameter)
	var params = 'eID=' + 'powermailcond_saveToSession' + '&id=' + pid + '&tx_powermailcond_pi1[uid]=' + uid + '&tx_powermailcond_pi1[value]=&ts=' + timestamp;
	
	$.ajax({
		type: 'GET', // type
		url: url, // send to this url
		data: params, // add params
		cache: false, // disable cache (for ie)
		success: function(data) { // return values
			if (data != '') { // if there is a response
				//alert(data); // alert the response
				$('form.tx_powermail_pi1_form').append('Error in powermail_cond.js in clearSession function:' + data);
			}
			checkConditions(uid); // check if something should be changed
		}
	});
};