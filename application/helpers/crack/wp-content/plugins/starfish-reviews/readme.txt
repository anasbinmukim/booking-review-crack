=== Starfish Reviews ===
Contributors: starfishwp, anasbinmukim, thefiddler
Donate link: https://starfish.reviews
Tags: reputation management,reviews,ratings,5-star reviews,
Requires at least: 4.8
Tested up to: 5.0
Stable tag: 1.5.1
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Encourage your customers to leave 5-star reviews on Google, Facebook, Yellow Pages, and more. Starfish Reviews is the #1 reputation management plugin for WordPress!

== Description ==

= Reputation Management for Everyone! =

_Starfish Reviews_ enables reputation management campaigns on your WordPress website. Encourage positive, 5-star ratings and reviews while capturing negative reviews for internal improvement. Or run campaigns for your clients via their website(s) or your own.

Send the Starfish "funnel" URL to your customers, users, followers, audience, etc and ask for feedback. The Starfish Reviews "funnel" will ask users how they feel about your site. If they like it, they'll be sent to whatever URL you set in the settings. This should be a link to the reviews section of wherever you want to get 5-star reviews. It could be your Google My Business listing, Facebook Page, Yelp listing, TripAdvisor page, podcast on iTunes, a digital product on an online marketplace, or a book on Amazon. Get 5-star reviews at any of those locations and many more! Anywhere that takes online reviews.

If people are not so happy with your product, service, media, etc, they'll be asked to provide feedback directly to you. This helps offset people's natural tendency to only post reviews when they're upset, or when they've totally misunderstood the purpose of the review. You'll still learn what you can do to improve, but it won't be published permanently for the whole world to see.

== Installation ==

[See Starfish Documentation](https://docs.starfish.reviews/article/4-how-to-install-starfish-reviews)

== Frequently Asked Questions ==

= Can I use this to encourage reviews on ______ website? =
Yes, you can send people to whatever URL you want. So as long as the destination is 1. a website, and 2. publicly linkable, you can send people there. Note: some sites may make it more difficult than others to actually leave a review, and most require an account (eg. Google for Google My Business, Amazon for product reviews, etc).

= Could you add _____ feature? =
We believe in LEAN Startup method. So if you ask, and so do a number of others of our members or potential members, it's likely we'll add it, as long as it fits with our core vision and goals.


== Changelog ==

= 1.6 – 2018-08-10 =
* Enhancements
    * Integrate Help Scout's Beacon 2.0, so you can now look at our growing documentation and open a ticket, from the button floating in the bottom-left corner of all Starfish Reviews settings screens
    * Removed "Contact Us" page since you can now do it through Beacon.
* Fixes
    * Updated to the latest version of the Freemius SDK.

= 1.5.2 – 2018-07-11 =
* Enhancements
    * Hide the license key fields after activation. This way clients can't find your license key.
    * Added German (thanks Dennis Klinger) translation.
    * Added .mo translation files for all included languages.
* Fixes
    * Correct filename formats for translation .po files. Included translations work now!

= 1.5.1 – 2018-06-08 =
* Fixes
    * Small fixing in Funnel Edit page Single and Multiple Destination fields toggle.

= 1.5.0 – 2018-06-07 =
* Enhancements
    * Multiple destinations! You can now add multiple destinations to each funnel. Select an icon, color options, and a name. Also select your own icon.
    * Prevent review "gating" to comply with Google's new policy. You can now present the destination after users have provided negative feedback so they can leave a public review.
    * Added French and Tagolog translations. Thanks to Eric Gracieta (French), Charry Mae Yanex (Tagolog), and Hannu Jaatinen (Finnish).
* Fixes
    * 100% now displays in a more readable way inside the graph.
    * Updates to change "Yes" and "No" wording to "Positive" and "Negative" respectively. Makes it easier for translation and consistency.
    * Set font weight to bold on buttons so button text is readable on themes with skinny fonts.
    * Unescape special characters in email subject and "From" name so they stay special characters instead of being converted to HTML code equivalents.

= 1.4.2 – 2018-05-13 =
* Fixes
    * Removed Freemius' Pricing option from sidebar.

= 1.4.1 – 2018-05-11 =
* Fixes
    * Fixed issue with license key field not allowing old WooCommerce API-based licenses to be input properly.

= 1.4.0 – 2018-05-08 =
* Enhancements
    * Integrated with Freemius. This new licensing and selling platform will help us innovate and add new features much more quickly going forward.
    * Added default WP body classes and ID's added to Funnel pages so you can target funnels generally or individual funnels in your CSS.
    * All functionality is disabled when the license is disabled, so you can now remotely remove a client's access if they cancel their agreement with you.
* Fixes
    * Removed body CSS being applied when using the shortcode.


= 1.3.0 – 2018-04-12 =

* Enhancements
	* New positive and negative icon combinations: 4 options with Font Awesome 5 icons.
	* Ask for name, email, or phone number and toggle whether they’re required, in the negative response form.
	* Set the text of the submit buttons on the positive and negative responses, individually.
	* New “Funnel Logo” setting to display a logo above the funnel.

* Fixes
	* jQery error when using shortcode in certain situations.
	* Force white background so theme doesn’t override it.
	* Message about upgrading from Business to Webmaster appearing in the wrong situation.
	* Misc typo’s and wording errors.

= 1.2.1 – 2018-03-14 =

* Fixes
	* Include .po and .mo files for translation

= 1.2 – 2018-03-06 =

* Enhancements
	* Auto-forward to destination URL after X number of seconds
	* Allow affiliates to put in their Affiliate ID to change "Powered by Starfish" to link with their affiliate link.
	* New {review-id} shortcode to include the ID variable in emails.
	* Set a reply-to email address.
	* Set reply-to email address to the ID, if the ID is a properly formatted email address. Allows you to reply directly to the review submitter on negative emails.
	* Compatibility added to support the Lite (free on WP.org) edition. If you use that plugin, then switch to Business or Webmaster, all reviews and funnel data will be kept.
	* Matched colors of Reviews stats and chart to Starfish Colors.
	* Added this readme.txt to the plugin.
	* "Delete all settings on deactivation" checkbox option, to completely remove all Starfish Reviews data from your database when you deactivate the plugin.


* Fixes
	* API Key & Email obscured in Settings>Starfish License.
	* Some coding best practices implemented for security and efficiency.
	* Reviews stats now have styling before the first review is submitted.
	* Negative review now requires at least 5 words to be completed. No more blank submissions.
	* Various wording updates for clarity.
	* Removed files generated by macOS from ZIP file.
	* Made {funnel-name} work in the message body.
	* Line break are no longer ignored in email template body.

= 1.1 – 2017-11-22 =

* Fix: admin ratings ranges.
* Updated: thank you message default wording.

= 1.0 – 2017-10-24 =

* Initial development.
