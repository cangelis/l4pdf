# Documentation

## Methods

Method | Description
------ | -----------
`loadView('view')` | PDF will be generated using the Laravel view
`loadUrl('http://www.laravel.com')` | PDF will be generated using the remote url
`loadHTML('<strong>Hello World</strong>')` | PDF will be generated using the plain HTML String
`loadHTMLFile('path/to/html/file.html')` | PDF will be generated using an HTML file
`grayscale()` | PDF will be generated in grayscale
`orientation('Landscape')` | Set orientation to Landscape or Portrait (default Portrait)
`pageSize('A4')` | Set paper size to: A4, Letter, etc. (default A4)
`lowquality()` | Generates lower quality pdf/ps. Useful to shrink the result document space
`dpi(96)` | Change the dpi explicitly (this has no effect on X11 based systems)
`imageDpi(600)` | When embedding images scale them down to this dpi (default 600)
`imageQuality(94)` | When jpeg compressing images use this quality (default 94)
`marginBottom('10mm')` | Set the page bottom margin (default 10mm)
`marginTop('10mm')` | Set the page top margin (default 10mm)
`marginRight('10mm')` | Set the page right margin (default 10mm)
`marginLeft('10mm')` | Set the page left margin (default 10mm)
`pageHeight('20cm')` | Page height
`pageWidth('50cm')` | Page width
`noBackground()` | Do not print background
`encoding('UTF-8')` | Set the default text encoding, for input
`enableForms()` |  Turn HTML form fields into pdf form fields
`noImages()` | Do not load or print images
`disableInternalLinks()` | Do not make local links
`disableJavascript()` | Do not allow web pages to run javascript
`password('password')` | HTTP Authentication password
`username('username')` | HTTP Authentication username
`footerCenter('text')` | Centered footer text
`footerFontName('Arial')` | Set footer font name (default Arial)
`footerFontSize(12)` | Set footer font size (default 12)
`footerHtml('http://www.google.com')` | Adds a html footer
`footerLeft('text')` | Left aligned footer text
`footerLine()` | Display line above the footer
`footerRight('text')` | Right aligned footer text
`footerSpacing('10mm')` | Spacing between footer and content in mm (default 0)
`headerCenter('text')` | Centered header text
`headerFontName('Arial')` | Set header font name (default Arial)
`headerFontSize(12)` | Set header font size (default 12)
`headerHtml('http://www.google.com')` | Adds a html header
`headerLeft('text')` | Left aligned header text
`headerLine()` | Display line above the header
`headerRight('text')` | Right aligned header text
`headerSpacing('10mm')` | Spacing between header and content in mm (default 0)
`printMediaType()` | Use print media-type instead of screen
`zoom(0.5)` | Use the zoom factor (default 1)
`download('filename')` | Serve the document as an attachment
`stream('filename')` | Display the document in the browser window
`save('path/to/file.pdf')` | Save the document to the specified location