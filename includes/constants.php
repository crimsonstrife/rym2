<?php

/**
 * define the job types for labels
 */
define('JOBTYPES', array(
    array("value" => "FULL", "label" => "Full Time"),
    array("value" => "PART", "label" => "Part Time"),
    array("value" => "INTERN", "label" => "Internship")
));

/**
 * Define constant for date formats
 */
define('DATE_FORMAT', 'm/d/Y');
define('TIME_FORMAT', 'h:i A');

/**
 * define the States array
 */
define('STATES', array(
    array(
        "value" => "AL",
        "label" => "Alabama"
    ),
    array(
        "value" => "AK",
        "label" => "Alaska"
    ),
    array(
        "value" => "AZ",
        "label" => "Arizona"
    ),
    array(
        "value" => "AR",
        "label" => "Arkansas"
    ),
    array(
        "value" => "CA",
        "label" => "California"
    ),
    array(
        "value" => "CO",
        "label" => "Colorado"
    ),
    array(
        "value" => "CT",
        "label" => "Connecticut"
    ),
    array(
        "value" => "DE",
        "label" => "Delaware"
    ),
    array(
        "value" => "DC",
        "label" => "District Of Columbia"
    ),
    array(
        "value" => "FL",
        "label" => "Florida"
    ),
    array(
        "value" => "GA",
        "label" => "Georgia"
    ),
    array(
        "value" => "HI",
        "label" => "Hawaii"
    ),
    array(
        "value" => "ID",
        "label" => "Idaho"
    ),
    array(
        "value" => "IL",
        "label" => "Illinois"
    ),
    array(
        "value" => "IN",
        "label" => "Indiana"
    ),
    array(
        "value" => "IA",
        "label" => "Iowa"
    ),
    array(
        "value" => "KS",
        "label" => "Kansas"
    ),
    array(
        "value" => "KY",
        "label" => "Kentucky"
    ),
    array(
        "value" => "LA",
        "label" => "Louisiana"
    ),
    array(
        "value" => "ME",
        "label" => "Maine"
    ),
    array(
        "value" => "MD",
        "label" => "Maryland"
    ),
    array(
        "value" => "MA",
        "label" => "Massachusetts"
    ),
    array(
        "value" => "MI",
        "label" => "Michigan"
    ),
    array(
        "value" => "MN",
        "label" => "Minnesota"
    ),
    array(
        "value" => "MS",
        "label" => "Mississippi"
    ),
    array(
        "value" => "MO",
        "label" => "Missouri"
    ),
    array(
        "value" => "MT",
        "label" => "Montana"
    ),
    array(
        "value" => "NE",
        "label" => "Nebraska"
    ),
    array(
        "value" => "NV",
        "label" => "Nevada"
    ),
    array(
        "value" => "NH",
        "label" => "New Hampshire"
    ),
    array(
        "value" => "NJ",
        "label" => "New Jersey"
    ),
    array(
        "value" => "NM",
        "label" => "New Mexico"
    ),
    array(
        "value" => "NY",
        "label" => "New York"
    ),
    array(
        "value" => "NC",
        "label" => "North Carolina"
    ),
    array(
        "value" => "ND",
        "label" => "North Dakota"
    ),
    array(
        "value" => "OH",
        "label" => "Ohio"
    ),
    array(
        "value" => "OK",
        "label" => "Oklahoma"
    ),
    array(
        "value" => "OR",
        "label" => "Oregon"
    ),
    array(
        "value" => "PA",
        "label" => "Pennsylvania"
    ),
    array(
        "value" => "RI",
        "label" => "Rhode Island"
    ),
    array(
        "value" => "SC",
        "label" => "South Carolina"
    ),
    array(
        "value" => "SD",
        "label" => "South Dakota"
    ),
    array(
        "value" => "TN",
        "label" => "Tennessee"
    ),
    array(
        "value" => "TX",
        "label" => "Texas"
    ),
    array(
        "value" => "UT",
        "label" => "Utah"
    ),
    array(
        "value" => "VT",
        "label" => "Vermont"
    ),
    array(
        "value" => "VA",
        "label" => "Virginia"
    ),
    array(
        "value" => "WA",
        "label" => "Washington"
    ),
    array(
        "value" => "WV",
        "label" => "West Virginia"
    ),
    array(
        "value" => "WI",
        "label" => "Wisconsin"
    ),
    array(
        "value" => "WY",
        "label" => "Wyoming"
    )
));

/**
 * Define the MAILER enum constants for the mail settings
 */
define('MAILER', array(
    array(
        "value" => "smtp",
        "label" => "SMTP"
    )
));

/**
 * Define the MAILER_ENCRYPTION enum constants for the mail settings
 */
define('MAILER_ENCRYPTION', array(
    array(
        "value" => "ssl",
        "label" => "SSL"
    ),
    array(
        "value" => "tls",
        "label" => "TLS"
    )
));

/**
 * Define the placeholder markdown for the privacy policy
 *
 */
define('PRIVACY_POLICY', '# Privacy Policy

[["We" or "I", or Website or App name]] takes your privacy seriously. To better protect your privacy [["we" or "I"]] provide this privacy policy notice explaining the way your personal information is collected and used.


## Collection of Routine Information

This [["website" or "app"]] track basic information about their [["visitors" or "users"]]. This information includes, but is not limited to, IP addresses, [["browser" or "app"]] details, timestamps and referring pages. None of this information can personally identify specific [["visitors" or "user"]] to this [["website" or "app"]]. The information is tracked for routine administration and maintenance purposes.


## Cookies

Where necessary, this [["website" or "app"]] uses cookies to store information about a visitor’s preferences and history in order to better serve the [["visitor" or "user"]] and/or present the [["visitor" or "user"]] with customized content.


## Advertisement and Other Third Parties

Advertising partners and other third parties may use cookies, scripts and/or web beacons to track [["visitors" or "user"]] activities on this [["website" or "app"]] in order to display advertisements and other useful information. Such tracking is done directly by the third parties through their own servers and is subject to their own privacy policies. This [["website" or "app"]] has no access or control over these cookies, scripts and/or web beacons that may be used by third parties. Learn how to [opt out of Google’s cookie usage](http://www.google.com/privacy_ads.html).


## Links to Third Party Websites

[["We" or "I"]] have included links on this [["website" or "app"]] for your use and reference. [["We" or "I"]] are not responsible for the privacy policies on these websites. You should be aware that the privacy policies of these websites may differ from [["our" or "my"]] own.


## Security

The security of your personal information is important to [["us" or "me"]], but remember that no method of transmission over the Internet, or method of electronic storage, is 100% secure. While [["we" or "I"]] strive to use commercially acceptable means to protect your personal information, [["we" or "I"]] cannot guarantee its absolute security.


## Changes To This Privacy Policy

This Privacy Policy is effective as of [[Date]] and will remain in effect except with respect to any changes in its provisions in the future, which will be in effect immediately after being posted on this page.

[["We" or "I"]] reserve the right to update or change [["our" or "my"]] Privacy Policy at any time and you should check this Privacy Policy periodically. If [["we" or "I"]] make any material changes to this Privacy Policy, [["we" or "I"]] will notify you either through the email address you have provided [["us" or "me"]], or by placing a prominent notice on [["our" or "my"]] [["website" or "app"]].


## Contact Information

For any questions or concerns regarding the privacy policy, please send [["us" or "me"]] an email to [[Contact Email Address]].');

/**
 * Define the placeholder markdown for the terms of service
 *
 */
define('TERMS_CONDITIONS', '# Terms and Conditions

Welcome to [["website" or "app"]]! These terms and conditions outline the rules and regulations for the use of [["website" or "app"]]’s Website, located at [["website" or "app URL"]].

By accessing this website we assume you accept these terms and conditions. Do not continue to use [["website" or "app"]] if you do not agree to take all of the terms and conditions stated on this page.');

/**
 * Error Message Constants
 *
 */
define('PERMISSION_ERROR_ACCESS', array(
    "code" => "LYNX",
    "message" => "You do not have permission to access this content, contact the Administrator."
));
define('CONFIGURATION_ERROR', array(
    "code" => "CHAMELEON",
    "message" => "There is a configuration error, contact the Administrator."
));
define('INVALID_REQUEST_ERROR', array(
    "code" => "CIPHER",
    "message" => "Invalid request, contact the Administrator."
));
define('ROUTING_ERROR', array(
    "code" => "LIGHTHOUSE",
    "message" => "Navigation or routing error, contact the Administrator."
));
define('DATABASE_ERROR', array(
    "code" => "VAULT",
    "message" => "Database error, contact the Administrator."
));
define('AUTHENTICATION_ERROR', array(
    "code" => "SENTINEL",
    "message" => "Authentication error, contact the Administrator."
));
define('AUTHORIZATION_ERROR', array(
    "code" => "GUARDIAN",
    "message" => "Authorization error, contact the Administrator."
));
define('VALIDATION_ERROR', array(
    "code" => "ZEPHYR",
    "message" => "Validation error, contact the Administrator."
));
define('DEFAULT_ERROR', array(
    "code" => "MYSTIC",
    "message" => "An error has occurred, contact the Administrator."
));
define('SUDDEN_ERROR', array(
    "code" => "COBRA",
    "message" => "An error has occurred, contact the Administrator."
));
define('TIMEOUT', array(
    "code" => "TURTLE",
    "message" => "The request has timed out, contact the Administrator."
));
define('RESTART', array(
    "code" => "PHOENIX",
    "message" => "There may have been a server restart or recovery, contact the Administrator."
));
define('NOT_FOUND', array(
    "code" => "SASQUATCH",
    "message" => "The requested content was not found, contact the Administrator."
));
define('NOT_IMPLEMENTED', array(
    "code" => "YETI",
    "message" => "The requested content is not yet implemented, contact the Administrator."
));
define('SERVICE_UNAVAILABLE', array(
    "code" => "COMPASS",
    "message" => "The requested content is not available, contact the Administrator."
));
define('CRITICAL', array(
    "code" => "MONOLITH",
    "message" => "A critical error has occurred, contact the Administrator."
));
define('DASHBOARD_PERMISSION_ERROR', array(
    "code" => "TELESCOPE",
    "message" => "You do not have permission to access the dashboard, contact the Administrator."
));
define('INVALID_USER_REQUEST', array(
    "code" => "SPECTER",
    "message" => "Invalid request, contact the Administrator."
));
