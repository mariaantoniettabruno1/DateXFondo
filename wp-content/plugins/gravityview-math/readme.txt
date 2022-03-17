=== GravityView Math ===
Tags: gravityview, math
Requires at least: 4.4
Tested up to: 5.8
Requires PHP: 7.1
Stable tag: 2.0.4
Contributors: gravityview
License: GPL 2 or higher

Calculations. Uses the [Hoa Math](https://github.com/hoaproject/Math) library.

== Installation ==

1. Upload plugin files to your plugins folder, or install using WordPress' built-in Add New Plugin installer
2. Activate the plugin
3. Follow the instructions on [the Math by GravityView documentation page](http://docs.gravityview.co/category/370-math-by-gravityview)

== Changelog ==

= 2.0.4 on October 25, 2021  =

* Fixed: Filters using CONTAINS or NOTCONTAINS operators would not work
* Fixed: Filters would not work with multi-input fields unless the exact input is specified

= 2.0.3 on June 9, 2021 =

* Fixed: Fatal error if performing calculations using Gravity Forms data, using the shortcode's `filter` attribute, and GravityView is not installed
* Fixed: Filter conditions using fields with multiple input did not work

= 2.0.2 on May 13, 2021 =

* Fixed: Incorrect formatting of calculation results with certain locales
* Added: Option to specify the number of decimal places for footer calculations

= 2.0.1 on March 8, 2021 =

* Fixed: PHP fatal error when the plugin is used without GravityView
* Added: Footer calculations for Custom Content fields

= 2.0 on February 25, 2021 =

* Added: Easily add footer calculations to your GravityView Table and DataTables layouts! [Learn how to add calculations](https://docs.gravityview.co/article/750-how-to-add-field-calculations-to-the-table-footer).
    - Effortlessly calculate field values
    - Quiz: # Passed, # Failed, % Passed, % Failed, Average Score
    - High, low, average, sum, checked
    - Count of checked and unchecked checkboxes, radio inputs, consent fields
    - Time duration: Fastest, slowest, average time
* Added: Perform duration calculations, great for races and time sheets! [See a video for setting up duration calculations](https://docs.gravityview.co/article/756-gravity-forms-duration-calculations).
* Added: Filter support for `scope="visible"`. [Read more about filters](https://docs.gravityview.co/article/295-math-shortcode#filters).
* Added: An `{entry_count}` Merge Tag you can use inside math formula as well as throughout Gravity Forms and GravityView! [Read about the `{entry_count}` Merge Tag](https://docs.gravityview.co/article/754-entry-count-merge-tag)
* Improved: Execution time when a Math filter is used on large data sets
* Fixed: Incorrect results returned by `min`/`max`/`avg`/`sum` operations
* Fixed: Calculations on non-numeric fields (e.g., product price) would not work in View widget areas
* Fixed: Only using one page of results were used when calculating `scope="view"`
* Logging improvements!
    * New: Not sure why your Math formula isn't working, but you don't want to modify the shortcode? Administrators can now debug Math formulae from the front-end by adding `?gv_math_debug=true` to the URL.
    * Added: Better debugging information is now shown, including the contents of the formula before and after it was processed.
    * Improved: Notices are now grouped for each shortcode, making it easy to debug.
    * Fixed: "Additional info" shown when debugging results not displaying when clicked.
    * Fixed: Math by GravityView wasn't showing in the [Gravity Forms "Logging" screen](https://docs.gravityforms.com/logging-and-debugging/#viewing-logs).
    * Fixed: Display user notices only when `notices` shortcode attribute is set.

= 1.3.2 on April 6, 2020 =

* Fixed: If using `scope=form` as well as a filter, only the first 20 entries in the form were used in calculations

= 1.3.1 on March 5, 2020 =

* Fixed: Shortcode not working when used inside GravityView DataTables layouts
* Updated: French translation

= 1.3 on January 29, 2020 =

* Fixed: The `[gvmath]` shortcode not working properly inside GravityView `[gvlogic]` shortcodes
* Fixed: Potential errors when editing content in Gutenberg editor
* Fixed: Potential error when using Math while running Gravity Forms < 2.3 and GravityView < 2.5
* Fixed: Properly handles dates passed via Merge Tags
* Added: Support for Gravity Forms Merge Tags in the `filter` attribute (when using `scope="form"`)

_Developer Updates:_

* Added: `gravityview/math/debug` filter to modify whether debugging is on or off. Return `false` to disable debugging. Note: Viewing debugging requires `edit_others_posts` capability.

= 1.2 on May 10, 2019 =

* **The plugin now requires PHP 7.1**
* Added: Filtering on `form` and `view` scopes. This will filter the entries in the scope by the specified values. [Read more about filtering](https://docs.gravityview.co/article/295-math-shortcode#scope-filtering).
* Fixed: `SQRT_PI`, `SQRT_{Number}` functionality
* Added: Russian Translation (thanks, Viktor S) and Ukrainian translation (thanks, Dariusz Zielonka!)
* Updated: Chinese translation (thanks, Edi Weigh!)

= 1.1.1 on May 8, 2018 =

* Fixed: Aggregate form data not calculating with Gravity Forms 2.3
* Fixed: `.mo` translation files weren't being generated
* Updated: Dutch, Turkish, and Spanish translations (thanks, jplobaton, SilverXp, and suhakaralar!)

= 1.1 on April 28, 2018 =

* Fixed: Compatibility with Gravity Forms 2.3
* Updated: Dutch and Turkish translations

= 1.0.3 on May 24, 2017 =

* Fixed: Don't link to entry in debug mode if the entry doesn't exist 👻
* Fixed: Incorrect argument passed to Gravity Forms Add-On registration function
* Fixed: Compatibility issue with the (excellent) [Gravity Forms Utility plugin](https://gravityplus.pro/gravity-forms-utility/) - thanks, Naomi C Bush!

= 1.0.2 on December 15 =

* Fixed PHP error when there are no values to calculate
* Updated German translation (Thank you, Hubert Test!)

= 1.0.1 on September 14 =

* Fix potential error blocking activation

= 1.0 =

* Launch!


= 1637315300-11203 =