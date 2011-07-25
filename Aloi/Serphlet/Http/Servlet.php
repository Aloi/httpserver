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
 * A HTTP Servlet that provides methods for handling a HTTP Request/Response
 *
 * Implement the corresponding request method (e.g. doGet/doHead etc)
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
abstract class Aloi_Serphlet_Http_Servlet extends Aloi_Serphlet_GenericServlet {
	const METHOD_DELETE = "DELETE";
    const METHOD_HEAD = "HEAD";
    const METHOD_GET = "GET";
    const METHOD_OPTIONS = "OPTIONS";
    const METHOD_POST = "POST";
    const METHOD_PUT = "PUT";
    const METHOD_TRACE = "TRACE";
    
    const HEADER_IFMODSINCE = "If-Modified-Since";
    const HEADER_LASTMOD = "Last-Modified";
    
	protected function doGet(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Http_Response $response) {
		$protocol = $request->getProtocol();
		$message = 'method not supported';
		if(substr($protocol, -3) == '1.1') {
			$response->sendError(Aloi_Serphlet_Application_HttpResponse::SC_METHOD_NOT_ALLOWED, $message);
		} else {
			$response->sendError(Aloi_Serphlet_Application_HttpResponse::SC_BAD_REQUEST, $message);
		}
	}
	
    public function getLastModified(Aloi_Serphlet_Http_Request $request) {
    	return -1;
    }
	
	protected function doHead(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Http_Response $response) {
		$this->doGet($request, $response);
		// Set no body
		$repsonse->resetBuffer();
		$response->setContentLength(0);
	}
    
	protected function doPost(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Http_Response $response) {
		$protocol = $request->getProtocol();
		$message = 'Request method not supported';
		if(substr($protocol, -3) == '1.1') {
			$response->sendError(Aloi_Serphlet_Application_HttpResponse::SC_METHOD_NOT_ALLOWED, $message);
		} else {
			$response->sendError(Aloi_Serphlet_Application_HttpResponse::SC_BAD_REQUEST, $message);
		}
	}
    
	protected function doPut(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Http_Response $response) {
		$protocol = $request->getProtocol();
		$message = 'Request method not supported';
		if(substr($protocol, -3) == '1.1') {
			$response->sendError(Aloi_Serphlet_Application_HttpResponse::SC_METHOD_NOT_ALLOWED, $message);
		} else {
			$response->sendError(Aloi_Serphlet_Application_HttpResponse::SC_BAD_REQUEST, $message);
		}
	}
	
	protected function doDelete(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Http_Response $response) {
	$protocol = $request->getProtocol();
		$message = 'Request method not supported';
		if(substr($protocol, -3) == '1.1') {
			$response->sendError(Aloi_Serphlet_Application_HttpResponse::SC_METHOD_NOT_ALLOWED, $message);
		} else {
			$response->sendError(Aloi_Serphlet_Application_HttpResponse::SC_BAD_REQUEST, $message);
		}
	}
    
    protected function doOptions(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Http_Response $response) {
    	// Use reflection to identify the get_class
    	throw new Aloi_Serphlet_Exception('Method not implemented');
    }
    
    protected function doTrace(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Http_Response $response) {
    	throw new Aloi_Serphlet_Exception('Method not implemented');
    }
        
    public function service(Aloi_Serphlet_Request $request, Aloi_Serphlet_Response $response) {
    	$method = $request->getMethod();
    	if($method == self::METHOD_GET) {
    		$lastModified = $this->getLastModified($request);
    		if($lastModified == -1) {
    			$this->doGet($request, $response);
    		} else {
    			$ifModifiedSince = $request->getDateHeader(self::HEADER_IFMODSINCE);
    			if($ifModifiedSince < ($lastModified / 1000 * 1000)) {
    				$this->maybeSetLastModified($response, $lastModified);
    				$this->doGet($request, $response);
    			} else {
    				$response->setStatus(Aloi_Serphlet_Application_HttpResponse::SC_NOT_MODIFIED);
    			}
    		}
    	} else if($method == self::METHOD_HEAD) {
    		$lastModified = $this->getLastModified($request);
    		$this->maybeSetLastModified($response, $lastModified);
    		$this->doHead($request, $response);
    	} else if($method == self::METHOD_POST) {
    		$this->doPost($request, $response);
    	}
    }
}