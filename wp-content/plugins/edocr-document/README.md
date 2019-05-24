# edocr Document Viewer

The edocr Document Viewer is a document viewer that enables you to easily embed documents on your website. The edocr Document Viewer is a HTML5 document viewer that enables you to display dozens of different document and image file types on your website without worrying about whether your visitors have the software to view them and  without having to convert your files to another format and risk degrading their quality. edocr Document Viewer supports dozens of file types, including DOC, PDF, PPT, XLS, CAD, GoogleDocs, and more.

Try it today for free.



## Description

This plugin adds a shortcode which alows you to embed documents on your page or post that are hosted on edocr.com. For example, if you have a document with the following URL:

```
https://www.edocr.com/v/aqegexna/edocr-service-agreement
```

You'll need to find the documents unique identifier. It's the 8-letter part of the URL directly after the '/v/'. In this example, the unique document identifier (or GUID) is:

```
aqegexna
```

Now simply take your document GUID and use it with our shortcode to add a document to your post or page like this:

```
[edocr guid=aqegexna]
```

Once you save and view your post, you should now have an embedded instance of the document on your WordPress site. You can embed any unrestricted edocr document on the site.



## Usage

Once the edocr Document Viewer is installed on your WordPress site, you'll need to find an edocr document GUID for an existing document to embed from the [edocr website](https://www.edocr.com/). You can use the edocr [search](https://www.edocr.com/search) functionality to find existing documents. For existing documents published on edocr, no further action is needed to get started. 

To embed new documents, simply [sign up](https://www.edocr.com/account/create) for a free edocr account. This will allow you to publish your documents on [edocr.com](https://www.edocr.com) and then embed them on your WordPress site using the shortcode and the document GUID.



## Shortcode Options

**guid=abcdefgh**

- REQUIRED - This is the only required option. It specifies which unique edocr document you'd like to embed

Example:

```
[edocr guid=aqegexna]
```

**type=(legacy/viewer/thumbnail)**

- viewer - This option embeds an iframe with a default size of 300 x 300 pixels
- thumbnail - this option embeds an image thumbnail of the specified document inside of an image tag

Example:

```
[edocr guid=aqegexna type=viewer]
[edocr guid=aqegexna type=thumbnail]
```

**autofit=1**

- (Can be used with viewer or thumbnail type)
- This option looks at the dimensions of the full-size document thumbnail and sizes the viewer or image accordingly

Example:

```
[edocr guid=aqegexna type=viewer autofit=1]
[edocr guid=aqegexna type=thumbnail autofit=1]
```

**width=x**
**height=y**

- Allows you to explicitly specify the height and width of the iframe or image tag
- Can be used with type=viewer or type=thumbnail
- Both values must be specified
- Units for values are pixels
- Cannot be used with autofit=1

Example:

```
[edocr guid=aqegexna type=viewer width=640 height=480]
```

**option=(profile/collection)**

- (Can only be used with type = viewer)
- profile - Adds a link after the iframe that allows viewers to subscribe to the edocr profile of the document publisher
- collection - Adds a link after the iframe that allows viewers to subscribe to the edocr document collection in which the document resides

Questions or Comments? Direct them to us directly at info@edocr.com or by visiting the Help Center on our site: [edocr.com](https://edocr.com)

Thanks for using the edocr Document Viewer for WordPress!
-The edocr Team