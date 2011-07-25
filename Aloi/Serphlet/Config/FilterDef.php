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
 * Representation of a filter definition for a web application, as represented
 * in a <code>&lt;filter&gt;</code> element in the deployment descriptor.
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com>
 * @author Craig R. McClanahan
 * @version $Id$
 */
class Aloi_Serphlet_Config_FilterDef {


    // ------------------------------------------------------------- Properties


    /**
     * The description of this filter.
     */
    private $description = null;
    public function getDescription() {
        return ($this->description);
    }
    public function setDescription($description) {
        $this->description = $description;
    }


    /**
     * The display name of this filter.
     */
    private $displayName = null;
    public function getDisplayName() {
        return ($this->displayName);
    }
    public function setDisplayName($displayName) {
        $this->displayName = $displayName;
    }


    /**
     * The fully qualified name of the Java class that implements this filter.
     */
    private $filterClass = null;
    public function getFilterClass() {
        return ($this->filterClass);
    }
    public function setFilterClass($filterClass) {
        $this->filterClass = $filterClass;
    }


    /**
     * The name of this filter, which must be unique among the filters
     * defined for a particular web application.
     */
    private $filterName = null;
    public function getFilterName() {
        return ($this->filterName);
    }
    public function setFilterName($filterName) {
        $this->filterName = $filterName;
    }


    /**
     * The large icon associated with this filter.
     */
    private $largeIcon = null;
    public function getLargeIcon() {
        return ($this->largeIcon);
    }
    public function setLargeIcon($largeIcon) {
        $this->largeIcon = $largeIcon;
    }


    /**
     * The set of initialization parameters for this filter, keyed by
     * parameter name.
     */
    private $parameters = array();
    public function getParameterMap() {
        return ($this->parameters);
    }


    /**
     * The small icon associated with this filter.
     */
    private $smallIcon = null;

    public function getSmallIcon() {
        return ($this->smallIcon);
    }

    public function setSmallIcon($smallIcon) {
        $this->smallIcon = $smallIcon;
    }


    // --------------------------------------------------------- Public Methods


    /**
     * Add an initialization parameter to the set of parameters associated
     * with this filter.
     *
     * @param string name The initialization parameter name
     * @param string value The initialization parameter value
     */
    public function addInitParameter($name, $value) {
        $this->parameters[$name] = $value;
    }


    /**
     * Render a String representation of this object.
     */
    public function __toString() {

        $sb = "FilterDef[";
        $sb .= "filterName=";
        $sb .= $this->filterName;
        $sb .= ", filterClass=";
        $sb .= $this->filterClass;
        $sb .= "]";
        return ($sb);

    }


}

