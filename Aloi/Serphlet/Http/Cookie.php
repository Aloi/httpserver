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
 * A class for managing HTTP Cookies
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
class Aloi_Serphlet_Http_Cookie {
	// Cookie data
	private $name;
	private $value;

	// Cookie properties
	private $comment;
	private $domain;
	private $maxAge = -1;
	private $path;
	private $secure;
	private $version = 0;

	public function __construct($name, $value) {
		if($this->isToken($name)
		|| strtolower($name) == 'Comment'
		|| strtolower($name) == 'Discard'
		|| strtolower($name) == 'Domain'
		|| strtolower($name) == 'Expires'
		|| strtolower($name) == 'Max-Age'
		|| strtolower($name) == 'Path'
		|| strtolower($name) == 'Secure'
		|| strtolower($name) == 'Version'
		|| substr($name, 0, 1) == '$') {
			throw new Aloi_Serphlet_Exception_IllegalArgument('Cookie name ' . $name . ' is illegal');
		}
		$this->name = $name;
		$this->value = $value;
	}

	public function setComment($purpose) {
		$this->comment = $purpose;
	}
	public function getComment() {
		return $this->comment;
	}

	public function setDomain($pattern) {
		$this->domain = strtolower($pattern);	// IE allegedly needs this
	}
	public function getDomain() {
		return $this->domain;
	}

	public function setMaxAge($expiry) {
		$this->maxAge = $expiry;
	}
	public function getMaxAge() {
		return $this->maxAge;
	}

	public function setPath($uri) {
		$this->path = $uri;
	}
	public function getPath() {
		return $this->path;
	}

	public function setSecure($flag) {
		$this->secure = $flag;
	}
	public function getSecure() {
		return $this->secure;
	}

	public function getName() {
		return $this->name;
	}

	public function setValue($newValue) {
		$this->value = $newValue;
	}

	public function getVersion() {
		return $this->version;
	}
	public function setVersion($v) {
		$this->version = $v;
	}

	/**
	 * @todo confirm the token name is not reserved
	 */
	public function isToken($name) {
		return false;
	}
	
	public function getValue() {
		return $this->value;
	}
	
	public function __toString() {
		return $this->value;
	}

}