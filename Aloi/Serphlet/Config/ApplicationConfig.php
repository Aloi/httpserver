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
 * 
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
class Aloi_Serphlet_Config_ApplicationConfig implements Aloi_Serphlet_Config_ServletConfig {
	private $servletName;
	private $servletContext;
	private $parameters;
	private $servletClass;
	
	private $mappings;
	private $servletMapping;
	
	public function setServletClass($servletClass) {
		$this->servletClass = $servletClass;
	}
	public function getServletClass() {
		return $this->servletClass;
	}
	public function getServletName() {
		return $this->servletName;
	}
	public function setServletName($servletName) { 
		$this->servletName = $servletName;
	}
	
	public function getServletContext() {
		return $this->servletContext;
	}
	public function setServletContext($servletContext) {
		$this->servletContext = $servletContext;
	}
	
	
	/**
	 * Returns a string containing the value of the named initialization
	 * parameter, or null if the parameter does not exist.
	 *
	 * @param string $name The name of the initialization parameter
	 * @return string
	 */
	public function getInitParameter($name) {
		$name = ($name);
		if (is_array($this->parameters) && array_key_exists($name, $this->parameters)) {
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
		if(!empty($this->parameters))
			return array_keys($this->parameters);
		else 
			return null;
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
	 * Adds a possible mapping for mapping this servlet to the request
	 * @param unknown_type $mapping
	 */
	public function addMapping($mapping) {
		$this->mappings[] = $mapping;
	}
	
	/**
	 * Returns with all the configured mappings
	 */
	public function getMappings() {
		return $this->mappings;
	}
	
	/**
	 * Returns with the servlet mapping used to map this request
	 */
	public function getServletMapping() {
		return $this->servletMapping;
	}
	/**
	 * Sets the servlet mapping used to map this request
	 * @param string $value
	 */
	public function setServletMapping($value) {
		$this->servletMapping = $value;
	}
}