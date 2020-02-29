<?php

namespace Xnowi;
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
* This is a configuration class for xnowi
*
*/	
	//xnowi_config_debug();
	
	// Manage the xnowi settings
	class Settings {
		// Return app client path root
		static public function get_pathToRootAppClient() {
			return uriFunction::get_path_to_root();
		}
		
		// Return the webroot path root
		static public function get_pathToWebRoot() {
			// current directory
			(defined('__DIR__')) ? $currentDirectory = __DIR__ : $currentDirectory = dirname(__FILE__);
			$currentDirectory = rtrim($currentDirectory, "/");
			$currentDirectory = str_replace("xnowi/configs","",$currentDirectory);
			$currentDirectory = uriFunction::normalize_uri($currentDirectory);
			return $currentDirectory;
		}
		
		// Return path from root to xnowi
		static public function get_pathFromRootToXnowi() {
			$pathFromRootToXnowi = ltrim(dirname($_SERVER['PHP_SELF']),"/")."/";
			return $pathFromRootToXnowi;
		}
		
		// Return path url
		static public function get_pathUrl() {
			$urlRoot = "http://{$_SERVER['HTTP_HOST']}";
			$urlRoot = uriFunction::normalize_uri($urlRoot).self::get_pathFromRootToXnowi();
			return $urlRoot;
		}
	}
	
	// Manage uri address
	class uriFunction {
		// Function normalize uri with last slash
		static public function normalize_uri($uri) {
			$uri = rtrim($uri, "/");
			$uri = $uri . "/";
			return $uri;	
		}
		
		// Function that return the relative path to root
		static public function get_path_to_root() {
			$parsedUri = dirname($_SERVER['PHP_SELF']);
			$parsedUri .= substr($parsedUri, -1) != '/' ? '/' : '';
			$relativeUri = str_replace('/', '', $parsedUri);
			$relativePath = strlen($parsedUri) - strlen($relativeUri) - 1;
			if ($relativePath < 0){ $relativePath = 0; }
			$relativePath = str_repeat('../', $relativePath);
			if (!$relativePath){ $relativePath = './'; }
			return $relativePath;
		}
	}
		
	// Manage the debug
	function xnowi_config_debug() {
		echo "------- DEBUG XNOWI -----------<br>";
		echo "Path to Root: ". uriFunction::get_path_to_root() ."<br>";
		echo "Path to App Client Root: " . Settings::get_pathToRootAppClient() . "<br>";
		echo "Path to WebRoot: " . Settings::get_pathToWebRoot() . "<br>";	
		echo "Path from Root to xnowi: " . Settings::get_pathFromRootToXnowi() . "<br>";
		echo "Path URL: ". Settings::get_pathUrl() ."<br>";
		echo "------- END DEBUG XNOWI -----------<br>";
		echo "<br>";
	}
?>
