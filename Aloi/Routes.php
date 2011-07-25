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
 * Aloi_Routes provides a simple implementation of Routing URLs, which
 * can remap parameters from the URL into the request. You can
 * implement your own matching methods, or use the defaults, which
 * allows you to match a number of elements similar to that found
 * in Rails.
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 */
class Aloi_Routes {
	protected $log;
	protected $mapper;
	
	// Save the current
	protected $currentRoute;
	protected $currentArguments;
	
	protected static $instance;
	
	public function getMapper() {
		if(empty($this->mapper)) $this->mapper = new Aloi_Routes_Mapper();
		return $this->mapper;
	}
	
	public function __construct() {
		$this->log = Aloi_Util_Logger_Manager::getLogger(__CLASS__);
	}
	
	public function process(Aloi_Serphlet_Application_HttpRequest $request) {
		$route = $this->getMapper()->route($request);
		if(!empty($route)) {
			// Hold the current route for later
			$this->currentRoute = $route;

			// Hold the arguments to be substitued later
			$arguments = $request->getParameters();
			$arguments['PATH_INFO'] = $request->getPathInfo();
			$this->currentArguments = $arguments;
		}
	}
	
	public function url($name, $arguments) {
		if(empty($this->log)) {
			$this->log = Aloi_Util_Logger_Manager::getLogger(__CLASS__);
		}
		
		$route = $this->getMapper()->getRoute($name);
		if(!empty($route)) {
			$url = $route->createURL($arguments);
		}
		return $url;
	}
	
	public function current($arguments) {
		$arguments = array_merge($this->currentArguments, $arguments);
		if(!empty($this->currentRoute)) {
			$url = $this->currentRoute->createURL($arguments);
		}
		return $url;
	}
	
	public function factory() {
		return new Aloi_Routes();
	}
}