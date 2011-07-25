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
 * Copyright 1997-2008 Sun Microsystems, Inc. All rights reserved.
 *
 * The contents of this file are subject to the terms of either the GNU
 * General Public License Version 2 only ("GPL") or the Common Development
 * and Distribution License("CDDL") (collectively, the "License").  You
 * may not use this file except in compliance with the License. You can obtain
 * a copy of the License at https://glassfish.dev.java.net/public/CDDL+GPL.html.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 */

/**
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
class Aloi_Serphlet_Config_ApplicationContext implements Aloi_Serphlet_Config_ServletContext {
	protected $attributes;
	
	protected $requestDispatcher;
	protected $context;
	protected $basePath;
	
	protected $servlets = array();
	
	protected $welcomeFiles = array();
	
	protected $statusPages = array();
	protected $exceptionPages = array();
	
	/**
	 * The servletMapping variable (index.php?do=*, /action/*, *.do)
	 */
	protected $servletMapping = array();
	
	/**
	 * Array of paths to add to the include_path PHP directive.
	 *
	 * @var array
	 */
	protected $includePaths = array ();

	/**
	 * The initialization parameters.
	 *
	 * @var array
	 */
	protected $parameters = array ();

	/**
     * The set of filter definitions for this application, keyed by
     * filter name.
     */
    protected $filterDefs = array();
	
	/**
     * The set of filter mappings for this application, in the order
     * they were defined in the deployment descriptor.
     */
    protected $filterMaps = array();
	
    /**
     * The context for this servlet
     */
    protected $servletContext = null;
    
    protected $requestProcessor = null;
    
    /**
     * Return with the context of this servlet
     */
    public function getServletContext() {
    	return $this->servletContext;
    }
    
    
    /**
	 * @param string $path The path to add to the include_path PHP directive
	 * @param boolean $relative Indicate if the path is relative to the
	 * application context or absolute
	 */
	public function setIncludePath($path, $relative) {
		$temp = strtolower($relative);
		if ($temp === 'false' || $temp === 'no') {
			$this->includePaths[] = array (
				(string) $path,
				false
			);
		} else {
			$this->includePaths[] = array (
				(string) $path,
				true
			);
		}
	}

	/**
	 * Returns a string containing the value of the named initialization
	 * parameter, or null if the parameter does not exist.
	 *
	 * @param string $name The name of the initialization parameter
	 * @return string
	 */
	public function getInitParameter($name) {
		if (array_key_exists($name, $this->parameters)) {
			return $this->parameters[$name];
		} else {
			return null;
		}
	}

	/**
	 * Returns the names of the servlet's initialization parameters.
	 *
	 * @return array Returns an array of string, or an empty array if the
	 * servlet has no initialization parameters
	 */
	public function getInitParameterNames() {
		return array_keys($this->parameters);
	}

	/**
	 * Set an initialization parameter.
	 *
	 * @param string $name Name of the initialization parameter
	 * @param string $value Value of the initialization parameter
	 */
	public function setInitParameter($name, $value) {
		$name = (string) $name;
		$this->parameters[$name] = (string) $value;
	}
	
	/**
     * Add a filter definition to this Context.
     *
     * @param FilterDef filterDef The filter definition to be added
     */
    public function addFilterDef(Aloi_Serphlet_Config_FilterDef $filterDef) {
        $this->filterDefs[$filterDef->getFilterName()] = $filterDef;
    }
    
    /**
     * Remove the specified filter definition from this Context, if it exists;
     * otherwise, no action is taken.
     *
     * @param filterDef Filter definition to be removed
     */
    public function removeFilterDef(Aloi_Serphlet_Config_FilterDef $filterDef) {
		unset($this->filterDefs[$filterDef->getFilterName()]);
    }
    
    /**
     * Return with the filter defs
     */
    public function getFilterDefs() {
    	return $this->filterDefs;
    }
    
    /**
     * Add multiple filter mappings to this Context.
     *
     * @param filterMaps The filter mappings to be added
     *
     * @exception IllegalArgumentException if the specified filter name
     *  does not match an existing filter definition, or the filter mapping
     *  is malformed
     */
    public function addFilterMaps(Aloi_Serphlet_Config_FilterMaps $filterMaps) {
        $dispatcherMapping = $filterMaps->getDispatcherMapping();
        $filterName = $filterMaps->getFilterName();
        $servletNames = $filterMaps->getServletNames();
        $urlPatterns = $filterMaps->getURLPatterns();
        foreach($servletNames as $servletName) {
            $fmap = new Aloi_Serphlet_Config_FilterMap();
            $fmap->setFilterName($filterName);
            $fmap->setServletName($servletName);
            $fmap->setDispatcherMapping($dispatcherMapping);
            $this->addFilterMap($fmap);
        }
        foreach($urlPatterns as $urlPattern) {
            $fmap = new Aloi_Serphlet_Config_FilterMap();
            $fmap->setFilterName($filterName);
            $fmap->setURLPattern($urlPattern);
            $fmap->setDispatcherMapping($dispatcherMapping);
            $this->addFilterMap($fmap);
        }
    }
    
    /**
     * Add a filter mapping to this Context->
     *
     * @param filterMap The filter mapping to be added
     *
     * @exception IllegalArgumentException if the specified filter name
     *  does not match an existing filter definition, or the filter mapping
     *  is malformed
     * @todo: Lookup the error message
     */
    public function addFilterMap(Aloi_Serphlet_Config_FilterMap $filterMap) {
        // Validate the proposed filter mapping
        $filterName = $filterMap->getFilterName();
        $servletName = $filterMap->getServletName();
        $urlPattern = $filterMap->getURLPattern();
        if ($this->findFilterDef($filterName) == null)
            throw new Aloi_Serphlet_Exception_IllegalArgument("applicationConfig->filterMap->name not found");
        if (($servletName == null) && ($urlPattern == null))
            throw new Aloi_Serphlet_Exception_IllegalArgument("applicationConfig->filterMap->either specify servlet name or url filter");
        if (($servletName != null) && ($urlPattern != null))
            throw new Aloi_Serphlet_Exception_IllegalArgument("applicationConfig->filterMap->either specify only a servlet name or url filter");
        if (($urlPattern != null) && !$this->validateURLPattern($urlPattern))
            throw new Aloi_Serphlet_Exception_IllegalArgument("applicationConfig->filterMap->pattern url pattern not valid");

        // Add this filter mapping to our registered set
		$this->filterMaps[] = $filterMap;
    }
    
	/**
     * Return the filter definition for the specified filter name, if any;
     * otherwise return <code>null</code>.
     *
     * @param filterName Filter name to look up
     * @return FilterDef
     */
    public function findFilterDef($filterName) {
    	$filterDefs = $this->getFilterDefs();
    	if(!empty($filterDefs[$filterName])) return $filterDefs[$filterName];
    	return (null);
    }
    
    
    public function findFilterConfig($filterName) {
    	if(!empty($this->filterConfigs) && !empty($this->filterConfigs[$filterName])) {
    		return $this->filterConfigs[$filterName];
    	}
    }
    
    /**
     * Return the set of filter mappings for this Context->
     */
    public function findFilterMaps() {
        return ($this->filterMaps);
    }
    
    /**
     * Remove a filter mapping from this Context->
     *
     * @param filterMap The filter mapping to be removed
     * @todo: Check the "equals" comparrison
     */
    public function removeFilterMap(FilterMap $removeFilterMap) {
		foreach($this->filterMaps as $index => $filterMap) {
			if($filterMap->getFilterName() == $removeFilterMap->getFilterName()
				&& $filterMap->getServletName() == $removeFilterMap->getServletName()
				&& $filterMap->getURLPattern() == $removeFilterMap->getURLPattern()) {
					// Remove
					unset($this->filterMaps[$index]);
				}
		}
    }
    
    public function filterStart() {
        // Instantiate and record a FilterConfig for each defined filter
        $ok = true;
        $this->filterConfigs = array();
        $filterDefs = $this->getFilterDefs();
        foreach(array_keys($filterDefs) as $name) {
            try {
                $filterConfig = new Aloi_Serphlet_Config_FilterConfig($this, $filterDefs[$name]);
                $this->filterConfigs[$name] = $filterConfig;
            } catch (Exception $e) {
                $log = Aloi_Util_Logger_Manager::getLogger(__CLASS__);
                $log->error('filterStart() caused exception ' . $e->getMessage());
                $ok = false;
            }
        }
        return ($ok);
    }
    
    public function filterStop() {
        // Release all Filter and FilterConfig instances
		foreach(array_keys($this->filterConfigs) as $name) {
			$this->filterConfigs[$name]->release();
        }
        $this->filterConfigs = array();
        return (true);
    }
    
	/**
     * Validate the syntax of a proposed <code>&lt;url-pattern&gt;</code>
     * for conformance with specification requirements->
     *
     * @param urlPattern URL pattern to be validated
     */
    private function validateURLPattern($urlPattern) {
        if ($urlPattern == null)
            return (false);
        if (strpos($urlPattern, '\n') >= 0 || strpos($urlPattern, '\r') >= 0) {
//            log->warn(sm->getString("standardContext->crlfinurl", urlPattern));
        }

        if(substr($urlPattern, 0, 2) == '*.') {
        	if (strpos($urlPattern, '/') === false)
                return (true);
            else
                return (false);
        }
        
        if (preg_match('/^\*\./', $urlPattern)) {
            if (strpos($urlPattern, '/') < 0)
                return (true);
            else
                return (false);
        }
        if (preg_match('/^\//', $urlPattern) && !preg_match('/\*\./', $urlPattern))
            return (true);
        else
            return (false);

    }
    
	
	public function __construct($basePath, $context) {
		if(empty($basePath)) {
			$this->basePath = realpath('.');
		} else $this->basePath = (string)$basePath;
		$this->servletContext = $context;
	}
	
	public function getContextPath() {
		
	}
	public function getContext($uriPath) {
		
	}
	public function getMajorVersion() {
		
	}
	public function getMinorVersion() {
		
	}
	public function getMimeType($file) {
		
	}
	public function getResourcePaths($path) {
		
	}
	public function getResource($path) {
		
	}
	public function getResourceAsStream($path) {
		
	}
	public function getRequestDispatcher($path) {
		if (is_null($this->requestDispatcher)) {
			$this->requestDispatcher = new Aloi_Serphlet_Application_RequestDispatcher($this);
		}

		$this->requestDispatcher->setRequestURI($path);
		return $this->requestDispatcher;
	}
	
	public function setRequestDispatcher(Aloi_Serphlet_Request_Dispatcher $dispatcher) {
		$this->requestDispatcher = $dispatcher;
	}
	
	public function getNamedDispatcher($name) {
		// TODO:
	}
	public function log($message, Exception $throwable) {
		
	}
	public function getRealPath($path) {
		return $this->basePath . (substr($path, 0, 1) != '/' ? '/' . $path : $path);
	}
	public function getServerInfo() {
		
	}
	public function getAttribute($name) {
		$name = (string)$name;
		if (is_array($this->attributes) && array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		} else {
			return null;
		}
	}
	
	public function getAttributeNames() {
		return(array_keys($this->attributes));
	}
	
	public function setAttribute($name, $object) {
		$name = (string) $name;
		$this->attributes[$name] = $object;
	}
	public function removeAttribute($name) {
		
	}
	public function getServletContextName() {
		
	}
	public function addServletConfig(Aloi_Serphlet_Config_ApplicationConfig $servletConfig) {
		$servletConfig->setServletContext($this);
		$this->servlets[$servletConfig->getServletName()] = $servletConfig;
	}
	public function addServletMapping(Aloi_Serphlet_Config_ServletMap $servletMapping) {
		// Locate the servlet
		$servlet = $this->getServlet($servletMapping->getServletName());
		if(empty($servlet)) throw new Aloi_Serphlet_Exception_IllegalArgument("servletConfig->servletMapping->name servlet name not found");
		
		// Test that servlet mapping url is correct
		$urlPatterns = $servletMapping->getUrlPatterns();
		foreach($urlPatterns as $urlPattern) {
			if (($urlPattern != null) && !$this->validateURLPattern($urlPattern))
            	throw new Aloi_Serphlet_Exception_IllegalArgument("servletConfig->servletMapping->pattern url pattern not valid");
            $servlet->addMapping($urlPattern);
		}
		$this->servletMapping[] = $servletMapping;
	}
	
	public function getServletNames() {
		return array_keys($this->servlets);
	}
	public function getServlet($name) {
		if(array_key_exists($name, $this->servlets)) {
			return $this->servlets[$name];
		}
		return null;
	}
	public function getServlets() {
		return $this->servlets;
	}
	
	public function addWelcomeFile($welcomeFile) {
		$this->welcomeFiles[] = $welcomeFile;
	}
	public function findWelcomeFiles() {
		return $this->welcomeFiles;
	}
	public function getWelcomeFiles() {
		return $this->findWelcomeFiles();
	}
	public function addErrorPage($errorPage) {
		$this->errorPages[] = $errorPage;
		if(!trim($errorPage->getLocation()) || substr($errorPage->getLocation(), 0, 1) != '/') {
			throw new Aloi_Serphlet_Exception_IllegalArgument("servletConfig->errorPage->location url location not valid");
		}
		if(trim($errorPage->getExceptionType())) {
			// We have an exception
			$this->exceptionPages[$errorPage->getExceptionType()] = $errorPage;
		} else {
			$this->statusPages[$errorPage->getErrorCode()] = $errorPage;
		}
	}
	
    /**
     * Return the error page entry for the specified HTTP error code, or
     * PHP Exception Type if any; otherwise return <code>null</code>.
     *
     * @param errorCodeOrExceptionType Error code/Exception Type to look up
     */
    public function findErrorPage($errorCodeOrExceptionType) {
        if(is_numeric($errorCodeOrExceptionType)) {
        	$errorCode = intval($errorCodeOrExceptionType);
	    	if (($errorCode >= 400) && ($errorCode < 600) && !empty($this->statusPages[$errorCode])) {
	            return $this->statusPages[$errorCode];
	        }
        } else {
        	$exceptionType = $errorCodeOrExceptionType;
        	if(!empty($this->exceptionPages[$exceptionType])) {
        		return $this->exceptionPages[$exceptionType];
        	}
        }
    }

    /**
     * Return the set of defined error pages for all specified error codes
     * and exception types.
     */
    public function findErrorPages() {
		return array_merge($this->exceptionPages, $this->statusPages);
    }
}