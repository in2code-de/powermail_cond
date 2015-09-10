/**
 * PowermailCondition functions
 *
 * @class PowermailCondition
 * @param {jQuery} $formElement powermail form
 * @constructor
 */
function PowermailCondition($formElement) {

	/**
	 * @type {jQuery}
	 */
	this.$formElement = $formElement;

	/**
	 * This class
	 *
	 * @type {PowermailCondition}
	 */
	var that = this;

	/**
	 * @type {null}
	 */
	var init = initialize();

	/**
	 * Classnames of all default fields
	 *
	 * @type {array}
	 */
	var defaultFieldClassNames = [
		'powermail_input',
		'powermail_textarea',
		'powermail_select',
		'powermail_radio',
		'powermail_checkbox'
	];

	this.sendFormValuesToPowermailCond = function() {
		var formToSend = $(that.$formElement.get(0));
		var tempEnabledFields = formToSend.find(':disabled').removeProp('disabled');
		var dataToSend = new FormData(that.$formElement.get(0));
		tempEnabledFields.prop('disabled', true);
		jQuery.ajax({
			type: 'POST',
			url: 'index.php?type=3131',
			data: dataToSend,
			contentType: false,
			processData: false,
			success: function(data) {
				console.log(data);
				if (data.loops === 100) {
					console.log('DFAAFUQ');
				}
				if (data.todo !== undefined) {
					for (var formUid in data.todo) {
						var form = $('.powermail_form_' + formUid)
						for (var pageUid in data.todo[formUid]) {

							var page = form.find('.powermail_fieldset_' + pageUid);
							if (data.todo[formUid][pageUid]['action'] === 'hide') {
								page.hide();
							}
							if (data.todo[formUid][pageUid]['action'] === 'un_hide') {
								page.show();
							}

							for (var fieldMarker in data.todo[formUid][pageUid]) {
								var fields = form.find('[id^=powermail_field_' + fieldMarker + ']');
								if (data.todo[formUid][pageUid][fieldMarker]['action'] === 'hide') {
									fields.prop('disabled', true);
									fields.closest('.powermail_fieldwrap').hide();
								}
								if (data.todo[formUid][pageUid][fieldMarker]['action'] === 'un_hide') {
									fields.removeProp('disabled');
									fields.closest('.powermail_fieldwrap').show();
								}
							}
						}
					}
				}

			}
		});
	};

	/**
	 * Initialize
	 *
	 * @returns {null}
	 */
	function initialize() {
		that.$formElement.css('background-color', 'red');
	}

	// make global
	window.PowermailCondition = PowermailCondition;
}

jQuery(document).ready(function() {
	$('form.powermail_form').each(function() {
		var PowermailCondition = new window.PowermailCondition($(this));
		PowermailCondition.sendFormValuesToPowermailCond();
		$(this).find('input').each(function() {
			$(this).on('change', function() {
				PowermailCondition.sendFormValuesToPowermailCond();
			});
		});
		$(this).find('select').each(function() {
			$(this).on('change', function() {
				PowermailCondition.sendFormValuesToPowermailCond();
			});
		});
		$(this).find('textarea').each(function() {
			$(this).on('change', function() {
				PowermailCondition.sendFormValuesToPowermailCond();
			});
		});
	});
});
