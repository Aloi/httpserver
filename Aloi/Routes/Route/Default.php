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
 * The default routes class
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 */
class Aloi_Routes_Route_Default implements Aloi_Routes_Route {
	protected $log;

	// The backbone regexp
	protected $regExp;
	
	// The path and arguments
	protected $path;
	protected $arguments;
	
	// Regexp's
	protected $requirementsList = array();
	protected $orderMatching = array();
	
	protected $extracedRequirements = false;
	
	public function __construct($path, $arguments = array(), $requirementsList = array()) {
		$this->path = $path;
		$this->arguments = $arguments;
		$this->requirementsList = $requirementsList;
		
		$this->log = Aloi_Util_Logger_Manager::getLogger(__CLASS__);
	}
	
	public function url($arguments, $ignoreRequirementsList = false, $appendSlash = false) {
		// Append the path to the URL
		$url = ($appendSlash ? '/' : '') . $this->path;
		
		// Replace back the current arguments
		$arguments = array_merge($this->arguments, $arguments);
		
		// Obtain substituable values
		preg_match_all('/{([^}]+)}/', $url, $matches);
		
		// Extract the requirements
		
		if($this->extractedRequirements && !$ignoreRequirementsList) {
			if(!empty($matches)) {
				$dynamicMatches = !empty($matches[1]) ? $matches[1] : array();
				$this->log->info('Found ' . count($dynamicMatches) . ' substitutions: ' . implode(', ', $dynamicMatches));
				
				// Identify the dynamic elements
				foreach($dynamicMatches as $match) {
					$pattern = $match;
					
					// Extract out any requirements
					if(strpos($match, ':') > -1) {
						// We have a requirement present, extract into the requirements list
						$requirement = split(':', $match, 2);
						$match = $requirement[0];
						$this->requirementsList[$match] = $requirement[1];
					} else if(empty($this->requirementsList[$match])) {
						$this->requirementsList[$match] = '[^\/]+';
					}
				}
			}
			$this->extractedRequirements = true;
		}
		
		// Check the requirements
		foreach($this->requirementsList as $name => $requirement) {
			$value = (!empty($arguments[$name]) ? $arguments[$name] : ''); // TODO: Update to allow extraction from object (using get/set or direct?)
			if(!trim($value) || !preg_match('/^' . $requirement . '$/', $value)) {
				$this->log->error('The required argument ' . $name . ' does not parse the requirements');
				throw new Exception('Argument ' . $name . ' does not parse requirements');
			}
		}
		
		// Substitute the values
		if(!empty($matches[1])) {
			// We have substitutions
			for($x = 1; $x < count($matches); $x++) {
				$name = $matches[$x];
				$value = (!empty($arguments[$name]) ? $arguments[$name] : ''); // TODO: Update to allow extraction from object (using get/set or direct?)
				$url = preg_replace('/{' . $name . '([^}]*)}/', urlencode($value), $url);
			}
		}
		
		// Return the URL
		return $value;
	}
	
	protected function getMatchRegExp() {
		if(empty($this->regExp)) {
			$regExp = str_replace('/', '\/', $this->path);
			// Obtain all the dynamic elements
			preg_match_all('/{([^}]+)}/', $this->path, $matches);
			if(!empty($matches)) {
				$dynamicMatches = !empty($matches[1]) ? $matches[1] : array();
				foreach($dynamicMatches as $match) {
					$pattern = $match;
					
					// Extract out any requirements
					if(strpos($match, ':') > -1) {
						// We have a requirement present, extract into the requirements list
						$requirement = split(':', $match, 2);
						$match = $requirement[0];
						$this->requirementsList[$match] = $requirement[1];
					} else if(empty($this->requirementsList[$match])) {
						$this->requirementsList[$match] = '[^\/]+';
					}
					// Extract out the order
					$this->orderMatching[] = $match;

					// Substitute the value back in
					$regExp = preg_replace('/{' . $match . '([^}]*)}/', '(' . $this->requirementsList[$match] . ')', $regExp);
				}
			}
			
			// Generate
			$this->regExp = '/^' . $regExp . '$/i';
		};
		return $this->regExp;
	}
	
	public function route(Aloi_Serphlet_Application_HttpRequest $request) {
		// Return if we match
		$path = $request->getPathInfo();
		$match = (preg_match($this->getMatchRegExp(), $path));
		if(!$match) return false;
		
		// Assuming we have matched a route, it is time to reroute it in the request
		if(!empty($this->orderMatching)) {
			// We have variables to draw
			preg_match($this->getMatchRegExp(), $path, $matches);
			if(!empty($matches[1])) {
				// We have substitutions
				for($x = 1; $x < count($matches); $x++) {
					$this->saveRequestArgument($request, $this->orderMatching[$x - 1], $matches[$x]);
				}
			}
		}
		// Process the parameters into the request
		foreach($this->arguments as $argument => $value) {
			$this->saveRequestArgument($request, $argument, $value);
		}
	}
	
	protected function saveRequestArgument($request, $argument, $value) {
		switch($argument) {
			case 'PATH_INFO':
				$this->log->debug('Assigning PATH_INFO(request): ' . $value);
				if(substr($value, 0, 1) != '/') $value = '/' . $value;
				$request->setPathInfo($value);
				break;
			default:
				$this->log->debug('Assigning ' . $argument . ': ' . $value);
				$request->setParameter($argument, $value);
				break;
		}
	}
	
	public static function createRoute($map, $arguments, $requirements) {
		return new Aloi_Routes_Route_Default($map, $arguments, $requirements);
	}
}