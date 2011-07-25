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
 * A class containing global consts that are used in identifying
 * components in the application.
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 */
class Aloi_Serphlet_Globals {
	const BASE_PATH = 'Aloi_Serphlet_BASE_PATH';
	
	/**
     * The request attribute under which we forward a PHP exception
     * (as an object of type Throwable) to an error page.
     */
	const EXCEPTION_ATTR = 'Aloi_Serphlet_EXCEPTION_ATTR';
	
	/**
     * The request attribute under which we forward the request URI
     * (as an object of type String) of the page on which an error occurred.
     */
	const EXCEPTION_PAGE_ATTR = 'Aloi_Serphlet_EXCEPTION_PAGE_ATTR';
	
	  /**
     * The request attribute under which we forward a PHP exception type
     * (as an object of type Class) to an error page.
     */
    const EXCEPTION_TYPE_ATTR = "Aloi_Serphlet_EXCEPTION_TYPE_ATTR";

    /**
     * The request attribute under which we forward an HTTP status message
     * (as an object of type String) to an error page.
     */
    const ERROR_MESSAGE_ATTR = "Aloi_Serphlet_ERROR_MESSAGE_ATTR";
    
    /**
     * The request attribute under which we forward an HTTP status code
     * (as an object of type Integer) to an error page.
     */
    const STATUS_CODE_ATTR = "Aloi_Serphlet_STATUS_CODE_ATTR";
}