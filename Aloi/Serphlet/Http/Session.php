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
 * A class for handling server sessions
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
class Aloi_Serphlet_Http_Session {
	protected $attributes = array();
	
	public function __construct() {
		if(session_id() == '') {
			session_start();
		}
	}
	
	public function getAttribute($name) {
		$name = (string) $name;
		if(array_key_exists($name, $this->attributes)) {
			return $this->attributes[$name];
		} else if(!empty($_SESSION) && array_key_exists($name, $_SESSION)) {
			$this->attributes[$name] = unserialize($_SESSION[$name]);
			return $this->attributes[$name];
		}
	}
	
	public function getId() {
		return session_id();
	}
	
	public function setAttribute($name, $value) {
		$name = (string) $name;
		$this->attributes[$name] = $value;
		$log = Aloi_Util_Logger_Manager::getLogger(__CLASS__);
		$log->info('Setting the attribute ' . $name);
	}
	
	public function removeAttribute($name) {
		unset($this->attributes[$name]);
		unset($_SESSION[$name]);
	}
	
	public function getAttributeNames() {
		return array_unique(array_merge(array_keys($_SESSION), array_keys($this->attributes)));
	}
	
	public function commit() {
		foreach($this->attributes as $attributeName => $attributeValue) {
			$_SESSION[$attributeName] = serialize($attributeValue);
		}
	}
}