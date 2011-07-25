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
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 */
class Aloi_Routes_Mapper {
	private $log;
	private $routes;
	
	public function __construct() {
		$this->log = Aloi_Util_Logger_Manager::getLogger(__CLASS__);
	}
	
	public function connect($name, $map, $arguments = array(), $requirements = array()) {
		$route = Aloi_Routes_Route_Default::createRoute($map, $arguments, $requirements);
		$this->connectRoute($name, $route);
	}
	
	public function prepend($name, $map, $arguments = array(), $requirements = array(), $before = null) {
		$route = Aloi_Routes_Route_Default::createRoute($map, $arguments, $requirements);
		$this->prependRoute($name, $route);
	}
	
	public function connectRoute($name, Aloi_Routes_Route $route) {
		$this->routes[$name] = $route;
	}
	
	public function prependRoute($name, Aloi_Routes_Route $route, $before = null) {
		if($before == null) {
			$this->routes = array_merge(array($name => $route), $this->routes);
			return true;
		}
			
		$offsetLookup = array_flip(array_keys($this->routes));
		if(!empty($offsetLookup[$before])) {
			$this->routes = array_merge(array_slice($this->routes, 0, $offsetLookup[$before]), array($name => $route), array_slice($this->routes, $offsetLookup[$before]));
			return true;
		}
	}
	
	public function route($request) {
		$match = false;
		if(empty($this->routes)) return;
		
		foreach($this->routes as $route) {
			if($route->route($request)) {
				$match = true;
				return $route;
				break;
			}
		}
	}
	
	public function getRoute($name) {
		if(!empty($this->routes[$name])) {
			return $this->routes[$name];
		} else {
			return null;
		}
	}
}