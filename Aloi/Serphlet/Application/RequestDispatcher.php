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
  * Copyright (C) 2008 PHruts
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
 * Defines an object that receives requests from the client and sends them to
 * any resource (such as a HTML or PHP file) on the server.
 *
 * @author Olivier HENRY <oliv.henry@gmail.com> (PHP5 port of Struts)
 * @author John WILDENAUER <jwilde@users.sourceforge.net> (PHP4 port of Struts)
 * @version $Id$
 */
class Aloi_Serphlet_Application_RequestDispatcher implements Aloi_Serphlet_Request_Dispatcher {
	/**
	 * Commons Logging instance.
	 *
	 * @var Logger
	 */
	protected static $log = null;

	/**
	 * The context this RequestDispatcher is associated with.
	 *
	 * @var ServletContext
	 */
	protected $context = null;

	/**
	 * The request URI for this RequestDispatcher.
	 *
	 * @var string
	 */
	protected $requestURI = null;

	/**
	 * Construct a new instance of this class, configured according to the
	 * specified parameters.
	 *
	 * @param ServletContext $context The context this
	 * RequestDispatcher is associated with
	 * @param string $requestURI The request URI for this RequestDispatcher
	 */
	public function __construct(Aloi_Serphlet_Config_ServletContext $context) {
		$this->context = $context;

		if (is_null(self :: $log)) {
			self :: $log = Aloi_Util_Logger_Manager :: getLogger(__CLASS__);
		}
	}

	public function __wakeup() {
		if (is_null(self :: $log)) {
			self :: $log = Aloi_Util_Logger_Manager :: getLogger(__CLASS__);
		}
	}

	/**
	 * @return string
	 */
	public function getRequestURI() {
		return $this->requestURI;
	}

	/**
	 * @param string $requestURI
	 */
	public function setRequestURI($requestURI) {
		$this->requestURI = (string) $requestURI;
	}

	/**
	 * Forward this request and response to another resource for processing.
	 *
	 * @param Aloi_Serphlet_Request $request The servlet request to be
	 * forwarded
	 * @param Aloi_Serphlet_Response $response The servlet response to be
	 * forwarded
	 */
	public function doForward(Aloi_Serphlet_Request $request, Aloi_Serphlet_Response $response) {

		// Reset any output that has been buffered, but keep headers/cookies
		if ($response->isCommitted()) {
			if (self :: $log->isDebugEnabled()) {
				self :: $log->error('Forward on committed response');
			}
			throw new IllegalStateException('Cannot forward after response has been committed');
		}
		$response->resetBuffer();

		$this->invoke($request, $response);
		
		Aloi_Serphlet_Application_RequestDispatcherForward::commit($request, $response, $this->context);
	}

	/**
	 * Include the response from another resource in the current response.
	 *
	 * @param Aloi_Serphlet_Request $request The servlet request that is
	 * including this one
	 * @param Aloi_Serphlet_Response $response The servlet response to be
	 * appended to
	 */
	public function doInclude(Aloi_Serphlet_Request $request, Aloi_Serphlet_Response $response) {
		$this->invoke($request, $response);
	}

	/**
	 * Ask the resource represented by this RequestDispatcher to process
	 * the associated request, and create (or append to) the associated response.
	 *
	 * @param Aloi_Serphlet_Request $request The servlet request we are
	 * processing
	 * @param Aloi_Serphlet_Response $response The servlet response we are
	 * creating
	 * @todo Manage exception if the resource doesn't exist.
	 */
	protected function invoke(Aloi_Serphlet_Request $request, Aloi_Serphlet_Response $response) {

		$requestURI = urldecode($this->requestURI);
		$urls = @ parse_url($requestURI);
		if (array_key_exists('query', $urls)) {
			parse_str($urls['query'], $_GET);
		}
		$path = $this->context->getRealPath($urls['path']);

		$_REQUEST = array_merge($_GET, $_POST, $_COOKIE);

		$fileExists = @fopen($path, 'r', true);
		if (!$fileExists) {
			self::$log->error('Resource ' . $path . ' is not found');
			throw new Aloi_Serphlet_Exception_Unavailable('The resource is currently unavailable');
		} else fclose($fileExists);
		
		if ($response->getAutoflush()) {
			require ($path);
		} else {
			ob_start();
			require $path;
			$response->write(ob_get_contents());
			ob_end_clean();
		}
	}
}