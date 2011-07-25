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
 * descriptor.  Each filter mapping must contain a filter name plus either
 * a URL pattern or a servlet name.
 * 
 * @author Cameron Manderson
 * @author Craig R. McClanahan
 * @version $Id$
 */
class Aloi_Serphlet_Config_FilterMap {

    // ------------------------------------------------------------- Properties


    /**
     * The name of this filter to be executed when this mapping matches
     * a particular request.
     */
    const ERROR = 1;
    const FORWARD = 2;
    const FORWARD_ERROR = 3;  
    const INCLUDE_ = 4;
    const INCLUDE_ERROR = 5;
    const INCLUDE_ERROR_FORWARD = 6;
    const INCLUDE_FORWARD = 7;
    const REQUEST = 8;
    const REQUEST_ERROR = 9;
    const REQUEST_ERROR_FORWARD = 10;
    const REQUEST_ERROR_FORWARD_INCLUDE = 11;
    const REQUEST_ERROR_INCLUDE = 12;
    const REQUEST_FORWARD = 13;
    const REQUEST_INCLUDE = 14;
    const REQUEST_FORWARD_INCLUDE = 15;
    
    // represents nothing having been set. This will be seen 
    // as equal to a REQUEST
    const NOT_SET = -1;
    
    private $dispatcherMapping = Aloi_Serphlet_Config_FilterMap::NOT_SET;

    private $filterName = null;    
    public function getFilterName() {
        return ($this->filterName);
    }
    public function setFilterName($filterName) {
        $this->filterName = $filterName;
    }


    /**
     * The servlet name this mapping matches.
     */
    private $servletName = null;
    public function getServletName() {
        return ($this->servletName);
    }
    public function setServletName($servletName) {
        $this->servletName = $servletName;
    }

    /**
     * The URL pattern this mapping matches.
     */
    private $urlPattern = null;
    public function getURLPattern() {
        return ($this->urlPattern);
    }
    public function setURLPattern($urlPattern) {
//        $this->urlPattern = RequestUtil::URLDecode(urlPattern);
		$this->urlPattern = $urlPattern;
    }
    
    /**
     *
     * This method will be used to set the current state of the Aloi_Serphlet_Config_FilterMap
     * representing the state of when filters should be applied:
     *
     *        ERROR
     *        FORWARD
     *        FORWARD_ERROR
     *        INCLUDE_               INCLUDE_ERROR        INCLUDE_ERROR_FORWARD
     * REQUEST        REQUEST_ERROR        REQUEST_ERROR_INCLUDE
     * REQUEST_ERROR_FORWARD_INCLUDE        REQUEST_INCLUDE
     * REQUEST_FORWARD,        REQUEST_FORWARD_INCLUDE
     *
     */
    public function setDispatcher($dispatcherString) {
        $dispatcher = strtoupper(dispatcherString);
        if ($dispatcher == "FORWARD") {

            // apply FORWARD to the global $this->dispatcherMapping.
            switch ($this->dispatcherMapping) {
                case NOT_SET  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::FORWARD; break;
                case ERROR : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::FORWARD_ERROR; break;
                case INCLUDE_  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::INCLUDE_FORWARD; break;
                case INCLUDE_ERROR  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::INCLUDE_ERROR_FORWARD; break;
                case REQUEST : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_FORWARD; break;
                case REQUEST_ERROR : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR_FORWARD; break;
                case REQUEST_ERROR_INCLUDE : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR_FORWARD_INCLUDE; break;
                case REQUEST_INCLUDE : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_FORWARD_INCLUDE; break;
            }
        } else if ($dispatcher == "INCLUDE") {
            // apply INCLUDE to the global $this->dispatcherMapping.
            switch ($this->dispatcherMapping) {
                case NOT_SET  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::INCLUDE_; break;
                case ERROR : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::INCLUDE_ERROR; break;
                case FORWARD  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::INCLUDE_FORWARD; break;
                case FORWARD_ERROR  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::INCLUDE_ERROR_FORWARD; break;
                case REQUEST : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_INCLUDE; break;
                case REQUEST_ERROR : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR_INCLUDE; break;
                case REQUEST_ERROR_FORWARD : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR_FORWARD_INCLUDE; break;
                case REQUEST_FORWARD : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_FORWARD_INCLUDE; break;
            }
        } else if ($dispatcher == "REQUEST") {
            // apply REQUEST to the global $this->dispatcherMapping.
            switch ($this->dispatcherMapping) {
                case NOT_SET  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST; break;
                case ERROR : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR; break;
                case FORWARD  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_FORWARD; break;
                case FORWARD_ERROR  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR_FORWARD; break;
                case INCLUDE_  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_INCLUDE; break;
                case INCLUDE_ERROR  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR_INCLUDE; break;
                case INCLUDE_FORWARD : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_FORWARD_INCLUDE; break;
                case INCLUDE_ERROR_FORWARD : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR_FORWARD_INCLUDE; break;
            }
        }  else if ($dispatcher == "ERROR") {
            // apply ERROR to the global $this->dispatcherMapping.
            switch ($this->dispatcherMapping) {
                case NOT_SET  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::ERROR; break;
                case FORWARD  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::FORWARD_ERROR; break;
                case INCLUDE_  :  $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::INCLUDE_ERROR; break;
                case INCLUDE_FORWARD : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::INCLUDE_ERROR_FORWARD; break;
                case REQUEST : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR; break;
                case REQUEST_INCLUDE : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR_INCLUDE; break;
                case REQUEST_FORWARD : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR_FORWARD; break;
                case REQUEST_FORWARD_INCLUDE : $this->dispatcherMapping = Aloi_Serphlet_Config_FilterMap::REQUEST_ERROR_FORWARD_INCLUDE; break;
            }
        }
    }
    
    public function getDispatcherMapping() {
        // per the SRV.6.2.5 absence of any dispatcher elements is
        // equivelant to a REQUEST value
        if ($this->dispatcherMapping == Aloi_Serphlet_Config_FilterMap::NOT_SET) return Aloi_Serphlet_Config_FilterMap::REQUEST;
        else return $this->dispatcherMapping; 
    }
    public function setDispatcherMapping($mapping) {
        $this->dispatcherMapping = $mapping;
    }


    // --------------------------------------------------------- Public Methods


    /**
     * Render a String representation of this object.
     */
    public function __toString() {

        $sb = "FilterMap[";
        $sb .= "filterName=";
        $sb .= $this->filterName;
        if ($this->servletName != null) {
            $sb .= ", servletName=";
            $sb .= $this->servletName;
        }
        if ($this->urlPattern != null) {
            $sb .= ", urlPattern=";
            $sb .= $this->urlPattern;
        }
        $sb .= "]";
        return $sb;
    }
}