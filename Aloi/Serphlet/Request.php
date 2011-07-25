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
 * An interface used for the request of the servlet
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
interface Aloi_Serphlet_Request {
    public function getAttribute($name);
    public function getAttributeNames();
    public function getCharacterEncoding();
    public function setCharacterEncoding($env);
    public function getContentLength();
    public function getContentType();
    public function getInputStream();
    public function getParameter($name);
    public function getParameterNames();
    public function getParameterValues($name);
    public function getParameterMap();
    public function getProtocol();
    public function getScheme();
    public function getServerName();
    public function getServerPort();
    public function getReader();
    public function getRemoteAddr();
    public function getRemoteHost();
    public function setAttribute($name, $value);
    public function removeAttribute($name);
    public function getLocale();
    public function getLocales();
    public function isSecure();
    public function getRequestDispatcher($path);
    public function getRemotePort();
    public function getLocalName();
    public function getLocalAddr();
    public function getLocalPort();
}