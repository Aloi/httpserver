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

class Aloi_Routes_Filter extends Aloi_Serphlet_Filter {
	const DEFAULT_CONFIG_FILE_LOCATION = 'WEB-INF/routes.php';
	const ROUTES_ATTR = 'Aloi_Routes_Filter_ROUTES_ATTR';
	
	public function doFilter(Aloi_Serphlet_Application_HttpRequest $request, Aloi_Serphlet_Application_HttpResponse $response, Aloi_Serphlet_Filter_Chain $chain) {
		$log = Aloi_Util_Logger_Manager::getLogger(__CLASS__);
		
		// Obtain the init parameter from the servlet parameter
		$configFile = $this->getFilterConfig()->getInitParameter('config');
		if(empty($configFile)) $configFile = self::DEFAULT_CONFIG_FILE_LOCATION;
		
		// Process the request
 		$routes = Aloi_Routes::factory();
		
		// Includes the routes
		if(is_readable($configFile)) {
 			require($configFile);
		} else {
			$log->error('Can not read the config file ' . $configFile);
			throw new Aloi_Serphlet_Exception('Routes file can not be found');
		}
		
 		$routes->process($request, $response);
 		$request->setAttribute(self::ROUTES_ATTR, $routes);
 		
 		// Process the application
		$chain->doFilter($request, $response, $chain);
	}
}