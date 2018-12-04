(function($) {
	/**
	 * PowermailCondition functions
	 *
	 * @class PowermailCondition
	 * @param {HTMLFormElement} formElement powermail form
	 * @constructor
	 */
	function PowermailCondition(formElement) {
		'use strict';

		/**
		 * Closure for $(formElement)
		 *
		 * @type {jQuery}
		 */
		var $formElement = $(formElement);

		/**
		 * Classnames of all default fields (used via closure)
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

		/**
		 * Listening for changes
		 *
		 * @returns {void}
		 */
		this.ajaxListener = function() {
			// initially send form values
			sendFormValuesToPowermailCond();

			// send form values on a change
			$(getDefaultFieldClassNamesList()).on('change', function() {
				sendFormValuesToPowermailCond();
			});
		};

		/**
		 * do actions with fields or fieldsets (private)
		 *
		 * @param {array} data
		 * @returns {void}
		 */
		var processActions = function(data) {
			if (data.todo !== undefined) {
				for (var formUid in data.todo) {
					var $form = $('.powermail_form_' + formUid)
					for (var pageUid in data.todo[formUid]) {

						// do actions with whole pages
						var $page = $form.find('.powermail_fieldset_' + pageUid);
						if (data.todo[formUid][pageUid]['#action'] === 'hide') {
							hidePage(getFieldsetByUid(pageUid, $form));
						}
						if (data.todo[formUid][pageUid]['#action'] === 'un_hide') {
							showPage(getFieldsetByUid(pageUid, $form));
						}

						// do actions with single fields
						for (var fieldMarker in data.todo[formUid][pageUid]) {
							if (data.todo[formUid][pageUid][fieldMarker]['#action'] === 'hide') {
								hideField(fieldMarker, $form);
							}
							if (data.todo[formUid][pageUid][fieldMarker]['#action'] === 'un_hide') {
								showField(fieldMarker, $form);
							}
						}
					}
				}
				
				reInitializeParsleyValidation();
			}
		};

		/**
		 * Send form values (private)
		 *
		 * @returns {void}
		 */
		var sendFormValuesToPowermailCond = function() {
			var formToSend = $($formElement.get(0));
			var tempEnabledFields = formToSend.find(':disabled').prop('disabled', false);
			var dataToSend = new FormData($formElement.get(0));
			dataToSend.find('input[type="file"]').map(function(i,input){
				dataToSend.delete && dataToSend.delete(input.name)
			})
			tempEnabledFields.prop('disabled', true);
			$.ajax({
				type: 'POST',
				url: getAjaxUri(),
				data: dataToSend,
				contentType: false,
				processData: false,
				success: function(data) {
					if (data.loops === 100) {
						log('100 loops reached by parsing conditions and rules. Maybe there are conflicting conditions.');
					}
					processActions(data);
				}
			});
		};

		/**
		 * Make a required field to a non-required field (private)
		 *
		 * @param {jQuery} $field
		 * @returns {void}
		 */
		var derequireField = function($field) {
			if ($field.prop('required') || $field.data('parsley-required')) {
				$field.prop('required', false);
				$field.removeAttr('data-parsley-required');
				$field.data('powermailcond-required', 'required');
			}
		};

		/**
		 * Make a inactive required field required again
		 *
		 * @param {jQuery} $field
		 * @returns {void}
		 */
		var rerequireField = function($field) {
			if ($field.data('powermailcond-required') === 'required') {
				if (isHtml5ValidationActivated()) {
					$field.prop('required', 'required');
				} else if (isParsleyValidationActivated()) {
					$field.prop('required', 'required');
				}
			}
			$field.removeData('powermailcond-required');
		};

		/**
		 * Show a powermail field (private)
		 *
		 * @param {string} fieldMarker
		 * @param {jQuery} $form
		 * @returns {void}
		 */
		var showField = function(fieldMarker, $form) {
			var $wrappingContainer = $form.find('.powermail_fieldwrap_' + fieldMarker);
			$wrappingContainer.show();
			var $field = getFieldByMarker(fieldMarker, $form);
			$field.prop('disabled', false);
			rerequireField($field);
		};

		/**
		 * Hide a powermail field (private)
		 *
		 * @param {string} fieldMarker
		 * @param {jQuery} $form
		 * @returns {void}
		 */
		var hideField = function(fieldMarker, $form) {
			var $wrappingContainer = $form.find('.powermail_fieldwrap_' + fieldMarker);
			$wrappingContainer.hide();
			var $field = getFieldByMarker(fieldMarker, $form);
			$field.prop('disabled', true);
			derequireField($field);
		};

		/**
		 * Show a powermail page (fieldset)
		 *
		 * @param {jQuery} $page
		 * @returns {void}
		 */
		var showPage = function($page) {
			$page.show();
		};

		/**
		 * Hide a powermail page (fieldset)
		 *
		 * @param {jQuery} $page
		 * @returns {void}
		 */
		var hidePage = function($page) {
			$page.hide();
		};

		/**
		 * get page id
		 *
		 * @returns {int}
		 */
		var getAjaxUri = function() {
			var uri = $('*[data-condition-uri]').data('condition-uri');
			if (uri === undefined) {
				log('Tag with data-condition-uri not found. Maybe TypoScript was not included.');
			}
			return uri;
		};

		/**
		 * Select a powermail field by its name (private)
		 *         name="tx_powermail_pi1[field][fieldMarker]"
		 *         or
		 *         name="tx_powermail_pi1[field][fieldMarker][]"
		 *
		 * @param {string} fieldMarker
		 * @param {jQuery} $form
		 * @returns {jQuery}
		 */
		var getFieldByMarker = function(fieldMarker, $form) {
			return $form.find('[name^="tx_powermail_pi1[field][' + fieldMarker + ']"]').not('[type="hidden"]');
		};

		/**
		 * Select a powermail fieldset (private)
		 *         class="powermail_fieldset_[pageUid]"
		 *
		 * @param {string} pageUid
		 * @param {jQuery} $form
		 * @returns {jQuery}
		 */
		var getFieldsetByUid = function(pageUid, $form) {
			return $form.find('.powermail_fieldset_' + pageUid);
		};

		/**
		 * Convert array to a string list (private)
		 *
		 * @param {array} array
		 * @param {string} itemPrefix
		 * @param {string} glue
		 * @returns {string}
		 */
		var listFromArray = function(array, itemPrefix, glue) {
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
		 * get default field class names as string
		 *
		 * @returns {string}
		 */
		var getDefaultFieldClassNamesList = function() {
			return listFromArray(defaultFieldClassNames, '.');
		};

		/**
		 * Check if parsley validation is activated (private)
		 *
		 * @returns {boolean}
		 */
		var isParsleyValidationActivated = function() {
			return $formElement.data('parsley-validate') === 'data-parsley-validate';
		};

		/**
		 * Check if html5 validation is activated (private)
		 *
		 * @returns {boolean}
		 */
		var isHtml5ValidationActivated = function() {
			return $formElement.data('validate') === 'html5';
		};

		/**
		 * Turn off and on parsley validation for a reinitialization (private)
		 *
		 * @returns {void}
		 */
		var reInitializeParsleyValidation = function() {
			if (isParsleyValidationActivated()) {
				$formElement.parsley().destroy();
				$formElement.parsley();
			}
		};

		/**
		 * write message to console
		 *
		 * @param {string|object} message
		 * @return {void}
		 */
		var log = function(message) {
			if (typeof console == 'object') {
				if (typeof message === 'string') {
					message = 'powermail_cond: ' + message;
				}
				console.log(message);
			}
		};
	}

	$(document).ready(function() {
		$('form.powermail_form').each(function() {
			(new PowermailCondition(this)).ajaxListener();
		});
	});

})(jQuery);
