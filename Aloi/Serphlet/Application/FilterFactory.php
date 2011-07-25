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
 */

/**
 * A class used for createing Filter chains. Filter chains
 * allow multiple filters to be 'chained' together and wrap one
 * another. Chains are built based on those filters that match
 * the current request.
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
class Aloi_Serphlet_Application_FilterFactory {
	private static $filterFactory = null; // For us of our singleton
	
	private function __constuct() {}

	public static function getInstance() {
		if(empty(self::$filterFactory)) self::$filterFactory = new Aloi_Serphlet_Application_FilterFactory();
		return self::$filterFactory;
	}


	public function createFilterChain(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Servlet $servlet) {
		// Create and initialize a filter chain object
		$filterChain = null;
		$filterMaps = $servlet->getServletConfig()->getServletContext()->findFilterMaps();

		// If there are no filter mappings, we are done
		if (($filterMaps == null) || (count($filterMaps) == 0))
		return (null);

		// Get the path
//		$requestPath = $request->getParameter($servlet->getServletContext()->getServletConfig()->getPathParam());
		$requestPath = $request->getPathInfo();
		
		// Add the relevant path-mapped filters to this filter chain
		$n = 0;
		foreach($filterMaps as $filterMap) {
			if (!$this->matchFiltersURL($filterMap, $requestPath, true))
			continue;
			try {
				$filterConfig = $servlet->getServletContext()->findFilterConfig($filterMap->getFilterName());
				if ($filterConfig == null) {
					throw new Exception('Could not locate the filter config for the supplied filter name ' . $filterMap->getFilterName());
				}
				
				if ($filterChain == null)
				$filterChain = $this->internalCreateFilterChain($request, $servlet);
				$filterChain->addFilterConfig($filterConfig);
				$n++;
				
			} catch(Exception $e) {
				$log = Aloi_Util_Logger_Manager::getLogger(__CLASS__);
				$log->error('createFilterChain() caused exception ' . $e->getMessage());
				continue;
			}
		}
		
		// Add filters that match on servlet name second
		foreach($filterMaps as $filterMap) {
			if(!$this->matchFiltersServlet($filterMap, $servlet->getServletConfig()->getServletName()))
			continue;
			$filterConfig = $servlet->getServletContext()->findFilterConfig($filterMap->getFilterName());
			if ($filterConfig == null) {
				$log = Aloi_Util_Logger_Manager::getLogger(__CLASS__);
				$log->error('createFilterChain() caused exception ' . $e->getMessage());
				continue;
			}
			if ($filterChain == null)
			$filterChain = $this->internalCreateFilterChain($request, $servlet);
			$filterChain->addFilterConfig($filterConfig);
			$n++;
		}

		// Return the completed filter chain
		return ($filterChain);
	}

	private function matchFiltersURL(Aloi_Serphlet_Config_FilterMap $filterMap, $requestPath, $caseSensitiveMapping = true) {
		if ($requestPath == null)
			return (false);

		// Match on context relative request path
		$testPath = $filterMap->getURLPattern();
		if ($testPath == null)
		return (false);

		if (!$caseSensitiveMapping) {
			$requestPath = strtolower($requestPath);
			$testPath = strtolower($testPath);
		}

		// Case 1 - Exact Match
		if ($testPath == $requestPath)
			return (true);

		// Case 2 - Path Match ("/.../*")
		if ($testPath == '/*') return (true);
		if (preg_match('/\/\*$/', $testPath)) {
			if (substr($testPath, 0, strlen($testPath) -2) == substr($requestPath, 0, strlen($testPath) - 2)) {
				if (strlen($requestPath) == (strlen($testPath) - 2)) {
					return (true);
				} else if ('/' == substr($requestPath, strlen($testPath) -2, 1)) {
					return (true);
				}
			}
			return (false);
		}

		// Case 3 - Extension Match
		if (preg_match('/^\*\./', $testPath)) {
			$slash = strrpos($requestPath, '/');
			$period = strrpos($requestPath, '.');
			if (($slash >= 0) && ($period > $slash)
			&& ($period != (strlen($requestPath) - 1))
			&& ((strlen($requestPath) - $period)
			== (strlen($testPath) - 1))) {
				return (substr($testPath, 2) == substr($requestPath, $period));
			}
		}

		// Case 4 - "Default" Match
		return (false); // NOTE - Not relevant for selecting filters

	}

	private function matchFiltersServlet(Aloi_Serphlet_Config_FilterMap $filterMap, $servletName) {
		if ($servletName == null) {
			return (false);
		} else {
			if ($servletName == $filterMap->getServletName() || $filterMap->getServletName() == '*') {
				return (true);
			} else {
				return false;
			}
		}
	}

	private function internalCreateFilterChain(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Servlet $servlet) {
		$filterChain = new Aloi_Serphlet_Application_FilterChain();
		$filterChain->setServlet($servlet);
		return $filterChain;
	}
}