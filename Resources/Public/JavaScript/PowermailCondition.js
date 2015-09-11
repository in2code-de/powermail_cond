/**
 * PowermailCondition functions
 *
 * @class PowermailCondition
 * @param {jQuery} $formElement powermail form
 * @constructor
 */
function PowermailCondition($formElement) {
	'use strict';

	/**
	 * This class
	 *
	 * @type {PowermailCondition}
	 */
	var that = this;

	/**
	 * Classnames of all default fields
	 *
	 * @type {array}
	 */
	this.defaultFieldClassNames = [
		'powermail_input',
		'powermail_textarea',
		'powermail_select',
		'powermail_radio',
		'powermail_checkbox'
	];

	/**
	 * @type {jQuery}
	 */
	this.$formElement = $formElement;

	/**
	 * Listening for changes
	 *
	 * @returns {void}
	 */
	this.ajaxListener = function() {
		// initially send form values
		that.sendFormValuesToPowermailCond();

		// send form values on a change
		$(that.getDefaultFieldClassNamesList()).on('change', function() {
			that.sendFormValuesToPowermailCond();
		});
	};

	/**
	 * Send form values
	 *
	 * @returns {void}
	 */
	this.sendFormValuesToPowermailCond = function() {
		var formToSend = $(that.$formElement.get(0));
		var tempEnabledFields = formToSend.find(':disabled').removeProp('disabled');
		var dataToSend = new FormData(that.$formElement.get(0));
		tempEnabledFields.prop('disabled', true);
		jQuery.ajax({
			type: 'POST',
			url: that.getAjaxUri(),
			data: dataToSend,
			contentType: false,
			processData: false,
			success: function(data) {
				if (data.loops === 100) {
					that.log('100 loops reached by parsing conditions and rules. Maybe there are conflicting conditions.');
				}
				that.processActions(data);
			}
		});
	};

	/**
	 * do actions with fields or fieldsets
	 *
	 * @param {array} data
	 * @returns {void}
	 */
	this.processActions = function(data) {
		if (data.todo !== undefined) {
			for (var formUid in data.todo) {
				var $form = $('.powermail_form_' + formUid)
				for (var pageUid in data.todo[formUid]) {

					// do actions with whole pages
					var $page = $form.find('.powermail_fieldset_' + pageUid);
					if (data.todo[formUid][pageUid]['#action'] === 'hide') {
						that.hidePage($page);
					}
					if (data.todo[formUid][pageUid]['#action'] === 'un_hide') {
						that.showPage($page);
					}

					// do actions with single fields
					for (var fieldMarker in data.todo[formUid][pageUid]) {
						var $field = $form.find('[id^=powermail_field_' + fieldMarker + ']');
						if (data.todo[formUid][pageUid][fieldMarker]['#action'] === 'hide') {
							that.hideField($field);
						}
						if (data.todo[formUid][pageUid][fieldMarker]['#action'] === 'un_hide') {
							that.showField($field);
						}
					}
				}
			}
		}
	};

	/**
	 * Show a powermail field
	 *
	 * @param {jQuery} $field
	 * @returns {void}
	 */
	this.showField = function($field) {
		$field.removeProp('disabled');
		$field.closest('.powermail_fieldwrap').show();
		that.rerequireField($field);
	};

	/**
	 * Hide a powermail field
	 *
	 * @param {jQuery} $field
	 * @returns {void}
	 */
	this.hideField = function($field) {
		$field.prop('disabled', true);
		$field.closest('.powermail_fieldwrap').hide();
		that.derequireField($field);
	};

	/**
	 * Show a powermail page (fieldset)
	 *
	 * @param {jQuery} $page
	 * @returns {void}
	 */
	this.showPage = function($page) {
		$page.show();
	};

	/**
	 * Hide a powermail page (fieldset)
	 *
	 * @param {jQuery} $page
	 * @returns {void}
	 */
	this.hidePage = function($page) {
		$page.hide();
	};

	/**
	 * Make a required field to a non-required field
	 *
	 * @param {jQuery} $field
	 * @returns {void}
	 */
	this.derequireField = function($field) {
		if ($field.prop('required') || $field.data('parsley-required')) {
			$field.removeProp('required');
			$field.removeAttr('data-parsley-required');
			$field.data('powermailcond-required', 'required');
		}
		that.reInitializeParsleyValidation();
	};

	/**
	 * Make a inactive required field required again
	 *
	 * @param {jQuery} $field
	 * @returns {void}
	 */
	this.rerequireField = function($field) {
		if ($field.data('powermailcond-required') === 'required') {
			if (that.isHtml5ValidationActivated()) {
				$field.prop('required', 'required');
			} else if (that.isParsleyValidationActivated()) {
				$field.prop('required', 'required');
			}
		}
		$field.removeData('powermailcond-required');
		that.reInitializeParsleyValidation();
	};

	/**
	 * get page id
	 *
	 * @returns {int}
	 */
	this.getAjaxUri = function() {
		var uri = $('*[data-condition-uri]').data('condition-uri');
		if (uri === undefined) {
			that.log('Tag with data-condition-uri not found. Maybe TypoScript was not included.');
		}
		return uri;
	};

	/**
	 * get default field class names as string
	 *
	 * @returns {string}
	 */
	this.getDefaultFieldClassNamesList = function() {
		return that.listFromArray(that.defaultFieldClassNames, '.');
	};

	/**
	 * Turn off and on parsley validation for a reinitialization
	 *
	 * @returns {void}
	 */
	this.reInitializeParsleyValidation = function() {
		if (that.isParsleyValidationActivated()) {
			that.$formElement.parsley().destroy();
			that.$formElement.parsley();
		}
	};

	/**
	 * Check if parsley validation is activated
	 *
	 * @returns {boolean}
	 */
	this.isParsleyValidationActivated = function() {
		return that.$formElement.data('parsley-validate') === 'data-parsley-validate';
	};

	/**
	 * Check if html5 validation is activated
	 *
	 * @returns {boolean}
	 */
	this.isHtml5ValidationActivated = function() {
		return that.$formElement.data('validate') === 'html5';
	};

	/**
	 * Convert array to a string list
	 *
	 * @param {array} array
	 * @param {string} itemPrefix
	 * @param {string} glue
	 * @returns {string}
	 */
	this.listFromArray = function(array, itemPrefix, glue) {
		itemPrefix = typeof itemPrefix !== 'undefined' ? itemPrefix : '';
		glue = typeof glue !== 'undefined' ? glue : ',';

		var string = '';
		for (var i = 0; i < array.length; i++) {
			if (i > 0) {
				string += glue;
			}
			string += itemPrefix + array[i];
		}
		return string;
	};

	/**
	 * write message to console
	 *
	 * @param {string|object} message
	 * @return {void}
	 */
	this.log = function(message) {
		if (typeof console == 'object') {
			if (typeof message === 'string') {
				message = 'powermail_cond: ' + message;
			}
			console.log(message);
		}
	};

	// make global
	window.PowermailCondition = PowermailCondition;
}

jQuery(document).ready(function() {
	$('form.powermail_form').each(function() {
		var PowermailCondition = new window.PowermailCondition($(this));
		PowermailCondition.ajaxListener();
	});
});
