.. include:: ../Includes.txt
.. include:: Images.txt

.. _records:

Records
=======

You can add records of type condition container to any sysfolder.
Specify which powermail form is related to it.

In this record, you can add one or more conditions (via IRRE).
Specify which is the target field and what should happen with it (hide or show).

In this record, you can add one or more rules (via IRRE).
Specify which is the starting field and what event creates an action.

|backend_condition_container|

.. t3-field-list-table::
 :header-rows: 1

 - :Field:
      Field
   :Description:
      Description
   :Explanation:
      Explanation
   :Record:
      Record

 - :Field:
      Title
   :Description:
      Add an internal title for this condition container
   :Explanation:
      This should help you to manage this records in backend
   :Record:
      Condition Container

 - :Field:
      Form
   :Description:
      Select a powermail form where the conditions should work
   :Explanation:
      Every form can only have one condition container
   :Record:
      Condition Container

 - :Field:
      Create new Condition
   :Description:
      Add one or more conditions
   :Explanation:
      -
   :Record:
      Condition Container

 - :Field:
      Title
   :Description:
      Add an internal title for this condition
   :Explanation:
      This should help you to manage this records in backend
   :Record:
      Condition

 - :Field:
      Target field
   :Description:
      Select a field (or a complete fieldset) where an action should work
   :Explanation:
      If you have not yet saved the record, all fields will be shown. If you want to show only fields of the related form, please save before.
   :Record:
      Condition

 - :Field:
      Action
   :Description:
      Select what should happen to the target field
   :Explanation:
      At the moment you can hide or unhide fields (or fieldsets)
   :Record:
      Condition

 - :Field:
      Conjunction
   :Description:
      Define how rules should be related
   :Explanation:
      OR or AND
   :Record:
      Condition

 - :Field:
      Create new Rules
   :Description:
      Add one or more rules
   :Explanation:
      -
   :Record:
      Condition

 - :Field:
      Title
   :Description:
      Add an internal title for this rule
   :Explanation:
      This should help you to manage this records in backend
   :Record:
      Rule

 - :Field:
      Start field
   :Description:
      Select which field should start an action
   :Explanation:
      If you have not yet saved the record, all fields will be shown. If you want to show only fields of the related form, please save before.
   :Record:
      Rule

 - :Field:
      Operator
   :Description:
      Define which event should start an action
   :Explanation:

      * is set: Check if there a any value in the field
      * is not set: Check if there is no value in the field
      * contains value: Check if the fieldvalue contains a defined value (will show field value)
      * contains value not: Check if the fieldvalue contains not a defined value (will show field value)
      * is: Check if field value is exact a defined value (will show field value)
      * is not: Check if field value is not a defined value (will show field value)
      * is greater than: Check if field value is greater than defined number (will show field value)
      * is less than: Check if field value is smaller than defined number (will show field value)
      * contains value from field: Check if field value is the same as in another field (will show field comparison)
      * contains not value from field: Check if field value is not the same as in another field (will show field comparison)

   :Record:
      Rule

 - :Field:
      Value
   :Description:
      Comperison value
   :Explanation:
      If you have chosen an operator that needs a fix value for comparison, you can add the value in this field
   :Record:
      Rule

 - :Field:
      Field for comparison
   :Description:
      Comperison field
   :Explanation:
      If you have chosen an operator that needs another field for comparison, you can select a field
   :Record:
      Rule