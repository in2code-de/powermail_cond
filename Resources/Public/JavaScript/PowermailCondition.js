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
		jQuery.ajax({
			type: 'POST',
			url: 'index.php?type=3131',
			data: new FormData(that.$formElement.get(0)),
			contentType: false,
			processData: false,
			success: function(data) {
				console.log(data);
				if (data.todo !== undefined) {
					for (var formUid in data.todo) {
						var form = $('.powermail_form_' + formUid)
						for (var pageUid in data.todo[formUid]) {
							for (var fieldMarker in data.todo[formUid][pageUid]) {
								var input = form.find('#powermail_field_' + fieldMarker);
								if (data.todo[formUid][pageUid][fieldMarker]['action'] === 'hide') {
									input.val('');
									input.prop('disabled', true);
									input.closest('.powermail_fieldwrap').hide();
								}
								if (data.todo[formUid][pageUid][fieldMarker]['action'] === 'un_hide') {
									input.prop('disabled', false);
									input.closest('.powermail_fieldwrap').show();
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
		//that.sendFormValuesToPowermailCond();
	}

	// make global
	window.PowermailCondition = PowermailCondition;
}

jQuery(document).ready(function() {
	$('form.powermail_form').each(function() {
		var PowermailCondition = new window.PowermailCondition($(this));
		PowermailCondition.sendFormValuesToPowermailCond();
	});
});
