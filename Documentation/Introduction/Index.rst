.. include:: ../Includes.txt
.. include:: Images.txt

.. _introduction:

Introduction
============

.. only:: html

	:ref:`what` | :ref:`facts` | :ref:`compatibility` | :ref:`screenshots` | :ref:`technique` |


.. _what:

What does it do?
----------------

This extension is an add on to the powermail extension.
It brings conditional logic to powermail.
Maybe you want to show or hide fields if another field was filled with a value - powermail_cond will do the job.

The version 3.0 is the second refactored version, that should support powermail 2.9.0 or newer.

Example usages:

* Hide phone field if email was entered
* Show submit button only if an option was selected in a selectbox
* Show a couple of address fields (fieldset) if checkbox "different delivery address" was selected
* Show only a second field if first field has exact a defined string


.. _facts:

Facts
-----

- Features

  - Actions

    - Show a field
    - Hide a field
    - Show a complete page (fieldset)
    - Hide a complete page (fieldset)

  - Events

    - At once (per JavaScript) if someone changes a field in frontend

  - Rules (do something, only if ...)

    - A field is filled
    - A field is empty
    - A field contains a defined value
    - A field contains not a defined value
    - A field is filled with a defined value
    - A field is not filled with a defined value
    - A field value is greater than a defined number
    - A field value is smaller than a defined number
    - A field value is the same value as in another field
    - A field value is not the same value as in another field

  - Connection between rules

    - OR
    - AND


.. _compatibility:

Compatible Powermail versions
-----------------------------

.. t3-field-list-table::
 :header-rows: 1

 - :PowermailVersion:
      Powermail Version
   :TYPO3Version:
      TYPO3 Versions

 - :PowermailVersion:
      2.9.0 - 2.99.99
   :TYPO3Version:
      6.2 LTS, 7.0, 7.1, 7.2, 7.3, 7.4


.. _screenshots:

Screenshots
-----------

Example: Hide a required field
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

|powermail_form_all|

Powermail field with two required fields


|powermail_form_validation|

Try to submit shows the validation messages from powermail


|powermail_form_hiddenfield|

If there is a value in Tel, hide email (and its validation messages)


Backend configuration
^^^^^^^^^^^^^^^^^^^^^

|backend_condition_container|

Example configuration in backend. If there is a value in field "telephone", hide field "email".



.. _technique:

How does it work?
-----------------

* Every time a field value is entered or changed in frontend, all form values will be send via AJAX to an PHP action
* The logic is implemented on serverside. All rules and conditions will be iterated for the related field
* A JSON will be send to the clientside script, which will hide or show fields or fieldsets
* jQuery is needed