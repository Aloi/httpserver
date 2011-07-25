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
 * A concrete implementation of the HTTP Response object used to
 * contain the output HTTP response that PHP will return to the user.
 *
 * @author Cameron Manderson <cameronmanderson@gmail.com>
 * @author Olivier HENRY <oliv.henry@gmail.com> (PHP5 port of Struts)
 * @author John WILDENAUER <jwilde@users.sourceforge.net> (PHP4 port of Struts)
 * @version $Id$
 */
class Aloi_Serphlet_Application_HttpResponse implements Aloi_Serphlet_Http_Response {
	/**
	 * Status code (100) indicating the client can continue.
	 */
	const SC_CONTINUE = 100;

	/**
	 * Status code (101) indicating the server is switching protocols
	 * according to Upgrade header.
	 */
	const SC_SWITCHING_PROTOCOLS = 101;

	/**
	 * Status code (200) indicating the request succeeded normally.
	 */
	const SC_OK = 200;

	/**
	 * Status code (201) indicating the request succeeded and created
	 * a new resource on the server.
	 */
	const SC_CREATED = 201;

	/**
	 * Status code (202) indicating that a request was accepted for
	 * processing, but was not completed.
	 */
	const SC_ACCEPTED = 202;

	/**
	 * Status code (203) indicating that the meta information presented
	 * by the client did not originate from the server.
	 */
	const SC_NON_AUTHORITATIVE_INFORMATION = 203;

	/**
	 * Status code (204) indicating that the request succeeded but that
	 * there was no new information to return.
	 */
	const SC_NO_CONTENT = 204;

	/**
	 * Status code (205) indicating that the agent SHOULD reset the document
	 * view which caused the request to be sent.
	 */
	const SC_RESET_CONTENT = 205;

	/**
	 * Status code (206) indicating that the server has fulfilled
	 * the partial GET request for the resource.
	 */
	const SC_PARTIAL_CONTENT = 206;

	/**
	 * Status code (300) indicating that the requested resource
	 * corresponds to any one of a set of representations, each with
	 * its own specific location.
	 */
	const SC_MULTIPLE_CHOICES = 300;

	/**
	 * Status code (301) indicating that the resource has permanently
	 * moved to a new location, and that future references should use a
	 * new URI with their requests.
	 */
	const SC_MOVED_PERMANENTLY = 301;

	/**
	 * Status code (302) indicating that the resource has temporarily
	 * moved to another location, but that future references should
	 * still use the original URI to access the resource.
	 *
	 * This definition is being retained for backwards compatibility.
	 * SC_FOUND is now the preferred definition.
	 */
	const SC_MOVED_TEMPORARILY = 302;

	/**
	 * Status code (302) indicating that the resource reside
	 * temporarily under a different URI.
	 *
	 * Since the redirection might be altered on occasion, the client should
	 * continue to use the Request-URI for future requests(HTTP/1.1). To
	 * represent the status code (302), it is recommended to use this variable.
	 */
	const SC_FOUND = 302;

	/**
	 * Status code (303) indicating that the response to the request
	 * can be found under a different URI.
	 */
	const SC_SEE_OTHER = 303;

	/**
	 * Status code (304) indicating that a conditional GET operation
	 * found that the resource was available and not modified.
	 */
	const SC_NOT_MODIFIED = 304;

	/**
	 * Status code (305) indicating that the requested resource MUST be
	 * accessed through the proxy given by the Location field.
	 */
	const SC_USE_PROXY = 305;

	/**
	 * Status code (307) indicating that the requested resource
	 * resides temporarily under a different URI.
	 *
	 * The temporary URI <b>SHOULD</b> be given by the Location field in the
	 * response.
	 */
	const SC_TEMPORARY_REDIRECT = 307;

	/**
	 * Status code (400) indicating the request sent by the client was
	 * syntactically incorrect.
	 */
	const SC_BAD_REQUEST = 400;

	/**
	 * Status code (401) indicating that the request requires HTTP
	 * authentication.
	 */
	const SC_UNAUTHORIZED = 401;

	/**
	 * Status code (402) reserved for future use.
	 */
	const SC_PAYMENT_REQUIRED = 402;

	/**
	 * Status code (403) indicating the server understood the request
	 * but refused to fulfill it.
	 */
	const SC_FORBIDDEN = 403;

	/**
	 * Status code (404) indicating that the requested resource is not
	 * available.
	 */
	const SC_NOT_FOUND = 404;

	/**
	 * Status code (405) indicating that the method specified in the
	 * Request-Line is not allowed for the resource identified by the
	 * Request-URI.
	 */
	const SC_METHOD_NOT_ALLOWED = 405;

	/**
	 * Status code (406) indicating that the resource identified by the
	 * request is only capable of generating response entities which have
	 * content characteristics not acceptable according to the accept
	 * headers sent in the request.
	 */
	const SC_NOT_ACCEPTABLE = 406;

	/**
	 * Status code (407) indicating that the client MUST first authenticate
	 * itself with the proxy.
	 */
	const SC_PROXY_AUTHENTICATION_REQUIRED = 407;

	/**
	 * Status code (408) indicating that the client did not produce a
	 * request within the time that the server was prepared to wait.
	 */
	const SC_REQUEST_TIMEOUT = 408;

	/**
	 * Status code (409) indicating that the request could not be
	 * completed due to a conflict with the current state of the
	 * resource.
	 */
	const SC_CONFLICT = 409;

	/**
	 * Status code (410) indicating that the resource is no longer
	 * available at the server and no forwarding address is known.
	 * This condition SHOULD be considered permanent.
	 */
	const SC_GONE = 410;

	/**
	 * Status code (411) indicating that the request cannot be handled
	 * without a defined Content-Length.
	 */
	const SC_LENGTH_REQUIRED = 411;

	/**
	 * Status code (412) indicating that the precondition given in one
	 * or more of the request-header fields evaluated to false when it
	 * was tested on the server.
	 */
	const SC_PRECONDITION_FAILED = 412;

	/**
	 * Status code (413) indicating that the server is refusing to process
	 * the request because the request entity is larger than the server is
	 * willing or able to process.
	 */
	const SC_REQUEST_ENTITY_TOO_LARGE = 413;

	/**
	 * Status code (414) indicating that the server is refusing to service
	 * the request because the Request-URI is longer than the server is willing
	 * to interpret.
	 */
	const SC_REQUEST_URI_TOO_LONG = 414;

	/**
	 * Status code (415) indicating that the server is refusing to service
	 * the request because the entity of the request is in a format not
	 * supported by the requested resource for the requested method.
	 */
	const SC_UNSUPPORTED_MEDIA_TYPE = 415;

	/**
	 * Status code (416) indicating that the server cannot serve the
	 * requested byte range.
	 */
	const SC_REQUESTED_RANGE_NOT_SATISFIABLE = 416;

	/**
	 * Status code (417) indicating that the server could not meet the
	 * expectation given in the Expect request header.
	 */
	const SC_EXPECTATION_FAILED = 417;

	/**
	 * Status code (500) indicating an error inside the HTTP server
	 * which prevented it from fulfilling the request.
	 */
	const SC_INTERNAL_SERVER_ERROR = 500;

	/**
	 * Status code (501) indicating the HTTP server does not support
	 * the functionality needed to fulfill the request.
	 */
	const SC_NOT_IMPLEMENTED = 501;

	/**
	 * Status code (502) indicating that the HTTP server received an
	 * invalid response from a server it consulted when acting as a
	 * proxy or gateway.
	 */
	const SC_BAD_GATEWAY = 502;

	/**
	 * Status code (503) indicating that the HTTP server is
	 * temporarily overloaded, and unable to handle the request.
	 */
	const SC_SERVICE_UNAVAILABLE = 503;

	/**
	 * Status code (504) indicating that the server did not receive
	 * a timely response from the upstream server while acting as
	 * a gateway or proxy.
	 */
	const SC_GATEWAY_TIMEOUT = 504;

	/**
	 * Status code (505) indicating that the server does not support
	 * or refuses to support the HTTP protocol version that was used
	 * in the request message.
	 */
	const SC_HTTP_VERSION_NOT_SUPPORTED = 505;
	
	
	/**
	 * Boolean indicating if the response has been committed.
	 *
	 * @var boolean
	 */
	protected $committed = false;

	/**
	 * The status code for this response.
	 *
	 * @var integer
	 */
	protected $sc = self :: SC_OK;

	/**
	 * The set of Cookies associated with this Response.
	 *
	 * @var array
	 */
	protected $cookies = array ();

	/**
	 * The content type associated with this Response.
	 *
	 * @var string
	 */
	protected $contentType = null;

	/**
	* The character encoding associated with this Response.
	 *
	 * @var string
	 */
	protected $characterEncoding = null;

	/**
	 * Has the charset been explicitly set.
	 *
	 * @var boolean
	 */
	protected $charsetSet = false;

	/**
	 * The HTTP headers explicitly added.
	 *
	 * This array is keyed by the header name, and the elements are
	 * array containing the associated values that have been set.
	 *
	 * @var array
	 */
	protected $headers = array ();

	/**
	 * If auto-flush is enabled, any writes are automatically flushed to the
	 * client whenever writeln method is called.
	 *
	 * @var boolean
	 */
	protected $autoflush = false;

	/**
	 * The buffer through which all of our output is passed.
	 *
	 * @var string
	 */
	protected $buffer = '';
	
	protected $error = false;
	protected $message = '';

	/**
	 * Sets the status code for this response.
	 *
	 * <p>This method is used to set the return status code when there is no error
	 * (for example, for the status codes SC_OK or SC_MOVED_TEMPORARILY).</p>
	 * <p>If there is an error, and the caller wishes to invoke an error-page
	 * defined in the the web application, the <samp>sendError</samp> method
	 * should be used instead.</p>
	 * <p>Clears the buffer, preserving cookies and other headers.</p>
	 *
	 * @param integer $sc
	 * @throws IllegalStateException - If the response has been committed
	 */
	public function setStatus($sc) {
		try {
			$this->resetBuffer();
		} catch (IllegalStateException $e) {
			throw $e;
		}

		$this->sc = (integer) $sc;
	}
	
	public function getStatus() {
		return $this->sc;
	}

	/**
	 * Adds the specified cookie to the response.
	 *
	 * This method can be called multiple times to set more than one cookie.
	 *
	 * @param Cookie $cookie The cookie to return to the client.
	 */
	public function addCookie(Aloi_Serphlet_Http_Cookie $cookie) {
		$this->cookies[] = $cookie;
	}

	/**
	 * Returns the name of the character encoding (MIME charset) used for the
	 * body sent in this response.
	 *
	 * The character encoding may have been specified explicitly using the
	 * <samp>setCharacterEncoding</samp> or <samp>setContentType</samp>
	 * methods, or implicitly using the <samp>setLocale</samp> method. Explicit
	 * specifications take precedence over implicit specifications. If no
	 * character encoding has been specified, ISO-8859-1 is returned.
	 *
	 * @return string
	 */
	public function getCharacterEncoding() {
		return $this->characterEncoding;
	}

	/**
	 * Sets the character encoding (MIME charset) of the response being sent to
	 * the client, for example, to UTF-8.
	 *
	 * If the character encoding has already been set by
	 * <samp>setContentType</samp> or <samp>setLocale</samp>, this method
	 * overrides it. Calling <samp>setContentType</samp> with the string of
	 * "text/html" and calling this method with the string of "UTF-8" is
	 * equivalent with calling <samp>setContentType</samp> with the string of
	 * "text/html; charset=UTF-8".
	 *
	 * @param string $charset A string specifying only the character set defined
	 * by IANA Character Sets (http://www.iana.org/assignments/character-sets)
	 */
	public function setCharacterEncoding($charset) {
		if (!$this->committed) {
			$this->characterEncoding = (string) $charset;
			$this->charsetSet = !empty($charset);
		}
	}

	/**
	 * Returns the content type used for the MIME body sent in this response.
	 *
	 * The content type proper must have been specified using
	 * <samp>setContentType</samp> before the response is committed. If no
	 * content type has been specified, this method returns null. If a content
	 * type has been specified and a character encoding has been explicitly or
	 * implicitly specified as described in <samp>getCharacterEncoding</samp>
	 * the charset parameter is uncluded in the string returned. If no character
	 * encoding has been specified, the charset parameter is omitted.
	 *
	 * @return string
	 */
	public function getContentType() {
		$ret = $this->contentType;

		if (!is_null($ret) && !is_null($this->characterEncoding) && $this->charsetSet) {
			$ret .= '; charset=' . $this->characterEncoding;
		}
		return $ret;
	}

	/**
	 * Sets the content type of the response being sent to the client, if the
	 * response has not been committed yet.
	 *
	 * <p>The given content type may include a character encoding specification,
	 * for example "text/html;charset=UTF-8".</p>
	 * <p>This method may be called repeatedly to change content type and
	 * character encoding. This method has no effect if called after the response
	 * has been committed.</p>
	 *
	 * @param string $type
	 */
	public function setContentType($type) {
		// Remove the charset param (if any) form the Content-Type, and use it to
		// set the response enconding.
		// The most recent response enconding setting will be appended to the
		// response's Content-Type (as its charset param) by getContentType().
		$hasCharset = false;
		$len = strlen($type);
		$index = strpos($type, ';');
		while ($index !== false) {
			$semicolonIndex = $index;
			$index++;
			while ($index < $len && substr($type, $index, 1) == ' ') {
				$index++;
			}
			if (substr($type, $index, 8) == 'charset=') {
				$hasCharset = true;
				break;
			}
			$index = strpos($type, ';', $semicolonIndex);
		}

		if (!$hasCharset) {
			$this->contentType = $type;
			return;
		}

		$this->contentType = substr($type, 0, $semicolonIndex);
		$tail = substr($type, $index +8);
		$nextParam = strpos($tail, ';');
		$charsetValue = null;
		if ($nextParam === false) {
			$charsetValue = $tail;
		} else {
			$this->contentType .= substr($tail, $nextParam);
			$charsetValue = substr($tail, 0, $nextParam);
		}

		// The charset value may be quoted, but must not contain any quotes.
		if (!is_null($charsetValue && strlen($charsetValue) > 0)) {
			$charsetSet = true;
			$charsetValue = str_replace('"', ' ', $charsetValue);
			$this->characterEncoding = trim($charsetValue);
		}
	}

	/**
	 * Returns a boolean indicating whether the named response header has already
	 * been set.
	 *
	 * @param string $name The header name
	 * @return boolean True if the named response header has already been set;
	 * false otherwise
	 */
	public function containsHeader($name) {
		return array_key_exists($name, $this->headers);
	}

	/**
	 * Sets a response header with the given name and value.
	 *
	 * If the header had already been set, the new value overwrites the previous
	 * one. The <samp>containsHeader</samp> method can be used to test for the
	 * presence of a header before setting its value.
	 *
	 * @param string $name The name of the header
	 * @param string $value The header value
	 */
	public function setHeader($name, $value) {
		$name = (string) $name;

		$this->headers[$name] = array (
			(string) $value
		);
	}

	/**
	 * Add a response header with the given name and value.
	 *
	 * This method allows response headers to have multiple values.
	 *
	 * @param string $name The name of the header
	 * @param string $value The additional header value
	 */
	public function addHeader($name, $value) {
		$name = (string) $name;

		$this->headers[$name][] = (string) $value;
	}

	/**
	 * Sets a response header with the given name and date-value.
	 *
	 * The date is specified in terms of milliseconds since the epoch. If the
	 * header had already been set, the new value overwrites the previous one.
	 * The <samp>containsHeader</samp> method can be used to test for the
	 * presence of a header before setting its value.
	 *
	 * @param string $name The name of the header to set
	 * @param integer $date The assigned date value
	 */
	public function setDateHeader($name, $date) {
		$values = array (
			date(DATE_RFC822,
			$date
		));
		$name = (string) $name;

		$this->headers[$name] = array (
			$values
		);
	}

	/**
	 * Adds a response header with the given name and date-value.
	 *
	 * The date is specified in terms of milliseconds since the epoch. This method
	 * allows response headers to have multiple values.
	 *
	 * @param string $name The name of the header to set
	 * @param integer $date The assigned date value
	 */
	public function addDateHeader($name, $date) {
		$value = date(DATE_RFC822, $date);
		$name = (string) $name;

		$this->headers[$name][] = $value;
	}

	/**
	 * Returns a boolean indicating if the response has been committed.
	 *
	 * A committed response has already had its status code and headers written.
	 *
	 * @return boolean
	 */
	public function isCommitted() {
		return $this->committed;
	}

	/**
	 * Commit the response.
	 *
	 * Send headers from the response (status code, cookies and other headers)
	 * if the response has not been already committed.
	 *
	 * @throws IllegalStateException - If headers has been already sent
	 */
	protected function commit() {
		if (!$this->committed) {
			if (headers_sent()) {
				throw new Aloi_Serphlet_Exception_IllegalState();
			}

			// Send the status code
			$header = 'HTTP/1.1 ' . (integer) $this->sc;
			header($header);

			// Send cookies
			foreach ($this->cookies as $cookie) {
				if ($cookie->getMaxAge() == 0) {
					// Delete the cookie
					setcookie($cookie->getName(), '');
				} else {
					if ($cookie->getMaxAge() < 0) {
						$expire = 0;
					} else {
						$expire = time() + $cookie->getMaxAge();
					}
					setcookie($cookie->getName(), $cookie->getValue(), $expire, $cookie->getPath(), $cookie->getDomain(), $cookie->getSecure(), $cookie->getHttpOnly());
				}
			}
			
			// Send the content type
			if(empty($this->contentType)) $this->contentType = 'text/html'; // Default to text/html
			$this->addHeader('contentType', 'Content-Type: ' . $this->getContentType());
			
			// Send headers
			foreach ($this->headers as $headerValues) {
				foreach ($headerValues as $header) {
					header($header);
				}
			}

			$this->committed = true;
		}
	}

	/**
	 * Returns the actual auto-flush property for the response.
	 *
	 * @return unknown
	 */
	public function getAutoflush() {
		return $this->autoflush;
	}

	/**
	 * Sets the auto-flush property for the response.
	 *
	 * @param boolean $autoflush
	 * @throws IllegalStateException - If the response has been committed
	 */
	public function setAutoflush($autoflush) {
		if ($this->committed) {
			throw new IllegalStateException();
		}

		$this->autoflush = (boolean) $autoflush;
	}

	/**
	 * Returns the actual buffer size used for the response.
	 *
	 * @return integer
	 */
	public function getBufferSize() {
		return strlen($this->buffer);
	}

	/**
	 * Clears the content of the underlying buffer in the response without
	 * clearing headers or status code.
	 *
	 * @throws IllegalStateException - If the response has been committed
	 */
	public function resetBuffer() {
		if ($this->committed) {
			throw new IllegalStateException();
		}

		$this->buffer = '';
	}

	/**
	 * This method prints a string to the buffer response.
	 *
	 * @param string $str The string to print
	 */
	public function write($str) {
		$this->buffer .= (string) $str;
	}

	/**
	 * This method prints a string to the buffer response.
	 *
	 * This method prints a line termination sequence after printing the value.
	 *
	 * @param string $str The string to print
	 * @throws IllegalStateException - If the headers has been already sent
	 */
	public function writeln($str) {
		$this->buffer .= (string) $str . PHP_EOL;

		if ($this->autoflush) {
			try {
				$this->flushBuffer();
			} catch (IllegalStateException $e) {
				throw $e;
			}
		}
	}

	/**
	 * Forces any content in the buffer to be written to the client.
	 *
	 * A call to this method automatically commits the response, meaning the
	 * status code and headers will be written.
	 *
	 * @throws IllegalStateException - If the headers has been already sent
	 */
	public function flushBuffer() {
		try {
			$this->commit();
		} catch (IllegalStateException $e) {
			throw $e;
		}
		
		echo $this->buffer;
		flush();
		$this->buffer = '';
	}

	/**
	 * Sends an error response to the client using the specified status code and
	 * clearing the buffer.
	 *
	 * <p>The server defaults to creating the response to look like an
	 * HTML-formatted server error page containing the specified message,
	 * setting the content type to "text/html", leaving cookies and other
	 * headers unmodified.</p>
	 * <p>If the response has already been committed, this method throws
	 * a IllegalStateException. After using this method, the response
	 * should be considered to be committed an should not be written to.</p>
	 *
	 * @param integer $sc The error status code
	 * @param string $msg The descriptive message
	 * @throws IllegalStateException - If the response was committed or
	 * the headers has been already sent
	 */
	public function sendError($sc, $msg) {
		$this->error = true;
		
		try {
			$this->setStatus($sc);
			$this->message = $msg;
		} catch (IllegalStateException $e) {
			throw $e;
		}
	}
	
	public function isError() {
		return $this->error == true;
	}

	/**
	 * Sends a temporary redirect response to the client using the specified
	 * redirect location URL.
	 *
	 * <p>If the response has already been committed, this method throws
	 * a IllegalStateException. After using this method, the response
	 * should be considered to be committed an should not be written to.</p>
	 *
	 * @param string $location The redirect location URL
	 * @throws IllegalStateException - If the response was committed or
	 * the headers has been already sent
	 * @todo Manage relatives URL.
	 * @todo Send cookies.
	 */
	public function sendRedirect($location) {
		if ($this->committed || headers_sent()) {
			throw new IllegalStateException();
		}
		$this->committed = true;

		$header = 'Location: ' . (string) $location;
		header($header);
	}
	
	/**
	 * Returns immediately with the buffer contents
	 * @return string Current Buffer Contents
	 */
	public function getBuffer() {
		return $this->buffer;
	}

	/**
	 * Encodes the specified URL for use in the sendRedirect method or, if
	 * encoding is not needed, returns the URL unchanged.
	 *
	 * The implementation of this method includes the logic to determine whether
	 * the session ID needs to be encoded in the URL.
	 *
	 * @param string $url The url to be encoded
	 * @return string The encoded URL if encoding is needed; the unchanged URL
	 * otherwise
	 * @todo Implement the method.
	 */
	public function encodeRedirectURL($url) {
		return $url;
	}
	
	public function encodeURL($url) {
		
	}
	
	public function addIntHeader($name, $value) {
		// TODO: Implement
	}
	public function setIntHeader($header, $value) {
		// TODO: Implement
	}
	public function getLocale() {
		// TODO: Implement
	}
	public function getOutputStream() {
		// TODO: Implement
	}
	public function getWriter() {
		// TODO: Implement
	}
	public function reset() {
		// TODO: Implement
	}
	public function setBufferSize($size) {
		// TODO: Implement
	}
	public function setContentLength($length) {
		// TODO: Implement
		$this->addHeader('Content-length: ', $length);
	}
	public function setLocale($locale) {
		// TODO: Implement
	}
	public function getMessage() {
		return $this->message;
	}
}