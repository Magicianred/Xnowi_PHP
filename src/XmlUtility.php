<?php

namespace Xnowi;

use DOMDocument;
use DOMXPath;

/**
*
* @package xnowi
* @version 0.2
* @copyright (c) 2020 Simone Paolucci
* @license GNU LAGPLv3 Lesser Affero General Public License v3
* @project http://simone.paolucci.name/xnowi
* @link http://simone.paolucci.name/xnowi/download.php
* @date 20200229
*
* This is a utility class for Xml function
*
*
*/
class XmlUtility {
	
	// Add params to XSL
	static function xAddParams( &$xp , $Params ) {
		if( $Params != null ) {
			// PHP 5.1 or >
			$xp->setParameter('', $Params);
			
			/* < PHP 5.1
			foreach ($Params as $name => $value) {
				$xp->setParameter('', $name, $value);
			}*/
		}
	}
	
	// Import a XML into other
	static function xImportDoc(&$dom1, &$dom2) {
		/* START CODE : http://www.php.net/manual/en/domnode.clonenode.php */
		/*/ PHP 4 (DOM XML):
		$root1 = $dom1->document_element();
		$other_node = $dom2->document_element();
		$root1->append_child($other_node->clone_node(true)); */ 
		
		// PHP 5 (DOM):
		$dom1->documentElement->appendChild(
			$dom1->importNode($dom2->documentElement, true)); 
		/* END CODE : http://www.php.net/manual/en/domnode.clonenode.php */
	}
			
	// Load xml file with eventualy xi:include
	static function xDocLoad($uriDoc) {
		/* START CODE : http://bugs.php.net/bug.php?id=46827 */
		$document = new DOMDocument();
		$document->load( $uriDoc );
		
		//echo "Parsing <xi:include> tags...\n";
		//$this->xinclude_all( $document );
		
		$counter = 0;

		$xpath = new DOMXPath($document);
		$xpath->registerNamespace('xi', $ns = 'http://www.w3.org/2001/XInclude');
		$includes = $xpath->query( "//xi:include" );
	
		while($includes->length){			
			$counter++;
			//echo "Parsing <xi:include> layer number $counter. {$includes->length} <xi:include> tags on this layer. ";
			$document->xinclude();
			$includes = $xpath->query( "//xi:include" );
			//echo "{$includes->length} includes to process on next layer\n";
		}
	
		//echo "$counter <xi:include> tag levels parsed.\n";
		
		/* END CODE : http://bugs.php.net/bug.php?id=46827 */
		return $document;
	}
	
	/* Including xi:include */
	private static function xinclude_all( $document ){
		/* START CODE : http://bugs.php.net/bug.php?id=46827 */
		$counter = 0;

		$xpath = new DOMXPath($document);
		$xpath->registerNamespace('xi', $ns = 'http://www.w3.org/2001/XInclude');
		$includes = $xpath->query( "//xi:include" );
	
		while($includes->length){			
			$counter++;
			//echo "Parsing <xi:include> layer number $counter. {$includes->length} <xi:include> tags on this layer. ";
			$document->xinclude();
			$includes = $xpath->query( "//xi:include" );
			//echo "{$includes->length} includes to process on next layer\n";
		}
	
		//echo "$counter <xi:include> tag levels parsed.\n";
		/* END CODE : http://bugs.php.net/bug.php?id=46827 */
		return $document;
	}

}
?>
