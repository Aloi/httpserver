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
 * A class for handling the commiting of a response.
 *
 * If it encounters a response status code of above 400, it will
 * attempt to return a configured error page or serve a default
 * error page.
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com> (Aloi Contributor)
 * @version $Id$
 */
class Aloi_Serphlet_Application_RequestDispatcherForward {
	public static function commit(Aloi_Serphlet_Http_Request $request, Aloi_Serphlet_Http_Response $response, Aloi_Serphlet_Config_ServletContext $context) {
		
		// Determine the type
		$statusCode = $response->getStatus();
		$exception = $request->getAttribute(Aloi_Serphlet_Globals::EXCEPTION_ATTR);
		
		// Consider only getting involved if the response is marked as an error
		if(!$response->isError()) { return; }
		
		// Handle the status
		if (!$response->isCommitted()) {
			$response->resetBuffer();
			if($statusCode >= 400) {
				$request->setAttribute(Aloi_Serphlet_Globals::EXCEPTION_PAGE_ATTR, $request->getRequestURI());
				$request->setAttribute(Aloi_Serphlet_Globals::STATUS_CODE_ATTR, $statusCode);
				$errorMessage = $request->getAttribute(Aloi_Serphlet_Globals::ERROR_MESSAGE_ATTR);
				if(!trim($errorMessage)) {
					$request->setAttribute(Aloi_Serphlet_Globals::ERROR_MESSAGE_ATTR, $response->getMessage());
				}
				$errorPage = $context->findErrorPage($statusCode);
				if(!empty($errorPage)) {
					self::serveErrorPage($request, $response, $context, $errorPage);
				} else {
					self::serveDefaultErrorPage($request, $response, $context);
				}
			} else {
				// Errors below this status don't have response contents
			}
		}
	}
	
	private static function serveErrorPage($request, $response, $context, $errorPage) {
		// Locate the path
		$appCWD = $request->getAttribute(Aloi_Serphlet_Globals::BASE_PATH);
		if(empty($appCWD)) $appCWD = getcwd();
		$path = $appCWD . $errorPage->getLocation();
		
		// Read in teh error page
		$fileExists = @fopen($path, 'r', true);
		if (!$fileExists) {
			$errorMessage = $request->getAttribute(Aloi_Serphlet_Globals::ERROR_MESSAGE_ATTR);
			$statusCode = $request->getAttribute(Aloi_Serphlet_Globals::STATUS_CODE_ATTR);
			$errorMessage .= '. Additionally, the Request Dispatcher could not load the configured error page resource location (' . $path . ') to display this status error (' . $statusCode . ').';
			$request->setAttribute(Aloi_Serphlet_Globals::ERROR_MESSAGE_ATTR, $errorMessage);
			self::serveDefaultErrorPage($request, $response, $context, $statusCode);
			return;
		} else fclose($fileExists);
		
		if ($response->getAutoflush()) {
			require ($path);
		} else {
			ob_start();
			require $path;
			$response->write(ob_get_contents());
			ob_end_clean();
		}
	}
	
	private static function serveDefaultErrorPage($request, $response, $context, $statusCode) {
		$response->resetBuffer();
		$message = $request->getAttribute(Aloi_Serphlet_Globals::ERROR_MESSAGE_ATTR);
		
		// TODO: Separate this code out into its own view. Not to be contained within this file!
		$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"';
		$html .= '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . PHP_EOL;
		$html .= '<html xmlns="http://www.w3.org/1999/xhtml">' . PHP_EOL;
		$html .= '<head>' . PHP_EOL;
		$html .= '<meta content="text/html; charset=ISO-8859-1"';
		$html .= ' http-equiv="content-type"/>' . PHP_EOL;
		$html .= '<title>' . $statusCode . ' Error</title>' . PHP_EOL;
		$html .= '</head>' . PHP_EOL;
		$html .= '<body>' . PHP_EOL;
		$html .= '<h1>Error</h1>' . PHP_EOL;
		$html .= '<p>' . $message . '</p>' . PHP_EOL;
		$html .= '</body>' . PHP_EOL;
		$html .= '</html>' . PHP_EOL;
		$response->write($html);
	}
}