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
 * The Generic Servlet provide a base implementation of the
 * Aloi_Serphlet_Servlet interface.
 *
 * Use the Aloi_Serphlet_Http_Servlet class to implment HTTP
 * servlet into your application environment.
 *
 * @see Aloi_Serphlet_Http_Servlet
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
class Aloi_Serphlet_GenericServlet implements Aloi_Serphlet_Servlet {
	protected $servletConfig;
	
	/**
	 * The initialisation of the servlet, as configured in the web.xml file
	 *
	 * You are able to implement the init function to perform any initialisation
	 * functions that are used across any request to this Servlet.
	 */
	public function init(Aloi_Serphlet_Config_ServletConfig $servletConfig) {
		$this->servletConfig = $servletConfig;
	}
	
	/**
	 * The Aloi_Serphlet_Config_ServletConfig object will allow you
	 * to access domain specific configuration parameters you have configured
	 * in your web.xml file.
	 */
	public function getServletConfig() {
		return $this->servletConfig;
	}

	/**
	 * Returns the configuration of the context to the entire servlet
	 * environment. You can configure application configurations accessible
	 * for all servlet run in your application.
	 *
	 * @return Aloi_Serphlet_Config_ServletConfig configuration of the context
	 */
	public function getServletContext() {
		return $this->getServletConfig()->getServletContext();
	}
	
	/**
	 * Used to service a request to build a response.
	 */
	public function service(Aloi_Serphlet_Request $request, Aloi_Serphlet_Response $response) {
		// The class defines a specific request method
	}
	
	/**
	 * Return with information about the current servlet
	 * @TODO:
	 */
	public function getServletInfo() {
		return (null);
	}
	
	/**
	 * A function for implementing a destroy function in the servlet
	 * lifecycle
	 */
	public function destroy() {
		$this->servletConfig = null;
	}
	
}