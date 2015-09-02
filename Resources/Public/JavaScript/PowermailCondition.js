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
			url: 'index.php',
			data: new FormData(that.$formElement.get(0)),
			contentType: false,
			processData: false,
			success: function(data) {
				console.log(data);
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