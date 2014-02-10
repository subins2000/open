=== Visual Editor Custom Buttons ===
Contributors: cyberduck
Donate link: 
Tags: visual editor, rich editor, tiny mce editor, buttons, custom, quicktag, html editor, tinymce, customize
Requires at least: 3.0
Tested up to: 3.8
Stable tag: 1.3.1
Version: 1.3.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Visual Editor Custom Buttons lets you add custom buttons to the Wordpress Visual Editor. 

== Description ==

Looking for a fast and easy way to add custom buttons to the WordPress Visual Editor? Visual Editor Custom Buttons is the answer. With this plugin, you can easily add your own custom buttons in the Visual Editor, as well as the HTML Editor. You can then add HTML code to the button, either as a wrap (before, after) or as a single block. On top of that you can, from within the plugin, set the CSS so you can view the effect of the button directly in the Visual Editor.

The plugin comes with a large number of ready to use button icons, but you can of course also add your own.

Visual Editor Custom Buttons. The perfect plugin for customizing the Visual Editor, add special features and simplify the content update process for the novice user.

== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Administer the buttons from the Admin Menu - "Visual Editor Custom Buttons".

*ATTENTION*

You may get an Error message right after installation. This will only appear once and will not be repeated. I havn´t found a solution yet to avoid it from showing up. Any tips on how to get rid of it is highly appreciated.
 
If your prior version was 1.0, backup own added custom icons before upgrading. They will be deleted.



== Frequently asked questions ==

= Where do I set which row my buttons will appear? =

That you can do under 'Settings' in the plugins menu.

= I have created a button but it is acting very strangely when trying to use it in the Visual Editor. Nothing happens when clicking on it in the Visual Editor. When trying in the Text Editor the code shows but can disappear again when flipping back and forth between the Editors. Why is this? =

This strange behavior with disappearing code may come up when you have pasted content to the Visual Editor from a pdf or word file and after that tries to use the Button. When pasting content directly from a pdf, unwanted invisible content may follow the pasted content messing up the Button code when trying to add it. The solution is to copy the pdf content to a simple text-editor (like Notepad) before copying it to the Visual Editor. When doing so, you clean the content before putting it into the Visual Editor.

About the Button CSS not visible in the Visual Editor (you can´t se any effect of the button in the Visual Editor). Cleaning the Browsers Cache should do the trick.




== Screenshots ==

1. Example of a Simple Button function. A Felt Marker. Just add a simple span-tag with a class. Then add the CSS to the class.
2. This is how the Felt Marker-button will work in the Visual Editor. To Get the same effect in Front End View, just add the same CSS to your normal CSS file.
3. Another button example. Four Image Slots. Same here, Add the code and the CSS.
4. The effect of the button in the Visual Editor. The dottered lines is for making the template boxes more clearly. Since you can have different CSS for backend and fronted you can simply remove the dottered border in the actual FrontEnd display.



== Changelog ==

= 1.3.1 =
* Visual adjustments for Wordpress version 3.8 and higher.
* Two new default icons:
  - Quote bubble
  - Inline header

= 1.3 =
* Upgrading issues causing buttons to disappear when upgrading are now fixed.
* It is now possible to change on which row your custom buttons will appear.

= 1.2.1 =
* Bug that made it impossible to open add media pop-up and other issues is now fixed. Thanks Scanomat for the tip.
* Bug causing broken Button Icon when using Single Block and Custom Icon is now fixed.

= 1.2 =
* Custom Buttons are now uploaded in Wordpress Upload Directory to prevent them from being deleted when upgrading the plugin.
* Custom Buttons are automatically added to the Button Icon Dropdown menu when uploaded. No need to specify the name of the icon. All added icons are available through the drop down menu.
* Seven new default icons. 
  - Width
  - Height
  - Bordered
  - Framed
  - Tab Space
  - Single line
  - Double line
* Minor Graphic and text changes

= 1.0 =
* Fixed bug that killed tags and post thumbnails in posts and pages. Thanks kevincrank and marcus.fridholm for your input. 
* Post-type labels corrected.

= 0.9.2.1 =
* Fixed upgrading issue.

= 0.9.2 =
* Fixed Resize Handles to only scale vertically
* Fixed Bug that automatically added css to Front End.

= 0.91 =
* Added Resize Handle to the Single Block Textarea




== Upgrade notice ==

*ATTENTION* 
If your prior version was 1.0, backup own added custom icons before upgrading. They will be deleted.



== Arbitrary section 1 ==

*ATTENTION* 
If your prior version was 1.0, backup own added custom icons before upgrading. They will be deleted.