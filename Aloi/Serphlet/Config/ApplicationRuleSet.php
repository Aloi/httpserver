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
 */
 

/**
 * The set of Digester rules required to parse a web configuration file
 * (web.xml).
 * @version $Id$
 */
class Aloi_Serphlet_Config_ApplicationRuleSet extends Aloi_Phigester_RuleSetBase {
	public function addRuleInstances(Aloi_Phigester_Digester $digester) {
		$digester->addSetProperties('web-app');

		$digester->addCallMethod('web-app/include-paths/include-path', 'setIncludePath', 2);
		$digester->addCallParam('web-app/include-paths/include-path', 0, 'path');
		$digester->addCallParam('web-app/include-paths/include-path', 1, 'context-relative');

		$digester->addCallMethod('web-app/context-param', 'setInitParameter', 2);
		$digester->addCallParam('web-app/context-param/param-name', 0);
		$digester->addCallParam('web-app/context-param/param-value', 1);
	
		$digester->addFactoryCreate('web-app/error-page', new Aloi_Serphlet_Config_ErrorPageFactory());
        $digester->addSetNext('web-app/error-page', 'addErrorPage');
        $digester->addCallMethod('web-app/error-page/error-code', 'setErrorCode', 0);
        $digester->addCallMethod('web-app/error-page/exception-type', 'setExceptionType', 0);
        $digester->addCallMethod('web-app/error-page/location', 'setLocation', 0);
		
		$digester->addFactoryCreate('web-app/filter', new Aloi_Serphlet_Config_FilterDefFactory());
        $digester->addSetNext('web-app/filter', 'addFilterDef');
        $digester->addCallMethod('web-app/filter/description', 'setDescription', 0);
        $digester->addCallMethod('web-app/filter/display-name', 'setDisplayName', 0);
        $digester->addCallMethod('web-app/filter/filter-class', 'setFilterClass', 0);
        $digester->addCallMethod('web-app/filter/filter-name', 'setFilterName', 0);
        $digester->addCallMethod('web-app/filter/large-icon', 'setLargeIcon', 0);
        $digester->addCallMethod('web-app/filter/small-icon', 'setSmallIcon', 0);
        $digester->addCallMethod('web-app/filter/init-param', 'addInitParameter', 2);
        $digester->addCallParam('web-app/filter/init-param/param-name', 0);
        $digester->addCallParam('web-app/filter/init-param/param-value', 1);
        
        $digester->addFactoryCreate('web-app/filter-mapping', new Aloi_Serphlet_Config_FilterMapsFactory());
        $digester->addSetNext('web-app/filter-mapping', 'addFilterMaps');
        $digester->addCallMethod('web-app/filter-mapping/filter-name', 'setFilterName', 0);
        $digester->addCallMethod('web-app/filter-mapping/servlet-name','addServletName', 0);
        $digester->addCallMethod('web-app/filter-mapping/url-pattern','addURLPattern', 0);
        // TODO:
//        $digester->addCallMethod('web-app/filter-mapping/dispatcher', 'setDispatcher', 0);

        $digester->addFactoryCreate('web-app/servlet', new Aloi_Serphlet_Config_ApplicationConfigFactory());
        $digester->addSetNext('web-app/servlet', 'addServletConfig');
        
        $digester->addCallMethod('web-app/servlet/init-param', 'setInitParameter', 2);
        $digester->addCallParam('web-app/servlet/init-param/param-name', 0);
        $digester->addCallParam('web-app/servlet/init-param/param-value', 1);
        
        $digester->addCallMethod('web-app/servlet/servlet-name', 'setServletName', 0);
        $digester->addCallMethod('web-app/servlet/servlet-class','setServletClass', 0);
        
        $digester->addFactoryCreate('web-app/servlet-mapping', new Aloi_Serphlet_Config_ServletMapFactory());
        $digester->addSetNext('web-app/servlet-mapping', 'addServletMapping');
        $digester->addCallMethod('web-app/servlet-mapping/servlet-name', 'setServletName', 0);
        $digester->addCallMethod('web-app/servlet-mapping/url-pattern','addUrlPattern', 0);
        
        $digester->addCallMethod('web-app/welcome-file-list/welcome-file', 'addWelcomeFile', 0);
	}
}


/**
 * An object creation factory which creates filter config instances.
 */
final class Aloi_Serphlet_Config_FilterDefFactory extends Aloi_Phigester_AbstractObjectCreationFactory {
	/**
	 * @param array $attributes
	 * @return object
	 */
	public function createObject(array $attributes) {
		$className = 'Aloi_Serphlet_Config_FilterDef';

		// Instantiate the new object and return it
		$config = null;
		try {
			$config = Aloi_Serphlet_ClassLoader :: newInstance($className, 'Aloi_Serphlet_Config_FilterDef');
		} catch (Exception $e) {
			$this->digester->getLogger()->error('Aloi_Serphlet_Config_FilterDefFactory->createObject(): ' . $e->getMessage());
		}
		return $config;
	}
}

/**
 * An object creation factory which creates filter maps instances.
 */
final class Aloi_Serphlet_Config_FilterMapsFactory extends Aloi_Phigester_AbstractObjectCreationFactory {
	/**
	 * @param array $attributes
	 * @return object
	 */
	public function createObject(array $attributes) {
		$className = 'Aloi_Serphlet_Config_FilterMaps';

		// Instantiate the new object and return it
		$config = null;
		try {
			$config = Aloi_Serphlet_ClassLoader::newInstance($className, 'Aloi_Serphlet_Config_FilterMaps');
		} catch (Exception $e) {
			$this->digester->getLogger()->error('Aloi_Serphlet_Config_FilterMapsFactory->createObject(): ' . $e->getMessage());
		}
		return $config;
	}
}


/**
 * An object creation factory which creates error page instances.
 */
final class Aloi_Serphlet_Config_ErrorPageFactory extends Aloi_Phigester_AbstractObjectCreationFactory {
	/**
	 * @param array $attributes
	 * @return object
	 */
	public function createObject(array $attributes) {
		$className = 'Aloi_Serphlet_Config_ErrorPage';

		// Instantiate the new object and return it
		$config = null;
		try {
			$config = Aloi_Serphlet_ClassLoader::newInstance($className, 'Aloi_Serphlet_Config_ErrorPage');
			
		} catch (Exception $e) {
			$this->digester->getLogger()->error('Aloi_Serphlet_Config_ErrorPageFactory->createObject(): ' . $e->getMessage());
		}
		return $config;
	}
}



/**
 * An object creation factory which creates filter maps instances.
 */
final class Aloi_Serphlet_Config_ApplicationConfigFactory extends Aloi_Phigester_AbstractObjectCreationFactory {
	/**
	 * @param array $attributes
	 * @return object
	 */
	public function createObject(array $attributes) {
		$className = 'Aloi_Serphlet_Config_ApplicationConfig';

		// Instantiate the new object and return it
		$config = null;
		try {
			$config = Aloi_Serphlet_ClassLoader::newInstance($className, 'Aloi_Serphlet_Config_ServletConfig');
			
		} catch (Exception $e) {
			$this->digester->getLogger()->error('Aloi_Serphlet_Config_ApplicationConfig->createObject(): ' . $e->getMessage());
		}
		return $config;
	}
}

/**
 * An object creation factory which creates filter maps instances.
 */
final class Aloi_Serphlet_Config_ServletMapFactory extends Aloi_Phigester_AbstractObjectCreationFactory {
	/**
	 * @param array $attributes
	 * @return object
	 */
	public function createObject(array $attributes) {
		$className = 'Aloi_Serphlet_Config_ServletMap';

		// Instantiate the new object and return it
		$config = null;
		try {
			$config = Aloi_Serphlet_ClassLoader::newInstance($className, 'Aloi_Serphlet_Config_ServletMap');
			
		} catch (Exception $e) {
			$this->digester->getLogger()->error('Aloi_Serphlet_Config_ServletMapFactory->createObject(): ' . $e->getMessage());
		}
		return $config;
	}
}