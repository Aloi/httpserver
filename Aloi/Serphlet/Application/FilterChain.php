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
 * A concrete implementation of a Filter Chain for use in the appication
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
class Aloi_Serphlet_Application_FilterChain implements Aloi_Serphlet_Filter_Chain {
	private $filterConfigs = array();
	private $currentPosition = 0;
	
	private $servlet = null;
	
	public function doFilter(Aloi_Serphlet_Application_HttpRequest $request, Aloi_Serphlet_Application_HttpResponse $response) {
		$this->internalDoFilter($request, $response);
	}
	
	private function internalDoFilter(Aloi_Serphlet_Application_HttpRequest $request, Aloi_Serphlet_Application_HttpResponse $response) {
		// For each filter wrap until at the end, invoke, and then return back through each filter
		if($this->currentPosition < count($this->filterConfigs)) {
			$filterConfig = $this->filterConfigs[$this->currentPosition++];
			try {
				$filter = $filterConfig->getFilter();
				$filter->doFilter($request, $response, $this);
			} catch(Exception $e) {
				$log = Aloi_Util_Logger_Manager::getLogger(__CLASS__);
				$log->error('internalDoFilter caused exception ' . $e->getMessage());
				throw $e;
			}
			return;
		}
		
		// At the end of chaining, invoke the process
		Aloi_Serphlet_Application_FilterChain::servletProcess($request, $response, $this->servlet);
	}
	
	public function setServlet(Aloi_Serphlet_Servlet $servlet) {
		$this->servlet = $servlet;
	}
	
	public function addFilterConfig(Aloi_Serphlet_Config_FilterConfig $filterConfig) {
		$this->filterConfigs[] = $filterConfig;
	}
	
	public function servletProcess(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Http_Response $response, Aloi_Serphlet_Servlet $servlet) {
		$servlet->service($request, $response);
	}
	
}