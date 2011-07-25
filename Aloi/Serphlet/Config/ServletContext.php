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
 * 
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
interface Aloi_Serphlet_Config_ServletContext {
	public function getContextPath();
	public function getContext($uriPath);
	public function getMajorVersion();
	public function getMinorVersion();
	public function getMimeType($file);
	public function getResourcePaths($path);
	public function getResource($path);
	public function getResourceAsStream($path);
	public function getRequestDispatcher($path);
	public function getNamedDispatcher($name);
	public function getServlet($name);
	public function getServlets();
	public function getServletNames();
	public function log($message, Exception $throwable);
	public function getRealPath($path);
	public function getServerInfo();
	public function getInitParameter($name);
	public function getInitParameterNames();
	public function getAttribute($name);
	public function getAttributeNames();
	public function setAttribute($name, $object);
	public function removeAttribute($name);
	public function getServletContextName();
	
	
	
}