jQuery(document).ready(function() {
	base = getBaseUrl();
	clearFullSession();
	if ($('form.powermail_form').length > 0) { // only if the powermail form is on the page (not for confirmation page)
		checkConditions(0); // check if something should be changed
	}

	// read values from session
	var url = base + '/index.php?eID=powermailcond_readSession&tx_powermailcond_pi1[form]=' + getFormUid();
	$.ajax({
		url: url, // send to this url
		cache: false, // disable cache (for ie)
		success: function(data) { // return values
			if (data) { // if there is a response
				var sets = data.split(';');
				for (var i=0; i < sets.length; i++) { // for each field which should be filled
					var tmp_value = sets[i].split(':');
					fieldValue(tmp_value[0], tmp_value[1]);
				}
			}
			$('form.powermail_form').fadeTo('fast', 1);
		}
	});

	// save values via ajax to session
	$('.powermail_input, .powermail_textarea, .powermail_select, .powermail_radio, .powermail_checkbox').bind('change', function() {
		$this = $(this); // caching
		var url = base + '/index.php';
		var timestamp = Number(new Date()); // timestamp is needed for a internet explorer workarround (always change a parameter)
		var value = $this.val(); // current value
		if ($(this).hasClass('powermail_checkbox') && !$(this).is(':checked')) { // clean value if checkbox was dechecked
			value = '';
		}
		var uid = $this.closest('.powermail_fieldwrap').attr('id').substr(20); // current field uid (without "uid")
		var name = $this.attr('name');
		var params = 'eID=' + 'powermailcond_saveToSession' + '&tx_powermailcond_pi1[form]=' + getFormUid() + '&tx_powermailcond_pi1[uid]=' + uid + '&tx_powermailcond_pi1[value]=' + value + '&ts=' + timestamp;

		$.ajax({
			type: 'GET', // type
			url: url, // send to this url
			data: params, // add params
			cache: false, // disable cache (for ie)
			success: function(data) { // return values
				if (data != '') { // if there is a response
					$('form.powermail_form').append('Error in powermail_cond.js in change function:' + data);
				}
				checkConditions(uid); // check if something should be changed
			}
		});
	});
});

/**
 * Fill a field with a value
 *
 * @param int fieldUid		Field Uid
 * @param int fieldValue		Field Value
 */
function fieldValue(fieldUid, fieldValue) {
	$('.powermail_field[name="tx_powermail_pi1[field][' + fieldUid + ']"]').val(fieldValue); // select, input, textarea
	$('.powermail_radio[name="tx_powermail_pi1[field][' + fieldUid + ']"], .powermail_checkbox_' + fieldUid).each(function() { // radio, check
		if ($(this).attr('value') == fieldValue) {
			$(this).attr('checked', 'checked');
		}
	})
}

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
		data: 'eID=' + 'powermailcond_getFieldStatus' + params + '&tx_powermailcond_pi1[formUid]=' + getFormUid(), // add params
		cache: false, // disable cache (for ie)
		success: function(data) {
			if (data != 'nochange') {
				$('.powermail_fieldwrap select option').removeClass('hide'); // show all options at the beginning
				$('.powermail_fieldwrap select option').removeAttr('disabled'); // enable all options at the beginning
				if (data != '') { // if there is a response
					if (data.length < 1000) { // stop if wrong result (maybe complete t3 page)
						doAction(data); // hide all given fields
					}
				} else { // if there is no response
					showAll(); // show all fields and fieldsets at the beginning
				}
			}

			// Form validation
			if ($.fn.validationEngine) {
				console.log('validateAgain');
				$('.powermail_form').validationEngine('detach');
			}
		},
		error: function() {
			$('form.powermail_form').append('Error in PowermailCond.js in checkCondtions function by opening the given url');
		}
	});
}

/**
 * Do some actions (hide and/or filter)
 *
 * @param string	list: commaseparated list with uids (1,2,3)
 * @return void
 */
function doAction(list) {
	showAll();

	var uid = list.split(',');
	if (uid.length < 1) {
		return false; // stop process
	}
	for (var i=0; i < uid.length; i++) { // one loop for every affected field
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
 * Hide a field and clear its value
 *
 * @param	integer	uid: uid of the element
 * @return	void
 */
function hideField(uid) {
	$('.powermail_fieldwrap_' + uid).addClass('hide'); // hide current field
	deRequired(uid);
	if ($('.powermail_fieldwrap_' + uid + ' .powermail_field').val() != '') { // only if value is not yet empty
		clearValue('.powermail_fieldwrap_' + uid + ' .powermail_field'); // clear value of current field
		clearSession(uid); // clear value of current field
	}
}

/**
 * Hide some fields, which are bundled in a fieldset and clear there value
 *
 * @param	string	string: mix of uid and values (fieldset:5:12;13;14)
 * @return	void
 */
function hideFieldset(string) {
	var params = string.split(':'); // filter / uid / values
	var values = params[2].split(';'); // value1 / value2 / value3
	$('.powermail_fieldset_' + params[1]).addClass('hide'); // hide current fieldset
	for (var k=0; k < values.length; k++) { // one loop for every field inside the fieldset
		clearValue('.powermail_fieldwrap_' + values[k] + ' .powermail_field'); // clear value of current field
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
	$('.powermail_fieldwrap_' + params[1] + ' .powermail_field > option').addClass('hide').attr('disabled', 'disabled'); // disable all options

	for (var j=0; j < values.length; j++) { // one loop for every option in select field
		$('.powermail_fieldwrap_' + params[1] + ' .powermail_field > option:contains(' + values[j] + ')').removeClass('hide').removeAttr('disabled'); // show this option
	}

	var valueSelected = $('.powermail_fieldwrap_' + params[1] + ' .powermail_field > option:selected').val(); // give me the value of the selected option
	if (params[2].indexOf(valueSelected) == '-1') { // if current selected value is one of the not allowed options
		$('.powermail_fieldwrap_' + params[1] + ' .powermail_field').get(0).selectedIndex = 0; // remove selection (because the selected option is not allowed)
	}
}

/**
 * Show all fields and fieldsets
 *
 * @return void
 */
function showAll() {
	reRequired();
	$('.powermail_fieldwrap, .powermail_fieldset').removeClass('hide');
}

/**
 * Remove required class in Field
 *
 * @param	integer	uid: uid of the element
 * @return	void
 */
function deRequired(uid) {
	// replace validate[required] with [_required_]
	var element = $('*[name="tx_powermail_pi1[field][' + uid +']"]');
	var classValue = element.attr('class');
	classValue = classValue.replace('required', '_required_');
	element.attr('class', classValue);

	// remove required="required"
	element.attr('required', false);
}

/**
 * Re required Fields for JS-Validation
 *
 * @return void
 */
function reRequired() {
	$('.powermail_field').each(function() {
		var element = $(this);
		var classValue = $(this).attr('class');
		if (classValue.indexOf('_required_') !== -1) {
			// replace validate[_required_] with [required]
			classValue = classValue.replace('_required_', 'required');
			element.attr('class', classValue);

			// add required="required"
			if (element.attr('type') == 'text') {
				element.attr('required', 'required');
			}
		}
	});
}

/**
 * Clear value of an inputfield, set selectedIndex to 0 for selection, don't clear value of submit buttons
 *
 * @param	string	selection: selection for jQuery (e.g. input.powermail)
 * @return	void
 */
function clearValue(selection) {
	if ($(selection).attr('type') == 'radio' || $(selection).attr('type') == 'checkbox') {
		$(selection).attr('checked', false);
	} else {
		$(selection).not(':submit').val('');
	}
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

/**
 * Clear session of a uid
 *
 * @param	integer	uid: uid of the element
 * @return	void
 */
function clearSession(uid) {
	var url = base + '/index.php';
	var timestamp = Number(new Date()); // timestamp is needed for a internet explorer workarround (always change a parameter)
	var params = 'eID=' + 'powermailcond_saveToSession' + '&tx_powermailcond_pi1[form]=' + getFormUid() + '&tx_powermailcond_pi1[uid]=' + uid + '&tx_powermailcond_pi1[value]=&ts=' + timestamp;

	$.ajax({
		type: 'GET', // type
		url: url, // send to this url
		data: params, // add params
		cache: false, // disable cache (for ie)
		success: function(data) { // return values
			if (data != '') { // if there is a response
				$('form.powermail_form').append('Error in powermail_cond.js in clearSession function:' + data);
			}
			checkConditions(uid); // check if something should be changed
		}
	});
};

/**
 * Clear session values if form is submitted
 *
 * @return void
 */
function clearFullSession() {
	if ($('.powermail_create').length || $('.powermail_frontend').length) { // if submitted Pi1 OR any Pi2
		var url = base + '/index.php?eID=powermailcond_clearSession';
		$.ajax({
			url: url, // send to this url
			cache: false
		});
	}
}

/**
 * Read From uid from DOM
 *
 * @return int		Form uid
 */
function getFormUid() {
	var classes = $('.powermail_form:first').attr('class').split(' ');
	for (var i=0; i < classes.length; i++) {
		if (classes[i].indexOf('powermail_form_') !== -1) {
			var currentClass = classes[i];
		}
	}

	var formUid = currentClass.substr(15);
	return formUid;
}