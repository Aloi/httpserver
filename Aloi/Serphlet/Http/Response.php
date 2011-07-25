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
 * An interface for dealing with HTTP Responses made to the application
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
interface Aloi_Serphlet_Http_Response extends Aloi_Serphlet_Response {
	public function addCookie(Aloi_Serphlet_Http_Cookie $cookie);
	public function addDateHeader($name, $date);
	public function addHeader($name, $value);
	public function addIntHeader($name, $value);
	public function containsHeader($name);
	public function encodeRedirectURL($url);
	public function encodeURL($url);
	public function sendError($statusCode, $message);
	public function sendRedirect($location);
	public function setDateHeader($name, $date);
	public function setHeader($header, $value);
	public function setIntHeader($header, $value);
	public function setStatus($statusCode);
}