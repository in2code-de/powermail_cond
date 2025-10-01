# TYPO3 Extension powermail_cond

Conditions for TYPO3 extension powermail.
While a user fills out a form, some fields should disappear, while
others should be visible.

> :warning: **TYPO3 13 compatibility**\
> See [EAP page (DE)](https://www.in2code.de/agentur/typo3-extensions/early-access-programm/) or [EAP page (EN)](https://www.in2code.de/en/agency/typo3-extensions/early-access-program/) for more information how to get access to a TYPO3 13 version

## Screenshots

![Example form with conditions](Documentation/Images/screenshot_powermail_cond_frontend.png "Example form with conditions")

![Backend view to records](Documentation/Images/screenshot_powermail_cond_backend_records.png "Backend view to records")

![Backend view to records with rule](Documentation/Images/screenshot_powermail_cond_backend_records_conditionrule.png "Backend view to records with rule")

## List of Conditions you can apply on the powermail form fields
* `is set`
* `is not set`
* `contains value`
* `contains value not`
* `is`
* `is not`
* `is greater than (numbers only)`
* `is less than (numbers only)`
* `contains value from field`
* `contains not value from field`

## Quick installation

Quick guide:
- Just install this extension - e.g. `composer require in2code/powermail_cond`
- Clear caches
- Add a powermail form to any page
- Add a new record from type "condition container" to a sysfolder and configure it
- Don't forget to include the static template from powermail_cond
- Don't forget to add jQuery to your frontend (if not yet installed)

Example routing configuration for TypeNum 3132:

```
...
rootPageId: 1
routes:
  -
    route: robots.txt
    type: staticText
    content: "Disallow: /typo3/\r\n"
routeEnhancers:
  PageTypeSuffix:
    type: PageType
    default: /
    index: ''
    suffix: /
    map:
      condition.json: 3132
...
```

## Trouble shoot: upload fields

For being able to evaluate the conditions on the backend the form data gets sent as payload to the `conditions.json` route. In case of upload files (a.k.a `[type=file]`) the entire set of selected files are being uploaded with every change of basically every input field. This is resource and time consuming.

In case you don't rely on upload field within your set of conditions you can exclude them from being sent to the backend. To do so just specify add a parameter `data-powermail-cond-excluded-fields-selector` to the form template, e.g.

```xml
<f:form
    action="{action}"
    section="c{ttContentData.uid}"
    name="field"
    enctype="multipart/form-data"
    additionalAttributes="{vh:validation.enableJavascriptValidationAndAjax(form:form, additionalAttributes:{data-powermail-cond-excluded-fields:'.powermail_file'})}"
>
```

## Local Development and Contribution
There is a docker based local development environment available.
See [Readme.md](Documentation/ForDevelopers/Readme.md) for more information.

## Less flickering

To prevent the flickering that occurs when loading a form with conditions the usually asynchronously loaded "condition JSON" can be rendered directly into the HTML source code via this viewhelper in your copy of `EXT:powermail/Resources/Private/Templates/Form/Form.html`

```xml
{namespace pc=In2code\PowermailCond\ViewHelpers}
<script type="application/json" id="form-{form.uid}-actions">{pc:conditions(form:form) -> f:format.raw()}</script>
<style type="text/css">
    .powermail_fieldset {
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.5s, visibility 0.5s;
    }
</style>
```

This way the initial asynchronous call will be skipped which reduces the flickering to a minimum.

## Early Access Programm for TYPO3 13 support

:information_source: **TYPO3 13 compatibility**
> See [EAP page (DE)](https://www.in2code.de/agentur/typo3-extensions/early-access-programm/) or
> [EAP page (EN)](https://www.in2code.de/en/agency/typo3-extensions/early-access-program/) for more information how
> to get access to a TYPO3 13 version


## Changelog

| Version | Date       | State   | Description                                                                                          |
|---------|------------|---------|------------------------------------------------------------------------------------------------------|
| 11.2.7  | 2025-10-01 | Bugfix  | Prevent race condition in powermail ajax forms                                                       |
| 11.2.5  | 2024-08-08 | Bugfix  | Handle missing arguments in ConditionController requests                                             |
| 11.2.4  | 2024-11-28 | Bugfix  | Prevent the flickering that occurs when loading a form with conditions                               |
| 11.2.3  | 2024-09-20 | Bugfix  | Some small bugfixes                                                                                  |
| 11.2.2  | 2024-10-16 | TASK    | Adjust autodeployment                                                                                |
| 11.2.1  | 2024-10-16 | Bugfix  | Fix autodeployment                                                                                   |
| 11.2.0  | 2024-02-15 | Feature | Move public repository, adjust deployment, fix error in multivalue checkboxes                        |
| 11.1.0  | 2023-10-16 | Feature | Support Powermail 11 & 12                                                                            |
| 11.0.0  | 2023-07-05 | Feature | Support Powermail 11                                                                                 |
| 10.1.1  | 2023-03-23 | Bugfix  | Fix possible undefined array key error                                                               |
| 10.1.0  | 2023-03-14 | Task    | Support all kind of powermail fields as with jQuery before (e.g. submit, text, etc...)               |
| 10.0.0  | 2022-10-10 | Feature | Support for Powermail 10 and remove of jQuery support                                                |
| 9.0.4   | 2022-10-10 | Bugfix  | Allow multilanguage conditions                                                                       |
| 9.0.3   | 2022-07-05 | Bugfix  | Fix ext_emconf.php for TER upload via REST API (another change)                                      |
| 9.0.2   | 2022-07-05 | Bugfix  | Fix ext_emconf.php for TER upload via REST API                                                       |
| 9.0.1   | 2022-07-04 | Bugfix  | Fix page fieldset conditions not being applied correctly                                             |
| 9.0.0   | 2022-02-23 | Feature | Support for TYPO3 11 and Powermail 9                                                                 |
| 8.2.2   | 2021-11-22 | Bugfix  | Reverted type change for field condition.target_field and updated index configuration for this field |
| 8.2.1   | 2021-11-22 | Bugfix  | Use integer field for condition.target_field                                                         |
| 8.2.0   | 2021-11-22 | Task    | Add mysql indices, hide children tables in list view, add code linting tests                         |
| 8.1.1   | 2021-08-04 | Task    | Simplify TCA to also fix the start/endtime bug in TYPO3 (last regression)                            |
| 8.1.0   | 2021-03-18 | Feature | Add TER autodeployment, add extension key to composer.json, small doc fix                            |
| 8.0.3   | 2020-04-30 | Bugfix  | Enforce content-type in TypoScript                                                                   |
| 8.0.2   | 2020-04-30 | Bugfix  | Also support checkboxes (array values) in powermail 8                                                |
| 8.0.1   | 2020-04-29 | Task    | Add useless dependency to TYPO3 for TER upload                                                       |
| 8.0.0   | 2020-04-29 | Task    | Update extension for powermail 8 and TYPO3 10.4                                                      |
| 7.0.0   | 2018-11-16 | Task    | Update dependencies for powermail 7                                                                  |
| 6.1.0   | 2018-10-21 | Task    | Remove deprecation warnings in TYPO3 9.5                                                             |
| 6.0.0   | 2018-10-16 | Task    | Support powermail 6.1                                                                                |
| 5.0.0   | 2018-05-24 | Task    | Support powermail 6.0                                                                                |
| 4.1.1   | 2018-03-28 | Bugfix  | Prevent exceptions in log                                                                            |
| 4.1.0   | 2018-01-29 | Task    | Remove dependencies for TYPO3 7.6                                                                    |
| 4.0.0   | 2018-01-15 | Task    | Update dependencies for powermail 5.0                                                                |
| 3.5.2   | 2017-12-05 | Bugfix  | Turn of ConditionAwareValitor on fields without page relations                                       |
| 3.5.1   | 2017-12-13 | Bugfix  | Prevent exceptions in backend for MySQL strict mode                                                  |
| 3.5.0   | 2017-11-13 | Task    | Update dependencies for powermail 4.x                                                                |
| 3.4.0   | 2017-08-14 | Feature | Allow all fieldtypes for target, add JS compression                                                  |
| 3.3.4   | 2017-07-25 | Bugfix  | Reduce unneeded calls in frontend                                                                    |
| 3.3.3   | 2017-06-12 | Bugfix  | Small change for MySQL strict mode                                                                   |
| 3.3.2   | 2017-05-20 | Bugfix  | TCA update for TYPO3 8.7                                                                             |
| 3.3.1   | 2017-04-25 | Bugfix  | Fix package name in composer.json                                                                    |
| 3.3.0   | 2017-04-23 | Task    | Move ext to github, make it fit for TYPO3 8.7 LTS                                                    |

## More to come soon?

- Use vanilla JS instead of jQuery

## Conflicts

- It's not possible to use powermail multistep forms with powermail_cond
