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
 */

/**
 * A concrete implementation of a HTTP Request class that maps
 * the framework to the PHP environment.
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
class Aloi_Serphlet_Application_HttpRequest implements Aloi_Serphlet_Http_Request {
	public static $BASIC_AUTH = "BASIC";
	public static $FORM_AUTH = "FORM";
	public static $CLIENT_CERT_AUTH = "CLIENT_CERT";
	public static $DIGEST_AUTH = "DIGEST";
	
	protected $attributes = array();
	protected $parameters = array();
	
	protected $locales = array();
	protected $localesParsed = false;
	
	protected $cookies = array();
	protected $session = null;
	
	protected $servletPath;
	protected $pathInfo;
	
	public function __construct() {
		// Set the cookies
		foreach($_COOKIE as $name => $value) {
			$cookie = new Aloi_Serphlet_Http_Cookie($name, $value);
			$this->cookies[] = $cookie;
		}
		
		// Parameters
		foreach($_REQUEST as $parameter => $value) {
			$this->setParameter($parameter, $value);
		}
	}
	
	public function getAuthType() {
		if(isset($_SERVER['AUTH_TYPE']) && $_SERVER['AUTH_TYPE'] != '') {
			return strtoupper($_SERVER['AUTH_TYPE']);
		}
	}
	public function getCookies() {
		if(count($this->cookies) == 0) return null;
		return $this->cookies;
	}
	public function getCookie($nae) {
		// TODO:
	}
	
	public function getDateHeader($name) {
		// Return the 'If-Modified-Since' and 'Last-Modified' header as a time since 1970
		if(in_array(strtolower($name), array('IF-MODIFIED-SINCE', 'LAST-MODIFIED')) && !empty($_SERVER[$name]))  {
			return strtotime($_SERVER[$name]);
		}
	}
	public function getHeader($name) {
		$name = (string) $name;
		if(array_key_exists($name, $_SERVER)) {
			return $_SERVER[$name];
		}
	}
	public function getHeaders($name) {
		// TODO:
		return $this->getHeader($name);
	}
	public function getHeaderNames() {
		return array_keys($_SERVER);
	}
	public function getIntHeader($name) {
		
	}
	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	/**
	 * Returns with any extra path information associated with the URL
	 * the client sent when it made this request. The extra path information
	 * follows the servlet path but precedes the query string and starts
	 * with a '/' character
	 * @return string Returns any extra path information associated with the URL the client sent when it made this request.
	 */
	public function getPathInfo() {
		return $this->pathInfo;
	}
	public function setPathInfo($pathInfo) {
		$this->pathInfo = $pathInfo;
	}
	
	/**
	 * Returns any extra path information after the servlet name but before the query string,
	 * and translates it to a real path.
	 * @return string Returns any extra path information after the servlet name but before the query string, and translates it to a real path.
	 */
	public function getPathTranslated() {
		
	}
	
	/**
	 * Returns the portion of the request URI that indicates the context of the request. The context
	 * path always comes first in a request URI. The path starts with a "/" character but does not
	 * end with a "/" character. For servlets in the default (root) context, this method returns ""
	 * @return string Returns the portion of the request URI that indicates the context of the request.
	 */
	public function getContextPath() {
		$requestURI = $this->getRequestURI();
		$serverPathInfo = !empty($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
		$contextPath = substr($requestURI, 0, strrpos($requestURI, $serverPathInfo));

		// Identify if the script name is in the URL (dealing with index.php/path_info)
		if(strpos($contextPath, $_SERVER['SCRIPT_NAME']) > -1) {
			// The script name is in the request URI
			$script = basename($_SERVER['SCRIPT_NAME']);
			$contextPath = substr($contextPath, 0, strrpos($contextPath, $script));
		}
		return $contextPath;
	}
	
	/**
	 * Returns the query string that is contained in the request URL after the path. This method returns null
	 * if the URL does not have a query string.
	 * @return string Returns the query string that is contained in the request URL after the path.
	 */
	public function getQueryString() {
		
	}
	public function getRemoteUser() {
		if(isset($_SERVER['REMOTE_USER']) && $_SERVER['REMOTE_USER'] != '') {
			return $_SERVER['REMOTE_USER'];
		}
	}
	public function isUserInRole($role) {
		return true;
	}
	public function getUserPrincipal() {
		
	}
	public function getRequestedSessionId() {
		
	}
	public function getRequestURI() {
		return $_SERVER['REQUEST_URI'];
	}
	
	/**
	 * Reconstructs the URL the client used to make the request. The returned URL contains a protocol, server name,
	 * port number, and server path, but it does not include query string parameters.
	 *
	 * @return string reconstructed URL
	 */
	public function getRequestURL() {
		$scheme = $this->getScheme();
		$port = $this->getServerPort();
		$url = $scheme . '://';
		$url .= $this->getServerName();
		if(($scheme == 'http' && $port != 80) || ($scheme == 'https' && $port != 443)) {
			$url .= ':' . $port;
		}
		$url .= $this->getRequestURI();
		return $url;
	}
	
	/**
	 * Returns the part of this request's URL that calls the servlet. This path starts with a "/" character
	 * and includes either the servlet name or a path to the servlet, but does
	 * not include any extra path information or a query string
	 *
	 * This method will return an empty string ("") if the servlet used to process this request was
	 * matched using the "/*" pattern.
	 * @return string a String containing the name or path of the servlet being called, as specified in the request URL, decoded,
	 * or an empty string if the servlet used to process the request is matched using the "/*" pattern.
	 */
	public function getServletPath() {
		return $this->servletPath;
	}
	public function setServletPath($servletPath) {
		$this->servletPath = $servletPath;
	}
	
	public function getSession($create = true) {
		$create = (boolean)$create;
		if(empty($this->session)) {
			if($create == true) {
				$this->session = new Aloi_Serphlet_Http_Session();
			}
		}
		return $this->session;
	}
	public function isRequestedSessionIdValid() {
		
	}
	public function isRequestedSessionIdFromCookie() {
		
	}
	public function isRequestedSessionIdFromURL() {
		
	}
	public function getAttribute($name) {
		$name = (string) $name;
		if(array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		}
	}
    public function getAttributeNames() {
    	return array_keys($this->attributes);
    }
    public function getCharacterEncoding() {
    	
    }
    public function setCharacterEncoding($env) {
    	
    }
    public function getContentLength() {
    	if(isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] != '') {
    		return $_SERVER['CONTENT_LENGTH'];
    	} else {
    		return -1;
    	}
    }
    public function getContentType() {
    	if(isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] != '') {
    		return $_SERVER['CONTENT_TYPE'];
    	}
    }
    public function getInputStream() {
    	
    }
    public function getParameter($name) {
    	$name = (string) $name;
		if(array_key_exists($name, $this->parameters)) {
			return $this->parameters[$name];
		}
    }
    public function getParameters() {
    	return $this->parameters;
    }
    public function setParameter($name, $value) {
    	$this->parameters[$name] = $value;
    }
    public function getParameterNames() {
    	return array_keys($this->parameters);
    }
    public function getParameterValues($name) {
    	
    }
    public function getParameterMap() {
    	
    }
    public function getProtocol() {
    	return $_SERVER['SERVER_PROTOCOL'];
    }
    public function getScheme() {
    	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
    		return 'https';
    	} else {
    		return 'http';
    	}
    }
    public function getServerName() {
    	return $_SERVER['SERVER_NAME'];
    }
    public function getServerPort() {
    	return (integer) $_SERVER['SERVER_PORT'];
    }
    public function getReader() {
    	
    }
    public function getRemoteAddr() {
    	return $_SERVER['REMOTE_ADDR'];
    }
    public function getRemoteHost() {
    	
    }
    public function setAttribute($name, $value) {
    	if(is_null($value)) {
    		$this->removeAttribute($name);
    	} else {
    		$name = (string)$name;
    		$this->attributes[$name] = $value;
    	}
    }
    public function removeAttribute($name) {
    	$name = (string)$name;
    	unset($this->attributes[$name]);
    }
    public function getLocale() {
    	$this->parseLocales();
    	if(is_array($this->locales) && count($this->locales) > 0) {
    		return $this->locales[0];
    	}
    }
    public function getLocales() {
    	$this->parseLocales();
    	return $this->locales;
    }
    private function parseLocales() {
    	if($this->localesParsed) return;
    	
    	// Parse the locales
    	$acceptLanguages = split(',', $this->getHeader('HTTP_ACCEPT_LANGUAGE'));
    	$locales = array();
    	foreach($acceptLanguages as $acceptLanguage) {
    		// Check the quality
    		$quality = 1;
    		$qualityPos = strpos($acceptLanguage, ';q=');
    		if($qualityPos > 0) {
    			$quality = floatval(substr($acceptLanguage, $qualityPos + 3));
    			$acceptLanguage = substr($acceptLanguage, 0, $qualityPos);
    		}
    		
    		// Only deal with quality parts
    		if($quality < 0.00005) continue;
    		if($acceptLanguage == '*') continue;
    		
    		// Extract the parts
    		$countryDash = strpos($acceptLanguage, '-');
    		if($countryDash > 0) {
    			// We have specified a country
    			$language = substr($acceptLanguage, 0, $countryDash);
    			$country = substr($acceptLanguage, $countryDash + 1);
    			$variantDash = strpos($country, '-');
    			if($variantDash > 0) {
    				// We have a variant
    				$variant = substr($country, $variantDash + 1);
    				$country = substr($country, 0, $variantDash);
    			} else {
    				$variant = "";
    			}
    		} else {
    			$language = $acceptLanguage;
    			$country = "";
    			$variant = "";
    		}
    		
    		// Place into the collection
    		$locale = new Aloi_Util_Locale($language, $country, $variant);
    		
    		// Minor increment the quality until we avoid a duplicate
    		$quality = $quality * -100000;
    		while(!empty($locales[$quality])) $quality += 1; // FIXME

    		$locales[$quality] = $locale;
    	}
    	ksort($locales, SORT_NUMERIC);
    	$this->locales = array_values($locales);
    	$this->localesParsed = true;
    }
    public function isSecure() {
    	return ($this->getScheme() == 'https');
    }
    public function getRequestDispatcher($path) {
    	
    }
    public function getRemotePort() {
    	return (integer) $_SERVER['REMOTE_PORT'];
    }
    public function getLocalName() {
    	
    }
    public function getLocalAddr() {
    	
    }
    public function getLocalPort() {
    	
    }
    //
	public function addParameter($name, $values) {
		
	}
	public function clearCookies() {
		// TODO:
	}
	public function clearHeaders() {
		
	}
	public function clearLocales() {
		
	}
	public function clearParameters() {
		$this->parameters = array();
	}
	public function setAuthType() {
		
	}
	
	/**
	 * Sets the context path for this request
	 * @param $path
	 */
	public function setContextPath($path) {
		
	}
	public function setMethod($method) {
		
	}
	public function setQueryString($query) {
		
	}
	
	public function setRequestedSessionId($id) {
		
	}
	public function setRequestedSessionURL($flag) {
		
	}
	public function setRequestURI($uri) {
		
	}
	
	/**
	 * Sets the servlet path for this request
	 * @param $path
	 */
	public function setUserPrinciple($principle) {
		
	}
    
    public function __destruct() {
	   	if(!is_null($this->session)) {
    		$this->session->commit();
    	}
    }
}