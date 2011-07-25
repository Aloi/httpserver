<?php
/* Copyright 2010 aloi-project
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
 *
 * This file incorporates work covered by the following copyright and
 * permissions notice:
 *
 * Copyright (C) 2008 PHruts
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
 * The ClassLoader is a way of customizing the way PHP gets its classes
 * and loads them into memory.
 *
 * @author Olivier HENRY <oliv.henry@gmail.com> (PHP5 port of Struts)
 * @author John WILDENAUER <jwilde@users.sourceforge.net> (PHP4 port of Struts)
 * @version $Id$
 */
class Aloi_Serphlet_ClassLoader {
	/**
	 * The PHP extension file used to store PHP class.
	 *
	 * @var string
	 */
	protected static $phpExtensionFile = '.php';

	/**
	 * @param string $phpExtensionFile The PHP extension file used to store PHP
	 * class
	 */
	public static function setPhpExtensionFile($phpExtensionFile) {
		self :: $phpExtensionFile = (string) $phpExtensionFile;
	}

	/**
	 * Check if a fully qualified class name is valid.
	 *
	 * @param string $name Fully qualified name of a class (with packages)
	 * @return boolean Return true if the class name is valid
	 */
	public static function isValidClassName($name) {
		$classPattern = '`^((([A-Z]|[a-z]|[0-9]|\_|\-)+\:{2})*)';
		$classPattern .= '(([A-Z]|[a-z]){1}([A-Z]|[a-z]|[0-9]|\_)*)$`';
		return (boolean) preg_match($classPattern, $name);
	}

	/**
	 * Return only the class name of a fully qualified name.
	 *
	 * @param string $name Fully qualified name of a class (with packages)
	 * @return string
	 */
	public static function getClassName($name) {
		// MODIFY THE STANDARD LOADING TO TREAT :: AS CLASS
		$name = str_replace('::', '_', $name);
		$lastDot = strrpos($name, '::');
		if ($lastDot === false) {
			$className = $name;
		} else {
			$className = substr($name, - (strlen($name) - $lastDot -2));
		}
		return $className;
	}

	/**
	 * Discover whether an instance of the class from would be an instance of the
	 * class as well.
	 *
	 * @param string $class
	 * @param string $classFrom
	 * @return boolean Whether an instance of classFrom would be an instance of
	 * class as well
	 */
	public static function classIsAssignableFrom($class, $classFrom) {
		$className = self :: getClassName($class);
		$classFromName = self :: getClassName($class);
		if ($className == $classFromName) {
			return true;
		} else {
			// Get reflection information of the class from
			$reflectionClass = new ReflectionClass($classFromName);
			if ($reflectionClass->isSubclassOf($className)) {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * Load a class.
	 *
	 * @param string $name The fully qualified name of the class (with packages)
	 * @return string Return the only class name
	 * @throws IllegalArgumentException - If the class name is not valid
	 * @throws ClassNotFoundException - If the class cannot be found
	 */
	public static function loadClass($name) {
		//Check if the fully qualified class name is valid
		if (!self :: isValidClassName($name)) {
			throw new Aloi_Serphlet_Exception_IllegalArgument('Illegal class name ' . $name . '.');
		}

		// Get only the class name
		$className = self :: getClassName($name);

		// Have we already loaded this class?
		if (class_exists($className, true)) {
			return $className;
		} else {
			// Try to load the class
			$pathClassFile = str_replace(array('::', '_'), '/', $name) . self :: $phpExtensionFile;
			$fileExists = @fopen($pathClassFile, 'r', true);
			if ($fileExists && fclose($fileExists) && require_once ($pathClassFile)) { // Removed the '@include_once' as we want to see the FATAL
				if(class_exists($className, false)) {
					return $className;
				} else {
					$msg = '"' . $name . '" class does not exist.';
					throw new Aloi_Serphlet_Exception_ClassNotFound($msg);
				}
				die();
			} else {
				$msg = 'PHP class file "' . $pathClassFile . '" does not exist.';
				throw new Aloi_Serphlet_Exception_ClassNotFound($msg);
			}
		}
	}

	/**
	 * Get a new instance of a class by calling the no-required-argument
	 * constructor.
	 *
	 * @param string $name The fully qualified name of the class (with packages)
	 * @param string $parent If is set, the class must be a subclass of the class
	 * which name is equal to "parent"
	 * @return $object New instance of the class
	 * @throws IllegalArgumentException - If the class name is not valid
	 * @throws ClassNotFoundException - If the class cannot be found
	 * @throws InstantiationException - If there is not a
	 * no-required-argument constructor for this class.
	 */
	public static function newInstance($name, $parent = null) {
		try {
			// Load the class and get only the class name
			$className = self :: loadClass($name);
		} catch (Exception $e) {
			throw $e;
		}

		// Get reflection information of the class
		$class = new ReflectionClass($className);
		if ($class->isAbstract()) {
			throw new Aloi_Serphlet_Exception_Instantiation('Cannot instantiate abstract class.');
		}
		if (!is_null($parent) && ($className != $parent) && !$class->isSubclassOf($parent)) {
			throw new Aloi_Serphlet_Exception_Instantiation('"' . $name . '" is not a subclass of "' . $parent . '".');
		}

		// Get reflection information of the constructor
		$constructor = $class->getConstructor();
		if (!is_null($constructor)) {
			// Check accessibility of the constructor
			if (!$constructor->isPublic()) {
				throw new Aloi_Serphlet_Exception_Instantiation('You are not allowed to access' . ' the no-required-argument constructor for this class.');
			}
			// Check the no-required-argument constructor
			if ($constructor->getNumberOfRequiredParameters() > 0) {
				throw new Aloi_Serphlet_Exception_Instantiation('There is not a no-required-argument constructor for this class.');
			}
		}

		// Create the new instance of the class
		$instance = new $className ();
		return $instance;
	}
}
