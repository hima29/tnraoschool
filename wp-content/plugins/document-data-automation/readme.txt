=== Document & Data Automation ===
Tags: Docxpresso, documents, data, forms, online documents
Requires at least: 3.5
Tested up to: 5.0.3
Stable tag: trunk
Contributors: No-nonsense Labs
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Generate dynamical documents and contracts from user input and Office templates

== Description ==

If you need to gather end user information, generate dynamical documents, manage all collected data
or even sign contracts directly from your Wordpress website this is the tool you are looking for.

This plugin is a Wordpress interface for our Docxpresso cloud service ([Get a free trial](http://saas.docxpresso.net "Generate and manage all your documents in the cloud")). It allows
for the integration of full front end Docxpresso functionality within your Wordpress website.

Just by uploading a document template to Docxpresso you will convert a simple Word document
into a fullfledged app that will allow you to:
* Generate online interactive documents with "active zones" that may be edited directly from the browser.
* Generate sophisticated web forms directly tailored from the uploaded document.
* Generate dynamical documents in any format (PDF, Word, ODT or RTF) using your end user input data and the original Word template.
* Manage all collected data and export it to HTML and Excel formats.
* Anchor inmutable proof of your documents to the Bitcoin blockchain.
* Sign documents & contracts from your WordPress interface.

Our "Document & Data Automation" plugin will convert your WordPress website in a sophisticated document management and
document automation system that will grow with your business.


= How to use it =

Publish an interactive document or web form:

1. Create or edit a post or page as usual from your WordPress admin interface.
2. Click the **Docxpresso SaaS** button located over the text editor.
3. A pop up will open with the a full directory tree of all the documet templates available in your Docxpresso cloud installation.
4. Click on the folder of your choice and a pop up will open with the list of **available document templates**.
5. Choose insert into Wordpress if you want the document/form to be embedded within your post/page or print link if you just want to forward your visitors to your Docxpresso installation.
6. Navigate through the list and choose the desired template by clicking on the **Insert** button (for interactive documents) or **Web Form** button (for an standard web form).
7. A shortcode [docxpresso_document] will be inserted with the selected options.
8. Write any additional content if needed and save the post/page

https://youtu.be/5i631JIesiE

Browse generated document and data directly from your Wordpress interface:

1. Click on the **Docxpreso SaaS** button of your main Wordpress admin menu.
2. Choose the **Templates** option.
3. You will be redirected to the **Data Management** interface where you will be able to filter by name and category the template data and generated documents that you wish to browse.
4. Click on the name of the desired template and you will be redirected to a page with all the **uses** of that template.
5. You may, if you wish, filter the data by dates or other associated fields.
6. You then may access **bulk data** in CSV format or access the data for a single use by clicking in the corresponding button.
7. You may also, at any time, download the associated documents by clicking on the **Download** button located at the right of each usage.

Of course, you may also access all this data and documents from your Docxpresso installation backoffice interface where you can enjoy more sophisticated filtering options and many other goodies.

https://youtu.be/42acmu6dko8

You may also learn how to anchor the generated documents to the Bitcoin blockchain with the following video:

https://youtu.be/mJWDajyovs4

== Installation ==

In order to use this plugin you need an active Docxpresso SaaS account. You may ask for a free trial in: http://saas.docxpresso.net

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'.
2. Search for **Document & Data Automation**.
3. Activate Docxpresso integration from your Plugins page.

= From WordPress.org =

1. Download Docxpresso.
2. Upload the 'Document & Data Automation' directory to your '/wp-content/plugins/' directory.
3. Activate Docxpresso integration from your Plugins page.

= Settings =

Once you have activated the plugin you should click on the "Docxpresso SaaS" > Options link available from your main
WP admin menu and provide the following data:

Main configuration options:
* The URL to your private Docxpresso installation
* The Docxpresso API key
* The email of the ADMIN Docxpresso user

General options:
* Default redirection page
* Default thanks message

Styles:
* Document frame styles (border)
* Thanks message styles (border, background-color and font options)

https://youtu.be/B8og79FltDE

== Frequently Asked Questions ==

= May I use this plugin in any website, commercial or not? =
Yes, there are no limits to that regard.
= May I ask for support? =
Yes, you may write in the forum or leave a message in [Docxpresso](http://www.docxpresso.com/contact "Generate dynamically all your documents online").

== Screenshots ==

1. **Docxpresso SaaS button: classic editor** - click on this button to insert a document into your WP post/page.
2. **Docxpresso SaaS button: block editor** - click on this widget to insert a document block into your WP post/page.
3. **Choose a template: latest templates** - list of the most recent Docxpresso templates.
4. **Choose a template: directory tree** - navigate through all Docxpresso available templates.
5. **Choose a template: display options** - configure how the document/web form will be displayed in the page.
6. **End user interface: interactive document** - the end user will be offered an interactive document to be completed.
7. **End user interface: web form** - if the web form option is selected the end user will be offered a standard web form.
8. **Browse data and download documents** - from the admin interface one may access all data gathered from end users as well as all documents generated.
9. **Browse single document template usage history** - you may access bulk data (CSV) for a single template or browse the data and download the document associated to a single use.



== Changelog ==

= 1.2 =

* Completely refurbished user interface
* Anchoring to the blockchain
* New filtering options for templates and associated data and documents
* Seamless integration with the Docxpresso SaaS interface

= 1.1 =

* Minor corrections

= 1.0 =

* Initial version

== Licensing ==

This work is licensed under GPLv2 or later.

This plugin comes bundled with:
* iFrameResizer JavaScript library (https://github.com/davidjbradshaw/iframe-resizer) 
* Peity jQuery plugin (http://benpickles.github.io/peity)
* jquery.mask.js JQuery plugin (http://blog.igorescobar.com) 
* sha.js (http://caligatio.github.com/jsSHA/) 
All of them enjoying a MIT license (http://www.opensource.org/licenses/mit-license.php) but sha.js that enjoys a BSD license (https://opensource.org/licenses/BSD-3-Clause)
