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
 * Representation of a filter mapping for a web application, as represented
 * in a <code>&lt;filter-mapping&gt;</code> element in the deployment
 * descriptor.  Each filter mapping must contain a filter name and any 
 * number of URL patterns and servlet names.
 * @version $Id$
 */
class Aloi_Serphlet_Config_FilterMaps {

    private $urlPatterns = array();
    private $servletNames = array();
    private $filterName;
    private $fmap;

	function __construct() { 
		$this->fmap = new Aloi_Serphlet_Config_FilterMap();
	}

    // ------------------------------------------------------------ Properties
    
    public function setFilterName($filterName) {
        $this->filterName = $filterName;
    }

    public function getFilterName() {
        return $this->filterName;
    }

    public function addServletName($servletName) {
        $this->servletNames[] = $servletName;
    }

    public function getServletNames() {
        return $this->servletNames;
    }

    public function addURLPattern($urlPattern) {
        $this->urlPatterns[] = $urlPattern;
    }

    public function getURLPatterns() {
        return $this->urlPatterns;
    }
    
    public function setDispatcher($dispatcherString) {
        $this->fmap->setDispatcher($dispatcherString);
    }

    public function getDispatcherMapping() {
        return $this->fmap->getDispatcherMapping();
    }
}
