<?php
/* Copyright 2010 aloi-project
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA
 *
 * This file incorporates work covered by the following copyright and
 * permissions notice:
 *
 * Copyright (C) 2008 PHruts
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA
 */

/**
 * The Aloi_Serphlet_Host plays the role of initialising the
 * environment for handling requests and responses in PHP.
 *
 * It will process the web.xml configuration file, create the
 * request and response objects and map the requests to the correct
 * object/classes in your application.
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
class Aloi_Serphlet_Host {
	/** Our HTTP Request/Response Objects */
	protected static $request;
	protected static $response;

	protected static $basePath;
	
	protected static $context;
	protected static $config;
	protected static $servlet;
	
	protected static $log;
	
	/** Our servlet configuration files (web configs) */
	protected static $configurationFiles = array();
	protected static $cacheDirectory = null;
	
	/**
	 * Initialisation function
	 * @param $basePath
	 */
	protected static function init($basePath = null) {
		static $configured;
		
		// Configure the application (once)
		if($configured == true) return;
		
		// TODO: Configure the base path
		if(empty($basePath)) self::$basePath = dirname(__FILE__);
		else self::$basePath = (string)$basePath;
		
		// TODO: Check for our servlet configuration files
		if(empty(self::$configurationFiles)) throw Aloi_Serphlet_Exception_UnavailableException('Missing servlet configuration. Please ensure application has added references to servlet configuration files (See Aloi_Serphlet_Application::addServletConfigurationFile())');
		
//		self::setTimeMarker('INIT02 - Init Configuration');
		
		// TODO: Process the servlet configuration
		if(self::configFileExpired()) {
			// Process the configuration
			$digester = new Aloi_Phigester_Digester();
			$digester->addRuleSet(new Aloi_Serphlet_Config_ApplicationRuleSet());

			self::$context = new Aloi_Serphlet_Config_ApplicationContext(self::$basePath, null);
			$digester->push(self::$context);
			$configFilePath = self::getRealPath(current(self::$configurationFiles));
			$digester->parse($configFilePath);
			unset ($digester);
			
			// Cache the config
			if(is_writable(self::getCacheDirectory())) {
				$cacheFile = self::getRealPath(self::getCacheDirectory() . DIRECTORY_SEPARATOR . 'serphlet.data');
				$serialData = serialize(self::$context);
				file_put_contents($cacheFile, $serialData);
			}
		} else {
			// Load the configuration
			$cacheFile = self::getRealPath(self::getCacheDirectory() . DIRECTORY_SEPARATOR . 'serphlet.data');
			$serialData = file_get_contents($cacheFile);
			self::$context = unserialize($serialData);
		}
		
		// Configure connectors
		self::$request = new Aloi_Serphlet_Application_HttpRequest();
		self::$response = new Aloi_Serphlet_Application_HttpResponse();
		
		// Configure the base dir
		self::$request->setAttribute(Aloi_Serphlet_Globals::BASE_PATH, $basePath);
		
		
		// Complete initialisation
		$configured = true;
	}
	
	/**
	 * Provides a real path to the provided path, prepending the basePath
	 * of the application if necessary.
	 *
	 * TODO: consider private/protected and get calls relative to context in modules
	 * @param unknown_type $path
	 */
	public function getRealPath($path) {
		return self::$basePath . (substr($path, 0, 1) == '/' ? $path : '/' . $path);
	}
	
	/**
	 * Determines whether the web.xml configuration cache has expired
	 */
	protected static function configFileExpired() {
		// TODO: Determine if the same number of web configs are being used
		$cachePath = self::getRealPath(self::getCacheDirectory() . DIRECTORY_SEPARATOR . 'serphlet.data');
		if (!file_exists($cachePath)) {
			return true;
		}
		$cacheTime = filemtime($cachePath);
		
		// TODO: Look at multiple servlet configurations
		$filePath = self::getRealPath(current(self::$configurationFiles)); // Pop the first
		$fileTime = filemtime($filePath);
		
		return $fileTime > $cacheTime;
	}
	
	/**
	 * Maps the current PHP request to the correct servlet configuration
	 */
	protected static function map() {
		// TODO: Write a mapper class to fully implement matching
		
		// Look through servlet mappings and do a match
		$pathInfo = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : null;
		
		// Check for configured servlets
		$servlets = self::$context->getServlets();
		if(empty($servlets)) throw new Aloi_Serphlet_Exception_Unavailable('No servlets configured');
		
		// Attempt to match urls
		$urlMapped = array();
		foreach($servlets as $servlet) {
			$urlMappings = $servlet->getMappings();
			if(!empty($urlMappings)) foreach($urlMappings as $urlMapping) {
				// Is this a file extension method (but not a path method)
				if(!empty($pathInfo) && substr($urlMapping, 0, 2) == '*.') {
					$suffix = substr($urlMapping, 1);
					if(strpos($pathInfo, '.') > 0 && substr($pathInfo, (-1 * strlen($suffix))) == $suffix) {
						$urlMapped[$urlMapping] = $servlet;
					}
				}
				
				// /servlet/path/* method (or default /*), but not with an extension present
				if(substr($urlMapping, -2) == '/*' && (strpos($pathInfo, '.') === false || strlen($urlMapping) == 2)) {
					$prefix = substr($urlMapping, 0, -1);
					$ignoreTrailingSlashPathInfo = substr($pathInfo, -1) != '/' ? $pathInfo . '/' : $pathInfo;
					if(strpos($ignoreTrailingSlashPathInfo, $prefix) === 0) {
						// Prefix matches the path info
						$urlMapped[$urlMapping] = $servlet;
					}
				}
			}
		}
		
		// TODO: Consider the welcome files (when empty path info)

		// Match the 'best' (based on the most specific match)
		$pattern = '';
		if(count($urlMapped) == 1) {
			// Only one matched
			$pattern = key($urlMapped);
			self::$config = current($urlMapped);
		} else {
			// Match the most specific one
			$matches = array_keys($urlMapped);
			foreach($matches as $match) if(strlen($match) > strlen($pattern)) $pattern = $match;
			if(!empty($pattern)) self::$config = $urlMapped[$pattern];
		}
		
		// Check the result
		if(empty(self::$config)) throw new Aloi_Serphlet_Exception_Unavailable('No servlets configured');
		self::$config->setServletMapping($pattern);
		
		// Setup the request paths
		$requestPathInfo = '';
		$requestServletPath = '';
		if(strpos($pattern, '.') > -1) {
			// Extension match, ditch the extension in the path info
			$requestPathInfo = substr($pathInfo, 0, (-1 * strlen(substr($pattern, 1))));
		} else {
			// Path pattern
			$requestServletPath = substr($pattern, 0, -2);
			$requestPathInfo = substr($pathInfo, strlen($requestServletPath));
		}
		if(substr($requestPathInfo, 0, 1) !== '/') $requestPathInfo = '/' . $requestPathInfo;
		self::$request->setPathInfo($requestPathInfo);
		self::$request->setServletPath($requestServletPath);
				
		// Factory the matching servlet classname for the request
		try {
			$className = self::$config->getServletClass();
			$servlet = Aloi_Serphlet_ClassLoader::newInstance($className, 'Aloi_Serphlet_Servlet');
		} catch (Exception $e) {
			// TODO: Handle
			throw $e;
		}
		return $servlet;
	}
	
	/**
	 * The main function for processing the current request from PHP
	 */
	public static function process($basePath = null) {
		try {
			ob_start();
			self::$log = Aloi_Util_Logger_Manager::getRootLogger();
			
			// Register a shutdown function
			register_shutdown_function(array('Aloi_Serphlet_Host', 'shutdown'));
			set_error_handler(array('Aloi_Serphlet_Host', 'defaultErrorHandler'));
			
			// Initialise
			self::init($basePath);
			
			// TODO: Identify the servlet
			self::$servlet = self::map();
			if(empty(self::$servlet)) throw new Aloi_Serphlet_Exception_UnavailableException('Servlet not available for this request');
			
			// Initialise the servlet
			self::$servlet->init(self::$config);
			
			// Configure the filter configurations
			self::$servlet->getServletConfig()->getServletContext()->filterStart();
			
			// Create a filter chain
			$filterFactory = Aloi_Serphlet_Application_FilterFactory::getInstance();
			$filterChain = $filterFactory->createFilterChain(self::$request, self::$servlet);
			
			// Do filters prior to processing the modules
			if($filterChain != null) {
				$filterChain->doFilter(self::$request, self::$response);
			} else {
				Aloi_Serphlet_Application_FilterChain::servletProcess(self::$request, self::$response, self::$servlet);
			}
			
			// Release filters
			self::$servlet->getServletConfig()->getServletContext()->filterStop();
						
			// TODO: Consider the location of this dispatch forward!
			if(!self::$response->isCommitted() && self::$response->isError()) {
				Aloi_Serphlet_Application_RequestDispatcherForward::commit(self::$request, self::$response, self::$context);
			}
			
			// Flush the response
			self::$response->flushBuffer();
			
			// TODO: Shutdown
			
		} catch(Exception $e) {
			self::gracefulDie($e);
		}
	}
	
	/**
	 * A shutdown method for handling logging PHP_ERROR events to logging
	 */
	public static function shutdown() {
		static $performed;
		// Consider if a PHP Fatal error caused shutdown
		$error = error_get_last();
		$raiseTypes = array(E_ERROR);
		if($error != null && in_array($error['type'], $raiseTypes) && !$performed) {
			$performed = true;
			@ob_end_clean();
			if(!empty(self::$log)) {
				self::$log->error('PHP Error: '. $error['file'] . ':' . $error['line'] . ' ' . $error['message'] . ' LEVEL: ' . $error['type']);
			}
			self::gracefulDie(new Exception('Encountered a PHP Fatal Error'));
			exit();
		}
	}
	
	/**
	 * The default handler for PHP errors, logging them to the logging object
	 */
	public static function defaultErrorHandler($type, $message, $file, $line) {
		// Log some of the PHP Errors to the logger manager
		$raiseTypes = array(E_USER_ERROR, E_USER_WARNING);
		if(in_array($type, $raiseTypes) && !empty(self::$log)) {
			switch($type) {
				case E_USER_ERROR:
					self::$log->error('PHP Error (E_USER_ERROR): ' . $file . ':' . $line . ' ' . $message);
					throw new Exception($message);
				case E_USER_WARNING:
					self::$log->error('PHP Warning (E_USER_WARNING): ' . $file . ':' . $line . ' ' . $message);
					break;
			}
		}
	}
	
	private static function gracefulDie($exception = null) {
		// Attempt a graceful die
		@ob_end_clean();
		if(empty($exception)) $exception = new Exception('Unhandled PHP Exception encountered');
		if(!empty(self::$log)) {
			self::$log->error('Caught exception ' . $exception->getMessage());
			self::$log->debug($exception->getTraceAsString());
		}
		try {
			if(!empty(self::$response) && !empty(self::$request) && !empty(self::$context)) {
				self::$response->sendError(Aloi_Serphlet_Application_HttpResponse::SC_INTERNAL_SERVER_ERROR, $exception->getMessage());
				self::$request->setAttribute(Aloi_Serphlet_Globals::ERROR_MESSAGE_ATTR, $exception->getMessage());
				self::$request->setAttribute(Aloi_Serphlet_Globals::EXCEPTION_ATTR, $exception);
				Aloi_Serphlet_Application_RequestDispatcherForward::commit(self::$request, self::$response, self::$context);
				self::$response->flushBuffer();
			}
		} catch(Exception $e) {
			if(!empty(self::$log)) {
				self::$log->error('Could not handle a graceful die ' . $e->getMessage());
			}
		}
		exit();
	}
	
	/**
	 * Add the servlet configuration file to the application
	 * Note: Currently only supports 1 configuration file
	 * @param string $configuration
	 */
	public static function addConfigurationFile($configuration) {
		// TODO: Add the configuration to the stack
		self::$configurationFiles = array($configuration);
	}
	
	/**
	 * Modify the cache directory for Aloi to write cache files
	 * to as necessary
	 */
	public static function setCacheDirectory($path) {
		self::$cacheDirectory = $path;
	}
	
	/**
	 * Obtain the directory to store the cache to
	 */
	public static function getCacheDirectory() {
		return self::$cacheDirectory;
	}
	
	/**
	 * Obtain the current servlet configuration
	 * NOTE: This is likely to be removed in future versions, use local scope instead!
	 */
	public static function getServletConfig() {
		return self::$config;
	}
	
	/**
	 * Obtain the reference to the request that the host has configured
	 * NOTE: This is likely to be removed in future versions, use local scope instead!
	 */
	public static function getRequest() {
		return self::$request;
	}
	
	/**
	 * Obtain the reference to the response that the host has configured
	 * NOTE: This is likely to be removed in future versions, use local scope instead!
	 */
	public static function getResponse() {
		return self::$response;
	}
	
	/**
	 * Obtain the current servlet that the host has configured
	 * NOTE: This is likely to be removed in future versions, use local scope instead!
	 */
	public static function getServlet() {
		return self::$servlet;
	}
}