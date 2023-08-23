# TYPO3 Extension powermail_cond - Documentation for developers

If you want to contribute to the TYPO3 extension powermail_cond, you are very welcome.

To make it easier to contribute, there is a ddev based installation, with a complete TYPO3 setup and test data ready to
use.

## DDEV-based environment
### Prerequisites

- Docker installed https://docs.docker.com/get-docker/
- ddev installed https://ddev.readthedocs.io/en/stable/

### Project setup

- open a console in the project root
- run `ddev start`
- run `ddev initialize`

Now you will be able to work with the website

Frontend: https://powermailcond.ddev.site,/ \
Backend: https://powermailcond.ddev.site/typo3


Username: admin \
Password: password

### Example use cases

There are some example use cases in the test page tree (below page EXT:powermail_cond) which you can use to test

![Test pages for EXT:powermail_cond](Documentation/Images/screenshot_powermail_cond_page_tree.png)

#### Use case 1: Hide field if another field is filled

Hide email if field tel is filled, hide tel if field email is filled, disable validation for these fields

Hide Field (Email OR Tel) (page 52)
  * HTML5,JS,PHP (all three types of validation are activated in TS) (page 170)
  * JS,PHP (only JS and PHP validation are activated in TS) (page 173)
  * HTML5 (only HTML5 validation is activated in TS) (page 171)
  * HTML5,JS (only HTML5 and JS validation are activated in TS) (page 172)
  * PHP (only PHP validation is activated in TS) (page 174)
  *
#### Use case 2: Unhide hidden field only if a box is checked

Show Fieldset Invoice Address only if checkbox is checked (page 136)

#### Use case 3: Show field if there is a value in another field

Show field 'first_name' only if email is filled (page 178)

#### Use case 4: Show field only if a certain value is selected in another field

Hide textarea if "Red" in Selectfield (page 224)

#### Use case 5: Show checkboxes only if a certain value is selected in another field (page 181)

Show list of checkboxes only if second option is chosen in radio button

#### Use case 6: Hide all other fields if there is an input in another field (page 253)

Hide all other fields on page `Target: All fields` if a date is filled in field `Start date`


