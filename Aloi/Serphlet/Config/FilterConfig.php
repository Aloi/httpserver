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
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com>
 * @author Craig R. McClanahan
 * @version $Id$
 */
class Aloi_Serphlet_Config_FilterConfig {

	/**
	 * Logging instance.
	 * @var Logger
	 */
	protected static $log = null;
 
    // ----------------------------------------------------------- Constructors


    /**
     * Construct a new ApplicationFilterConfig for the specified filter
     * definition.
     *
     * @param context The context with which we are associated
     * @param filterDef Filter definition for which a FilterConfig is to be
     *  constructed
     *
     * @exception ClassCastException if the specified class does not implement
     *  the <code>Aloi_Serphlet_Filter</code> interface
     * @exception ClassNotFoundException if the filter class cannot be found
     * @exception IllegalAccessException if the filter class cannot be
     *  publicly instantiated
     * @exception InstantiationException if an exception occurs while
     *  instantiating the filter object
     * @exception ServletException if thrown by the filter's init() method
     */
    public function __construct(Aloi_Serphlet_Config_ApplicationContext $context, Aloi_Serphlet_Config_FilterDef $filterDef) {
        $this->context = $context;
        $this->setFilterDef($filterDef);
    }
    
    public function __wakeup() {
		if (is_null(self :: $log)) {
			self :: $log = Aloi_Util_Logger_Manager :: getLogger(__CLASS__);
		}
	}


    // ----------------------------------------------------- Instance Variables


    /**
     * The Context with which we are associated.
     */
    private $context = null;


    /**
     * The application Filter we are configured for.
     */
    private $filter = null;


    /**
     * The <code>FilterDef</code> that defines our associated Filter.
     */
    private $filterDef = null;


    // --------------------------------------------------- FilterConfig Methods


    /**
     * Return the name of the filter we are configuring.
     */
    public function getFilterName() {
        return ($this->filterDef->getFilterName());
    }


    /**
     * Return a <code>String</code> containing the value of the named
     * initialization parameter, or <code>null</code> if the parameter
     * does not exist.
     *
     * @param string name Name of the requested initialization parameter
     */
    public function getInitParameter($name) {
        $map = $this->filterDef->getParameterMap();
        if (empty($map[$name]))
            return (null);
        else
            return $map[$name];
    }


    /**
     * Return an <code>array</code> of the names of the initialization
     * parameters for this Filter.
     */
    public function getInitParameterNames() {
		$map = $this->filterDef->getParameterMap();
		if (empty($map))
            return array();
        else
            return array_keys($map);
    }


    /**
     * Return the ServletContext of our associated web application.
     * @return ServletContext
     */
    public function getServletContext() {
        return ($this->context);

    }


    /**
     * Return a String representation of this object.
     */
    public function __toString() {

        $sb = "ApplicationFilterConfig[";
        $sb .= "name=";
        $sb .= $this->filterDef->getFilterName();
        $sb .= ", filterClass=";
        $sb .= $this->filterDef->getFilterClass();
        $sb .= "]";
        return ($sb);

    }


    // -------------------------------------------------------- Package Methods


    /**
     * Return the application Filter we are configured for.
     *
     * @exception ClassCastException if the specified class does not implement
     *  the <code>Aloi_Serphlet_Filter</code> interface
     * @exception ClassNotFoundException if the filter class cannot be found
     * @exception IllegalAccessException if the filter class cannot be
     *  publicly instantiated
     * @exception InstantiationException if an exception occurs while
     *  instantiating the filter object
     * @exception ServletException if thrown by the filter's init() method
     * @return Filter
     */
    public function getFilter() {
        // Return the existing filter instance, if any
        if ($this->filter != null)
            return ($this->filter);

        // Identify the class loader we will be using
        $filterClass = $this->filterDef->getFilterClass();

        // Instantiate a new instance of this filter and return it
        $this->filter = Aloi_Serphlet_ClassLoader::newInstance($filterClass, 'Aloi_Serphlet_Filter');
		$this->filter->init($this);
        return ($this->filter);

    }


    /**
     * Return the filter definition we are configured for.
     */
    public function getFilterDef() {
        return ($this->filterDef);
    }


    /**
     * Release the Filter instance associated with this FilterConfig,
     * if there is one.
     */
    public function release() {
        if ($this->filter != null){
			$this->filter->destroy();
        }
        $this->filter = null;
     }


    /**
     * Set the filter definition we are configured for.  This has the side
     * effect of instantiating an instance of the corresponding filter class.
     *
     * @param filterDef The new filter definition
     *
     * @exception ClassCastException if the specified class does not implement
     *  the <code>Aloi_Serphlet_Filter</code> interface
     * @exception ClassNotFoundException if the filter class cannot be found
     * @exception IllegalAccessException if the filter class cannot be
     *  publicly instantiated
     * @exception InstantiationException if an exception occurs while
     *  instantiating the filter object
     * @exception ServletException if thrown by the filter's init() method
     */
    public function setFilterDef(Aloi_Serphlet_Config_FilterDef $filterDef) {
        $this->filterDef = $filterDef;
        if ($this->filterDef == null) {
            if ($this->filter != null){
				$this->filter->destroy();
            }
            $this->filter = null;
        } else {
			$this->filter = $this->getFilter();
        }
    }


    // -------------------------------------------------------- Private Methods


}