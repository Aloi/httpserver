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
 * The interface used to represent a response
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
interface Aloi_Serphlet_Response {
	public function flushBuffer();
	public function getBufferSize();
	public function getCharacterEncoding();
	public function getLocale();
	public function getOutputStream();
	public function getWriter();
	public function isCommitted();
	public function reset();
	public function resetBuffer();
	public function setBufferSize($size);
	public function setContentLength($length);
	public function setContentType($type);
	public function setLocale($locale);
}