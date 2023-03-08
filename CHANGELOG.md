# Changelog
## Unreleased
### Add
- Add add option to send the SID as userId when user not logged in
### Change
- Send userId inside "add to cart" tracking
- upgrade Web Components to 4.2.5

## [v4.4.0] - 2023.02.20
### Add
- Tracking
    - Add option to select scenario when `Add To Cart button` has been clicked
- Add second credentials used for fetching data from FF
### Change
- upgrade Web Components to 4.2.4

## [v4.3.7] - 2023.01.24
### Fix
- Fix add to cart tracking for configurable products
### Change
- Replace getRecords event with object served from backend

## [v4.3.6] - 2023.01.16
### Add
- Add option to switch between Api Version
### Fix
- Fix user login tracking event

## [v4.3.5] - 2022.12.12
### Fix
- Fix cart tracking

## [v4.3.4] - 2022.11.07
### Fix
- removed `query` and `cl=search_result` parameters from pages different than search result

## [v4.3.3] - 2022.10.27
### Change
- Upgrade Web Components version to v4.2.2
### Fix
- fix for new way of user login tracking event - fixed problem with missing cookie when external identity provider used

## [v4.3.2] - 2022.10.13
### Change
- Upgrade Web Components version to v4.2.1
- introduce new way of user login tracking event

## [v4.3.1] - 2022.09.16
### Add
 - add anonymize functionality that anonymize `user-id` parameter in any tracking request sent to FACT-Finder
### Fix
 - fixed tracking after variant change
 - passing proper number of products in the basket after checkout
 
## [v4.3.0] - 2022.07.11
### Add
 - add "continue on export product failure" functionality. All errors thrown during the export will be logged to separate file.
 
## [v4.2.2] - 2022.05.30
### Fix
 - define $oConfig variable if missing in `src/views/frontend/blocks/scripts.tpl` 
 - fix "Update FieldRoles" does not map some of the field roles in NG format to 7.3 which is used by the Web Components
   
### Change
 - upgrade Web Components to version [4.0.10](https://github.com/FACT-Finder-Web-Components/ff-web-components/releases/tag/4.0.10)
 
## [v4.2.1] - 2022.04.19
### Change
 - Replace `OxidEsales\Eshop\Application\Model\Article::getAttributes` implementation to improve performance and memory consumption 
 
## [v4.2.0] - 2022.03.21
### Add
 - add "Update FieldRoles" functionality that allows user to store Field Roles received from FACT-Finder search response in shop configuration
 - add proxy mechanism - more information in README file
 
### Change
 - category filter is hidden on the category page 
 
### Fix
 - fix scrollTop top page is executed on search immediate event
 - fix `src/Export/Field/FilterAttributes` exports attribute selection from variant product level

## [v4.1.2] - 2022.02.23
### Fix
 - fix `Omikron\FactFinder\Oxid\Model\Config\FtpParams` does not return stored configuration 
 
## [v4.1.1] - 2022.02.21
### Fix
 - fix category names were encoded twice in `ff-communication/category-page`

### Change
 - upgrade Web Components version to v4.0.8
 
## [v4.1.0] - 2022.02.11
### Add
- implement `category-page` attribute

### Change
- category path filter parameter is removed from `add-params` in NG 
- `navigation=true` parameter is removed from `add-params` in NG
- upgrade Web Components version to v4.0.7
- remove `disable-single-hit-redirect` from `Communication.php` This attribute no longer exist
 
### Fix
- fix missing use of RuntimeException in Communication.php
- restored setting `currency-country-code` in Communication.php
- restored version="v4" in Communication.php
- fix attributes names with " and ' in their names cause export field setting to break 
 
## [v4.0.2] - 2021.12.16
### Add
 - add scroll to top callback after products page change
 - send product title on cart tracking 
 - export new field `OXLONGDESC` as `LongDescription` in Category Export

### Change
- Upgrade Web Components version to v4.0.5

## [v4.0.1] - 2021.11.05
### Change
 Compatibility
  - Declare PHP8.0 compatibility

## [v4.0.0] - 2021.10.28
### Breaking
 - Drop php7.1 version support
 
### Add
 - Add possibility to upload feed files using SFTP 
 
## [v3.0.1] - 2021.10.08
### Fix
- Fix Configuration error for the channel field rendering/saving

## [v3.0.0] - 2021.09.23
### Breaking
- Omikron\FactFinder\Oxid\Export\Entity\DataProvider
  - changed from `public function __construct(FieldInterface ...$fields)` to `public function __construct(CollectionInterface $collection, FieldInterface ...$fields)`
### Add
- introduce new feed type: Category. More information in README.md
- add client side validation for FACT-Finder server URL

## [v2.0.1] - 2021.08.11
### Add
- introduced cart tracking using Web Components Javascript API
- introduced way of overwriting `currency-fields` in the `Model/Config/Communication.php` 

### Fix
- fix error with export handler which does not handle non-string values properly

## [v2.0.0] - 2021.07.15
### Breaking
- communication layer has been entirely replaced with (PHP Communication SDK)[https://github.com/FACT-Finder-Web-Components/php-communication-sdk].
- removed all interfaces and classes related to communication, which were used in previous version of module

### Add
- fields Authorization Prefix and Authorization Postfix are now getting disabled after selecting API version `ng` since they are not relevant for that version
- add `ff-campaign-feedbacktext` elements to search result page
 
### Fix
- Add missing `id-type` attributes to `ff-similar-products` and `ff-campaign-product` elements
- Fix redirection to search page loses search parameters when shop supports more than one language

### Change
- Upgrade Web Components version to v4.0.3

## [v1.0.5] - 2021.02.05
### Add
- Introduce `ff-campaign-redirect` component

### Change
- redirect to search result page using `before-search` event
- upgrade Web Components version to v3.15.12

## [v1.0.4] - 2020.12.14
### Change
- use `sid` generated by Web Components

### Fix
- don't add `Content-Type` header on NG POST requests with empty body
- search event is triggered twice on search result page

## [v1.0.3] - 2020.11.26
### Add
- exclude category filter from URL on category pages

### Change
- upgrade Web Components version to v3.15.9

### Fix
- enforce sid length to 30 chars

## [v1.0.2] - 2020.10.08
### Change
- refactor and simplify API communication, taking advantage of Guzzle features
- upgrade Web Components version to v3.15.8

### Fix
- fix NG support for category filter and push import API

## [v1.0.1] - 2020.09.29
### Fix
- remove Article class extension
- trim double slash from URL to prevent fatal error on request (e.g https://ng-demo.fact-finder.de/fact-finder//rest/v3/search/Bergfreunde-en)

### Add
- add module version to metadata file
- add missing ASN blocks with translations

## [v1.0.0] - 2020.09.14
Initial module release. Includes Web Components v3.15.6

[v4.4.0]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.4.0
[v4.3.7]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.3.7
[v4.3.6]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.3.6
[v4.3.5]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.3.5
[v4.3.4]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.3.4
[v4.3.3]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.3.3
[v4.3.2]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.3.2
[v4.3.1]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.3.1
[v4.3.0]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.3.0
[v4.2.2]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.2.2
[v4.2.1]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.2.1
[v4.2.0]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.2.0
[v4.1.2]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.1.2
[v4.1.1]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.1.1
[v4.1.0]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.1.0
[v4.0.2]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.0.2
[v4.0.1]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.0.1
[v4.0.0]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v4.0.0
[v3.0.1]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v3.0.1
[v3.0.0]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v3.0.0
[v2.0.1]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v2.0.1
[v2.0.0]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v2.0.0
[v1.0.5]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v1.0.5
[v1.0.4]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v1.0.4
[v1.0.3]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v1.0.3
[v1.0.2]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v1.0.2
[v1.0.0]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v1.0.0
[v1.0.1]:  https://github.com/FACT-Finder-Web-Components/oxid-eshop-module/releases/tag/v1.0.1
