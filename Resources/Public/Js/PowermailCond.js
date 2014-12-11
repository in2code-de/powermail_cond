jQuery(document).ready(function() {
	base = getBaseUrl();
	validationFieldClasses = '.powermail_input, .powermail_textarea, .powermail_select, .powermail_radio, .powermail_checkbox';
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
	$(validationFieldClasses).bind('change', function() {
		$this = $(this); // caching
		var url = base + '/index.php';
		var timestamp = Number(new Date()); // timestamp is needed for a internet explorer workarround (always change a parameter)
		var value = $this.val(); // current value
		if ($this.hasClass('powermail_checkbox') && !$this.is(':checked')) { // clean value if checkbox was dechecked
			value = '';
		}
		var uid = $this.closest('.powermail_fieldwrap').attr('id').substr(20); // current field uid (without "uid")
		var name = $this.prop('name');
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
 * @param {int} fieldUid - Field Uid
 * @param {int} fieldValue - Field Value
 */
function fieldValue(fieldUid, fieldValue) {
	var fieldWrap = $('#powermail_fieldwrap_' + fieldUid);

	// set value for all default fields
	fieldWrap
		.find('input.powermail_field')
		.not('[type="checkbox"]')
		.val(fieldValue);

	// check checkboxes and radiobuttons
	fieldWrap.find('input[type="checkbox"]').each(function() {
		if ($(this).prop('value') == fieldValue) {
			$(this).prop('checked', 'checked');
		}
	})
}

/**
 * Main function to check conditions and do something (if necessary)
 *
 * @param {integer} uid: Field uid (if available)
 * @return void
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
				$('.powermail_fieldwrap select option').removeClass('hide').removeProp('disabled');
				if (data != '') { // if there is a response
					if (data.length < 1000) { // stop if wrong result (maybe complete t3 page)
						doAction(data); // hide all given fields
					}
				} else { // if there is no response
					showAll(); // show all fields and fieldsets at the beginning
				}
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
 * @param {string} list commaseparated list with uids (1,2,3)
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
 * @param {integer} uid uid of the element
 * @return void
 */
function hideField(uid) {
	$('.powermail_fieldwrap_' + uid).addClass('hide'); // hide current field
	deRequiredField(uid, true);
	if ($('.powermail_fieldwrap_' + uid + ' .powermail_field').val() != '') { // only if value is not yet empty
		clearValue('.powermail_fieldwrap_' + uid + ' .powermail_field'); // clear value of current field
		clearSession(uid); // clear value of current field
	}
}

/**
 * Hide some fields, which are bundled in a fieldset and clear there value
 *
 * @param {string} string mix of uid and values (fieldset:5:12;13;14)
 * @return void
 */
function hideFieldset(string) {
	var params = string.split(':'); // filter / uid / values
	var values = params[2].split(';'); // value1 / value2 / value3
	$('.powermail_fieldset_' + params[1]).addClass('hide');
	var fields = [];
	for (var k=0; k < values.length; k++) {
		clearValue('.powermail_fieldwrap_' + values[k] + ' .powermail_field');
		deRequiredField(values[k], true);
		fields.push(values[k]);
	}

	// save this field in session so it's no mandatory field any more
	$.ajax({
		url: '/index.php',
		data: 'eID=' + 'powermailcond_deRequiredFields&tx_powermailcond_pi1[formUid]=' + getFormUid() + '&tx_powermailcond_pi1[fieldUids]=' + fields.join() + '&no_cache=1',
		cache: false,
		async: false
	});
}

/**
 * Hide some fields and clear there value
 *
 * @param {string} string mix of uid and values (filter:123:Value1;Value2;Value3)
 * @return void
 */
function filterSelection(string) {
	var params = string.split(':'); // filter / uid / values
	var values = params[2].split(';'); // value1 / value2 / value3
	$('.powermail_fieldwrap_' + params[1] + ' .powermail_field > option').addClass('hide').prop('disabled', 'disabled'); // disable all options

	for (var j=0; j < values.length; j++) { // one loop for every option in select field
		$('.powermail_fieldwrap_' + params[1] + ' .powermail_field > option:contains(' + values[j] + ')').removeClass('hide').removeProp('disabled'); // show this option
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
	reRequiredAll();
	$('.powermail_fieldwrap, .powermail_fieldset').removeClass('hide');
}

/**
 * Remove required class in Field
 *
 * @param {int} uid of the element
 * @param {bool} disableAjaxRequest
 * @return void
 */
function deRequiredField(uid, disableAjaxRequest) {
	// save this field in session so it's no mandatory field any more
	if (disableAjaxRequest !== undefined && disableAjaxRequest === true) {
		$.ajax({
			url: '/index.php',
			data: 'eID=' + 'powermailcond_deRequiredField&tx_powermailcond_pi1[formUid]=' + getFormUid() + '&tx_powermailcond_pi1[fieldUid]=' + uid + '&no_cache=1',
			cache: false,
			async: false
		});
	}

	// rewrite required attributes
	var element = $('#powermail_fieldwrap_' + uid).find('input');
	if (element.prop('required') || element.data('parsley-required')) {
		element.removeProp('required'); // remove required attribute
		element.removeProp('data-parsley-required'); // remove parsley-required attribute
		element.data('powermailcond-required', 'required'); // add own data required attribute
	}
	if (isParsleyValidationActivated()) {
		// reinit parsley seems only to work with required="required" and not with data-parslay attributes :(
		var form = $('form.powermail_form_'+ getFormUid());
		form.parsley().destroy(); // turn off parsley
		form.parsley(); // turn on parsley again
	}
}

/**
 * Re required Fields for JS-Validation
 *
 * @return void
 */
function reRequiredAll() {
	// Required Fields all
	$.ajax({
		url: '/index.php',
		data: 'eID=' + 'powermailcond_requiredFields&tx_powermailcond_pi1[formUid]=' + getFormUid(),
		cache: false,
		async: false
	});

	$(validationFieldClasses).each(function() {
		var $this = $(this);
		$this.removeProp('powermailcond-required');
		if ($this.data('powermailcond-required') === 'required') {
			if (isHtml5ValidationActivated()) {
				$this.prop('required', 'required');
			} else if (isParsleyValidationActivated()) {
				$this.prop('required', 'required');
			}
		}
	});
}

/**
 * Clear value of an inputfield, set selectedIndex to 0 for selection, don't clear value of submit buttons
 *
 * @param {string} selection selection for jQuery (e.g. "input.powermail")
 * @return void
 */
function clearValue(selection) {
	if ($(selection).prop('type') == 'radio' || $(selection).prop('type') == 'checkbox') {
		$(selection).prop('checked', false);
	} else {
		$(selection).not(':submit').val('');
	}
}

/**
 * Read BaseUrl
 *
 * @return {string} BaseUrl from Tag in DOM
 */
function getBaseUrl() {
	var base = $('base').prop('href');
	if (!base || base == undefined) {
		base = '';
	}
	return base;
}

/**
 * Clear session of a uid
 *
 * @param {integer} uid of the element
 * @return void
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
		async: false,
		success: function(data) { // return values
			if (data != '') { // if there is a response
				$('form.powermail_form').append('Error in powermail_cond.js in clearSession function:' + data);
			}
			checkConditions(uid); // check if something should be changed
		}
	});
}

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
			cache: false,
			async: false
		});
	}
}

/**
 * Read Form uid from DOM
 *
 * @return {int} Form uid
 */
function getFormUid() {
	var formField = $('input[name="tx_powermail_pi1[mail][form]"]');
	if (formField.length === 0) {
		return 0;
	}
	return formField.val();
}

/**
 * Check if Parsley is activated
 *
 * @return bool
 */
function isParsleyValidationActivated() {
	if ($('form.powermail_form_'+ getFormUid()).data('parsley-validate') === 'data-parsley-validate') {
		return true;
	}
	return false;
}

/**
 * Check if HTML5 Validation is activated
 *
 * @return bool
 */
function isHtml5ValidationActivated() {
	if ($('form.powermail_form_'+ getFormUid()).data('validate') === 'html5') {
		return true;
	}
	return false;
}