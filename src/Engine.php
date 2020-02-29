<?php

namespace Xnowi;

use Xnowi\XmlUtility as Utility;
use DomDocument;
use XsltProcessor;

// require_once("includes/xmlUtility.php");
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
* This is the engine xnowi
* Make the transformation
*
*/
	
class Engine {
	private $Lang;
	private $DefaultLang;
	private $Mode;
	private $DefaultMode;
	private $pathTheme;
	private $pathStyle;
	private $Params;
	
	function __construct() {
		$this->DefaultLang = $this->getDefaultLang();
		$this->Lang = $this->getDefaultLang();
		$this->DefaultMode = $this->getDefaultMode();
		$this->Mode = $this->getDefaultMode();
		$this->pathTheme = $this->getDefaultPathTheme();
		$this->pathStyle = $this->getDefaultPathStyle();
		$this->Params = $this->getDefaultXslParams();
	}
	
/*	function __destruct() {
		
	}
*/	
	
	#region Simple transformation
	
	// Make a simple transformation XML -> XSL
	public function xTransformSimple( $UriXml, $UriXsl ) {
		
		// Load XML
		$xml_doc = Utility::xDocLoad($UriXml);
		
		// Load XSL
		$xsl = new DomDocument;
		$xsl->load($UriXsl);
		
		// Load Processor
		$xp = new XsltProcessor();
		$xp->importStylesheet($xsl);
		
		// transform the XML using the XSL file
		if (!$result = $xp->transformToXML($xml_doc)) {
			trigger_error('XSL transformation failed.', E_USER_ERROR);
		} 
		return $result;
	}
	
	// Make a transformation with params
	public function xTransformSimpleWithParams( $UriXml, $UriXsl, $Params ) {
		
		// Load XML
		$xml_doc = Utility::xDocLoad($UriXml);
		
		// Load XSL
		$xsl = new DomDocument;
		$xsl->load($UriXsl);
		
		// Load Processor
		$xp = new XsltProcessor();
		$xp->importStylesheet($xsl);
		
		// Add params to processor 
		Utility::xAddParams( $xp, $Params );
		
		// transform the XML using the XSL file
		if (!$result = $xp->transformToXML($xml_doc)) {
			trigger_error('XSL transformation failed.', E_USER_ERROR);
		}
		return $result;
	}
	
	#endregion Simple transformation
	
	
	#region International transformation
	
	// Make a transformation with internalization		
	public function xTransformIntlSimple( $UriXml, $UriXsl, $UrisLang) {
		
		$result = self::xTransformIntlWithParams( $UriXml, $UriXsl, $UrisLang, null);
		return $result;
	}
	
	// Make a transformation with params and internalization		
	public function xTransformIntlWithParams( $UriXml, $UriXsl, $UrisLang, $Params) {
		
		// Load XML
		$xml_doc = Utility::xDocLoad($UriXml);
		
		// Load XML languages
		if(isset($UrisLang) && is_array($UrisLang)) {
			foreach ($UrisLang as $uriLang) {
				$xml_lang = new DomDocument;
				$xml_lang->load($uriLang);
				Utility::xImportDoc( $xml_doc, $xml_lang ); 
			}
		}
		
		// Load XSL
		$xsl = new DomDocument;
		$xsl->load($UriXsl);
		
		// Load Processor
		$xp = new XsltProcessor();
		$xp->importStylesheet($xsl);			
		
		// Add params to processor 
		Utility::xAddParams( $xp, $Params );
		
		// transform the XML using the XSL file
		if (!$result = $xp->transformToXML($xml_doc)) {
			trigger_error('XSL transformation failed.', E_USER_ERROR);
		} 
		return $result;
	}
			
	// [Obsolet] Make a transformation with params and internalization [new: xTransformIntlWithParams]
	public function xTransformIntlSimpleWithParams( $UriXml, $UriXsl, $UriXmlLang, $UriXslLang, $Params) {
		$UrisLang = array($UriXmlLang,$UriXslLang);
		return $this->xTransformIntlWithParams( $UriXml, $UriXsl, $UrisLang, $Params);
	}
	
	#endregion International transformation
			
	
	#region Output transformation
	/* ToDo forAllFunction: 
		*	create files/directories, 
		*	manage permission files/directories, 
		*	manage xml instructiond
		* add/modify powerBy xnowi -> createdBy xnowi
		*/
			
	// Make a simple transformation with output
	public function xTransformOtpSimple( $UriXml, $UriXsl, $fileOutput) {
					
		$result = $this->xTransformSimple( $UriXml, $UriXsl );
		$xOutput = new DomDocument;
		//$xOutput->loadXml($result);
		
		return $result;
	}
	
	// Make a simple transformation with params and output
	public function xTransformOptSimpleWithParamsSimple( $UriXml, $UriXsl, $Params, $fileOutput) {
					
		$result = $this->xTransformSimpleWithParams( $UriXml, $UriXsl, $Params );
		$xOutput = new DomDocument;
		//$xOutput->loadXml($result);
		
		return $result;
	}
	
	// Make a transformation with params and internalization		
	public function xTransformOptIntlWithParams( $UriXml, $UriXsl, $UrisLang, $Params, $fileOutput) {
		
		
		$result = $this->xTransformIntlWithParams( $UriXml, $UriXsl, $UrisLang, $Params );
		$result = $this->processOutput($result, $fileOutput);
		return $result;
	}
	
	// Make directories/files output
	private function processOutput($xData, $fileOutput) {
		//print("<hr>".htmlentities($xData)."<hr>");
		$xOutput = new DomDocument;
		$xOutput->loadXML("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n".$xData);
		//print("<hr>".htmlentities($xOutput->saveXML())."<hr>");
		$xOutput->save($fileOutput);
		$result = $xOutput->saveXML();
		return $result;
	}
	
	#endregion Output transformation
	
	
	#region Default property
	
	// set default value (ToDo charge from external file)
	private function getDefaultLang() {
		return "it";
	}		
	private function getDefaultMode() {
		return "web";
	}
	private function getDefaultPathTheme() {
		return "themes/web/";
	}		
	private function getDefaultPathStyle() {
		return "themes/web/styles/";
	}		
	private function getDefaultXslParams() {
		return null;
	}				
	
	#endregion Default property
	
	
	#region Get/Set property
			
	// create property
	public function set_Lang($lang) {
			$this->Lang = $lang;
	}
	public function get_Lang() {
		if($this->Lang == '') {
			return $this->getDefaultLang();
		} else {
			return $this->Lang;
		}
	}
	public function set_DefaultLang($lang) {
			$this->DefaultLang = $lang;
	}
	public function get_DefaultLang() {
			return $this->DefaultLang;
	}
	public function set_Mode($mode) {
			$this->Mode = $mode;
	}
	public function get_Mode() {
		if($this->Mode == '') {
			return $this->getDefaultMode();
		} else {
			return $this->Mode;
		}
	}
	public function set_DefaultMode($mode) {
			$this->DefaultMode = $mode;
	}
	public function get_DefaultMode() {
			return $this->DefaultMode;
	}
	public function set_PathTheme($path) {
			$this->pathTheme = $path;
	}
	public function get_PathTheme() {
			return $this->pathTheme;
	}
	public function set_PathStyle($path) {
			$this->pathStyle = $path;
	}
	public function get_PathStyle() {
			return $this->pathStyle;
	}
	public function set_Params($params) {
			$this->Params = $params;
	}
	public function get_Params() {
			return $this->Params;
	}
	
	#endregion Get/Set property

}
?>
