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
 * A Filter is an element that can wrap your application before a Serphlet
 * is run. A Filter can be chained together so that multiple filters run across
 * your application and can do something for the request is processed or before
 * the response is returned.
 *
 * This can be useful for implementing modular reusable code, such as implementing
 * security, creating a caching mechanism, performing transformations etc.
 *
 * You can map filters together to map different parts of your application using
 * the web.xml
 *
 * To write a filter, you will need to extend this class and implement the doFilter,
 * init and destroy functions.
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
abstract class Aloi_Serphlet_Filter {
	protected $filterConfig;
	
	/**
	 * Returns with the Aloi_Serphlet_Config_FilterConfig object that
	 * has been configured for the current filter. The configuration
	 * allows you to access the web.xml configuration settings, allowing
	 * you to modify the filter configuration for reusability, changing
	 * properties depending on how your want to use it in your application.
	 * @return Aloi_Serphlet_Config_FilterConfig
	 */
	public function getFilterConfig() {
		return $this->filterConfig;
	}
	
	/**
	 * Sets the configuration of this filter config
	 * @param $filterConfig
	 */
	private function setFilterConfig(Aloi_Serphlet_Config_FilterConfig $filterConfig) {
		$this->filterConfig = $filterConfig;
	}
	
	/**
	 * An initialisation function which is called when the filter is
	 * instantiated before use. It allows you to configure your environment
	 * before the doFilter stage.
	 */
	public function init(Aloi_Serphlet_Config_FilterConfig $filterConfig) {
		$this->setFilterConfig($filterConfig);
	}
	
	/**
	 * The doFilter function is the main function used to contain your application
	 * filter logic.
	 *
	 * You must call doFilter() on the filter chain to continue running your application,
	 * alternatively you can not which will stop the execution of your application.
	 */
	public function doFilter(Aloi_Serphlet_Application_HttpRequest $request, Aloi_Serphlet_Application_HttpResponse $response, Aloi_Serphlet_Filter_Chain $chain) {}
	
	/**
	 * At the end of the lifecycle of the filter the destroy method is called
	 * so that you can perform any required cleanup before shutdown.
	 */
	public function destroy() {
		$this->filterConfig = null;
	}
}