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
 * permission notice:
 *
 * Copyright 2004 The Apache Software Foundation
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * An interface for dealing with HTTP Requests made on the application
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
interface Aloi_Serphlet_Http_Request extends Aloi_Serphlet_Request {
	public function getAuthType();
	public function getCookies();
	public function getDateHeader($name);
	public function getHeader($name);
	public function getHeaders($name);
	public function getHeaderNames();
	public function getIntHeader($name);
	public function getMethod();
	
	/**
	 * Returns with any extra path information associated with the URL
	 * the client sent when it made this request. The extra path information
	 * follows the servlet path but precedes the query string and starts
	 * with a '/' character
	 * @return string Returns any extra path information associated with the URL the client sent when it made this request.
	 */
	public function getPathInfo();
	
	/**
	 * Returns any extra path information after the servlet name but before the query string,
	 * and translates it to a real path.
	 * @return string Returns any extra path information after the servlet name but before the query string, and translates it to a real path.
	 */
	public function getPathTranslated();
	
	/**
	 * Returns the portion of the request URI that indicates the context of the request. The context
	 * path always comes first in a request URI. The path starts with a "/" character but does not
	 * end with a "/" character. For servlets in the default (root) context, this method returns ""
	 * @return string Returns the portion of the request URI that indicates the context of the request.
	 */
	public function getContextPath();
	
	/**
	 * Returns the query string that is contained in the request URL after the path. This method returns null
	 * if the URL does not have a query string.
	 * @return string Returns the query string that is contained in the request URL after the path.
	 */
	public function getQueryString();
	public function getRemoteUser();
	public function isUserInRole($role);
	public function getUserPrincipal();
	public function getRequestedSessionId();
	public function getRequestURI();
	
	/**
	 * Reconstructs the URL the client used to make the request. The returned URL contains a protocol, server name,
	 * port number, and server path, but it does not include query string parameters.
	 *
	 * @return string reconstructed URL
	 */
	public function getRequestURL();
	
	/**
	 * Returns the part of this request's URL that calls the servlet. This path starts with a "/" character
	 * and includes either the servlet name or a path to the servlet, but does
	 * not include any extra path information or a query string
	 *
	 * This method will return an empty string ("") if the servlet used to process this request was
	 * matched using the "/*" pattern.
	 * @return string a String containing the name or path of the servlet being called, as specified in the request URL, decoded,
	 * or an empty string if the servlet used to process the request is matched using the "/*" pattern.
	 */
	public function getServletPath();
	public function getSession($create = true);
	public function isRequestedSessionIdValid();
	public function isRequestedSessionIdFromCookie();
	public function isRequestedSessionIdFromURL();
	
	//
	public function addParameter($name, $values);
	public function clearCookies();
	public function clearHeaders();
	public function clearLocales();
	public function clearParameters();
	public function setAuthType();
	
	/**
	 * Sets the context path for this request
	 * @param $path
	 */
	public function setContextPath($path);
	public function setMethod($method);
	public function setQueryString($query);
	
	/**
	 * Sets the path info for this request
	 * @param $path
	 */
	public function setPathInfo($path);
	public function setRequestedSessionId($id);
	public function setRequestedSessionURL($flag);
	public function setRequestURI($uri);
	
	/**
	 * Sets the servlet path for this request
	 * @param $path
	 */
	public function setServletPath($path);
	public function setUserPrinciple($principle);
}