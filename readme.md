# TYPO3 Extension powermail_cond

Conditions for TYPO3 extension powermail.
While a user fills out a form, some fields should be disappear, while
others should be visible.

## Screenshots

![Example form with conditions](Documentation/Images/screenshot_powermail_cond_frontend.png "Example form with conditions")

![Backend view to records](Documentation/Images/screenshot_powermail_cond_backend_records.png "Backend view to records")

![Backend view to records with rule](Documentation/Images/screenshot_powermail_cond_backend_records_conditionrule.png "Backend view to records with rule")


## Quick installation

Quick guide:
- Just install this extension - e.g. `composer require in2code/powermail_cond`
- Clear caches
- Add a powermail form to any page
- Add a new record from type "condition container" to a sysfolder and configure it
- Don't forget to include the static template from powermail_cond
- Don't forget to add jQuery to your frontend (if not yet installed)

## Changelog

| Version    | Date       | State      | Description                                                                  |
| ---------- | ---------- | ---------- | ---------------------------------------------------------------------------- |
| 8.0.2      | 2020-04-30 | Bugfix     | Also support checkboxes (array values) in powermail 8                        |
| 8.0.1      | 2020-04-29 | Task       | Add useless dependency to TYPO3 for TER upload                               |
| 8.0.0      | 2020-04-29 | Task       | Update extension for powermail 8 and TYPO3 10.4                              |
| 7.0.0      | 2018-11-16 | Task       | Update dependencies for powermail 7                                          |
| 6.1.0      | 2018-10-21 | Task       | Remove deprecation warnings in TYPO3 9.5                                     |
| 6.0.0      | 2018-10-16 | Task       | Support powermail 6.1                                                        |
| 5.0.0      | 2018-05-24 | Task       | Support powermail 6.0                                                        |
| 4.1.1      | 2018-03-28 | Bugfix     | Prevent exceptions in log                                                    |
| 4.1.0      | 2018-01-29 | Task       | Remove dependencies for TYPO3 7.6                                            |
| 4.0.0      | 2018-01-15 | Task       | Update dependencies for powermail 5.0                                        |
| 3.5.2      | 2017-12-05 | Bugfix     | Turn of ConditionAwareValitor on fields without page relations               |
| 3.5.1      | 2017-12-13 | Bugfix     | Prevent exceptions in backend for MySQL strict mode                          |
| 3.5.0      | 2017-11-13 | Task       | Update dependencies for powermail 4.x                                        |
| 3.4.0      | 2017-08-14 | Feature    | Allow all fieldtypes for target, add JS compression                          |
| 3.3.4      | 2017-07-25 | Bugfix     | Reduce unneeded calls in frontend                                            |
| 3.3.3      | 2017-06-12 | Bugfix     | Small change for MySQL strict mode                                           |
| 3.3.2      | 2017-05-20 | Bugfix     | TCA update for TYPO3 8.7                                                     |
| 3.3.1      | 2017-04-25 | Bugfix     | Fix package name in composer.json                                            |
| 3.3.0      | 2017-04-23 | Task       | Move ext to github, make it fit for TYPO3 8.7 LTS                            |

## More to come soon?

- Use vanilla JS instead of jQuery

## Conflicts

- It's not possible to use powermail multistep forms with powermail_cond
