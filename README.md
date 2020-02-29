# Xnowi_PHP

Project xnowi is based on a simple concept, the separation of content from presentation through XML and XSLT. A single XML and many XSL to get many different results, not all visual.

Xnowi is a library, you can include it in your project and call the method for transform XML through XSL for generate a new version of source.

You can show a [Demo site with Xnowi](http://simone.paolucci.name/xnowi/home.php).

## Examples
You can show an example of use at this repository [Xnowi_PHP_Example](https://github.com/Magicianred/Xnowi_PHP_Example)

## Install
Install xnowi with composer (https://packagist.org/packages/magicianred/xnowi)  

`
composer require magicianred/xnowi
`

## Basic use  

### Example of XML data file
```xml
<?xml version="1.0" encoding="ISO-8859-1" ?>
<website>
  <name>Xs.WebUI</name>
  <denomination>xnowi not only web interface</denomination>
  <slogan>free software per realizzare siti con output multipli</slogan>
</website>
```

### Example of XSLT file
```xsl
<?xml version="1.0" encoding="ISO-8859-1" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  
  <xsl:param name="isLoggin">0</xsl:param>
  <xsl:param name="username">Ospite</xsl:param>
  <xsl:param name="language">it</xsl:param>
  <xsl:param name="lang_mode">cookie</xsl:param>
  <xsl:param name="mode">web</xsl:param>
  <xsl:param name="path"></xsl:param>
  <xsl:param name="pathRoot"></xsl:param>
  <xsl:param name="pathTheme">themes/web/</xsl:param>
  <xsl:param name="extTheme">.php</xsl:param>
  <xsl:param name="pageName">index</xsl:param>
  <xsl:param name="pageExt">.php</xsl:param>
  <xsl:param name="urlRoot">http://simone.paolucci.name/xnowi/</xsl:param>
  
  <xsl:template match="/">
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$language}">
      <head>
        <xsl:call-template name="head" />
      </head>
      <body>
        <div id="header">
            <h1><strong><xsl:value-of select="//website/name" /></strong> <em>(<xsl:value-of select="//website/denomination" />)</em></h1>
            <p><xsl:value-of select="//website/slogan" /></p>
        </div>
      </body>
    </html>
  </xsl:template>

  <xsl:template name="head">
    <title>
      <xsl:value-of select="//website/name" /> -<xsl:value-of select="//website/denomination" /> 
    </title>
    <link type="text/css" rel="stylesheet" href="{$pathRoot}{$pathTheme}styles/web.css" media="screen" />
  </xsl:template>

</xsl:stylesheet>

```

### Your file PHP (base for all the follow example)
```php
require_once __DIR__ . '/vendor/autoload.php'; // Autoload files using Composer autoload

use Xnowi\Engine as xnowiEngine;

$engine = new xnowiEngine();

$xmlPath = "/path/file.xml";
$xslPath = "/path/file.xsl";	
```

#### Make a simple transformation XML -> XSL
```php
echo $engine->xTransformSimple( $xmlPath, $xslPath );
```

#### Make a transformation with params (of xsl stylesheet)
```php
$params = array(
    'isLoggin' => $isLoggin,
    'username' => $username,
    'language' => $engine->get_Lang()
    );

echo $engine->xTransformSimpleWithParams( $xmlPath, $xslPath, $Params );
```

#### Make a transformation with internalization
```php
$xmlLangPath = "/path/langs/file.xml";	

echo $engine->xTransformIntlSimple( $xmlPath, $xslPath, $xmlLangPath);
```

#### Make a transformation with params (of xsl stylesheet) and internalization
```php
$xmlLangPath = "/path/langs/file.xml";	

$params = array(
    'isLoggin' => $isLoggin,
    'username' => $username,
    'language' => $engine->get_Lang()
    );
    
echo $engine->xTransformIntlWithParams( $xmlPath, $xslPath, $xmlLangPath, $Params);
```

#### Static site generator

##### Make a simple transformation with output
```php
$fileOutput = "/html/index.htm";
    
echo $engine->xTransformOtpSimple( $xmlPath, $xslPath, $fileOutput);
```

##### Make a simple transformation with params (of xsl stylesheet) and output
```php
$fileOutput = "/html/index.htm";

$params = array(
    'isLoggin' => $isLoggin,
    'username' => $username,
    'language' => $engine->get_Lang()
    );
    
echo $engine->xTransformOptSimpleWithParamsSimple( $xmlPath, $xslPath, $Params, $fileOutput);
```

##### Make a transformation with params (of xsl stylesheet) and internalization
```php
$fileOutput = "/html/index.htm";

$xmlLangPath = "/path/langs/file.xml";	

$params = array(
    'isLoggin' => $isLoggin,
    'username' => $username,
    'language' => $engine->get_Lang()
    );
    
echo $engine->xTransformOptIntlWithParams( $xmlPath, $xslPath, $xmlLangPath, $Params, $fileOutput);
```


## Required
- PHP > 5.6
- XSL ext by php.ini

## About Version 0.2

This is the version 0.2 of the project (the v.1 is still downloadable from [SourceForge](https://sourceforge.net/projects/xnowi/)) in this there is the introduction of Namespace.