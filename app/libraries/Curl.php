<?php
/*
* cURL Class
* By Timothy 'TiM' Oliver
* http://www.timoliver.com.au
* 
* An abstraction class to streamline and simplify use of the PHP cuRL implementation.
*
* ============================================================================
* 
* Copyright (C) 2011 by Tim Oliver
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
* 
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
* 
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
* 
*/

class Curl
{
	//The handle for the cURL session
	private $ch = NULL;
	
	/*
	* Class Constructor
	*/
	public function __construct()
	{
		//init the cURL object
		$this->ch = curl_init();
		
		//set up default parameters
		curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER, 1 ); 	//data is returned instead of automatically printed
		curl_setopt( $this->ch, CURLOPT_HEADER, 		0 );	//header data is not merged with the final output
		curl_setopt( $this->ch, CURLOPT_FRESH_CONNECT, 	1 );	//force a new connection each time instead of deferring the cached ones
		curl_setopt( $this->ch, CURLOPT_FORBID_REUSE, 	1 );	//force a new connection each time
		curl_setopt( $this->ch, CURLOPT_TIMEOUT, 		4 );	//Timeout time for the connection
		
		//set default referer to the address of the calling URL
		curl_setopt( $this->ch, CURLOPT_REFERER, 		$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"] );
		
		//set user agent to PHP OS string
		curl_setopt( $this->ch, CURLOPT_USERAGENT,		php_uname() );
	}
	
	/*
	* Class destructor
	*/
	public function __destruct()
	{
		//Deallocate handle
		curl_close( $this->ch );
		$this->ch = NULL;
	}	
	
	/**
	* Options
	* 
	* Set up additional options with cURL.
	*
	* @param Array $options An array that can contain the following values:
	*	- @param String 'referer' 			Request referer
	* 	- @param String 'user_agent' 		Request user agent string
	* 	- @param String 'proxy_url' 		Proxy URL
	*	- @param String 'proxy_port' 		Proxy Port
	*	- @param String 'proxy_username' 	Proxy Username
	*	- @param String 'proxy_password' 	Proxy Password
	*	- @param String 'cookies' 			Cookies text file
	*/
	public function options( $options = NULL )
	{
		if( $options == NULL )
			return;
		
		//referer
		if( isset( $options['referer'] ) )
			$this->referer( $options['referer'] );
		
		//user agent
		if( isset( $options['user_agent'] ) )
			$this->user_agent( $options['user_agent'] );

		//proxy
		if( isset( $options['proxy_url'] ) )
		{
			$this->proxy( $options['proxy_url'], 
							isset( $options['proxy_post'] ) ? $options['proxy_url'] : '', 
							isset( $options['proxy_username'] ) ? $options['proxy_username'] : '', 
							isset( $options['proxy_password'] ) ? $options['proxy_passowrd'] : '' );
		}
		
		//cookies
		if( isset( $options['cookies'] ) )
			$this->cookies( $options['cookies'] );
	}
	
	/*
	* Set the referer for the impending request.
	*
	* $referer - str - The URL of the referer.
	*/
	public function referer( $referer='' )
	{
		//set referer string
		curl_setopt( $this->ch, CURLOPT_REFERER, $referer );
	}
	
	/*
	* Set the user agent to this request.
	* 
	* $user_agent - str - The User-Agent string to set.
	*/
	public function user_agent( $user_agent = '' )
	{
		//set user agent
		curl_setopt( $this->ch, CURLOPT_USERAGENT, $user_agent );
	}
	
	/*
	* Set proxy details for the request
	*
	* $url		- str - URL of the proxy (minus port number)
	* $port 	- int - Port number (will be appended to proxy URL)
	* $username - str - If needed, the proxy authentication username
	* $password - str - If needed, the proxy authentication password
	*/
	public function proxy( $url='', $port = 0, $username = '', $password = '' )
	{
		//Set proxy information
		curl_setopt( $this->ch, CURLOPT_PROXYAUTH, 				CURLAUTH_BASIC );				//Basic authentication
		curl_setopt( $this->ch, CURLOPT_PROXY, 					$url.($port>0?':'.$port:'') );	//Set proxy URL (Append port if provided)
		
		//Set the port if provided
		if( $port > 0 )
			curl_setopt( $this->ch, CURLOPT_PROXYPORT, 				$port );
			
		//Set username and password if provided
		if( $username )
			curl_setopt( $this->ch, CURLOPT_PROXYUSERPWD, 			$username.':'.$password ); 	
	}
	
	/*
	* Set a file to handle cookies generated/required for the request
	*
	* $file - str - relative path to the cookies file (txt)
	*/ 
	public function cookies( $file = 'cookie.txt' )
	{
		if( !$file )
			return;
		
		//Set cookie parameters
		curl_setopt( $this->ch, CURLOPT_COOKIEJAR, $file );
		curl_setopt( $this->ch, CURLOPT_COOKIEFILE, $file ); 
	}
	
	/*
	* Sends a GET request
	* 
	* $url - str - the full URL to send the request to.
	* $get - array - associative array of variables to send with the request
	*/
	public function get( $url = '', $get = NULL )
	{
		//append variables to the GET request
		if( strpos( $url, '?' ) === FALSE && is_array( $get ) )
			$url .= '?'.http_build_query($get);
		elseif( strpos( $url, '?' ) !== FALSE && is_array( $get ) )
			$url .= '&'.http_build_query($get);
		
		//insert the URL
		curl_setopt($this->ch, CURLOPT_URL, $url );
		
		//perform the request
		$data = curl_exec( $this->ch );
		if( !$data )
			throw new Exception( 'cURL: '.curl_error( $this->ch ) );

		return $data;
	}
	
	
	public function simpleGet($url){
		//insert the URL
		curl_setopt($this->ch, CURLOPT_URL, $url );
		
		//perform the request
		$data = curl_exec( $this->ch );
		if( !$data )
			throw new Exception( 'cURL: '.curl_error( $this->ch ) );

		return $data;
		
	}
	/*
	* Sends a POST request
	*
	* $url - str - URL to send the request to.
	* $post - array - An associative array of variables to send
	*/
	 public function post( $url = '', $post = NULL )
	{
		//set the POST property and parameters
		curl_setopt( $this->ch, CURLOPT_POST, true);
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS, http_build_query($post) ); 
		
		//insert the URL
		curl_setopt($this->ch, CURLOPT_URL, $url );
		
		//perform the request
		$data = curl_exec( $this->ch );
		if( !$data )
			throw new Exception( 'cURL: '.curl_error( $this->ch ) );
	
		return $data;
	} 
	
	public function postXml( $url, $request){
		
		$header = array(



 		"content-type: application/xml",



	);
		//set the POST property and parameters
		curl_setopt( $this->ch, CURLOPT_POST, true);
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS,$request ); 
		curl_setopt($this->ch, CURLOPT_HTTPHEADER,  $header);
		//insert the URL
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->ch, CURLOPT_URL, $url );
		
		//perform the request
		$data = curl_exec( $this->ch );
		if( !$data )
			throw new Exception( 'cURL: '.curl_error( $this->ch ) );
		$info = curl_getinfo($this->ch);
		return array($data, $info['total_time']);
		//return $data;
	}
	
	public function postJson($url, $request,$token){



if(empty($token)){



	$header = array(



 		"content-type: application/json",



	);



}else{



	$header = array(



 		"content-type: application/json",



		"token: ".$token,



	);



}


		//set the POST property and parameters
		curl_setopt( $this->ch, CURLOPT_POST, true);
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS,$request ); 
		curl_setopt($this->ch, CURLOPT_HTTPHEADER,  $header);
		//insert the URL
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->ch, CURLOPT_URL, $url );
		
		//perform the request
		$data = curl_exec( $this->ch );
		if( !$data )
			throw new Exception( 'cURL: '.curl_error( $this->ch ) );
		$info = curl_getinfo($this->ch);
		return array($data, $info['total_time']);



}

public function postJsonSimple($url, $request,$token){
	$header = array("content-type: application/json");

		//set the POST property and parameters
		curl_setopt( $this->ch, CURLOPT_POST, true);
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS,$request ); 
		curl_setopt($this->ch, CURLOPT_HTTPHEADER,  $header);
		//insert the URL
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->ch, CURLOPT_URL, $url );
		
		//perform the request
		$data = curl_exec( $this->ch );
		if( !$data )
			throw new Exception( 'cURL: '.curl_error( $this->ch ) );
		$info = curl_getinfo($this->ch);
		return array($data, $info['total_time']);



}

	public function postJsonAuth($url, $request,$header){


		//set the POST property and parameters
		curl_setopt( $this->ch, CURLOPT_POST, true);
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS,$request ); 
		curl_setopt($this->ch, CURLOPT_HTTPHEADER,  $header);
		//insert the URL
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->ch, CURLOPT_URL, $url );
		
		//perform the request
		$data = curl_exec( $this->ch );
		if( !$data )
			throw new Exception( 'cURL: '.curl_error( $this->ch ) );
		$info = curl_getinfo($this->ch);
		return $data;



	}
	
	public function postCurlTwilio($url,$request,$user){
		

		//set the POST property and parameters
		curl_setopt( $this->ch, CURLOPT_POST, true);
		curl_setopt( $this->ch, CURLOPT_POSTFIELDS, $request ); 
		curl_setopt( $this->ch, CURLOPT_RETURNTRANSFER, 1 ); 
		curl_setopt( $this->ch, CURLOPT_FOLLOWLOCATION, true ); 
		curl_setopt( $this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt( $this->ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt( $this->ch, CURLOPT_USERPWD, $user);	
		curl_setopt( $this->ch, CURLOPT_URL, $url );
		
		
		//perform the request
		$data = curl_exec( $this->ch );
		if( !$data )
			throw new Exception( 'cURL: '.curl_error( $this->ch ) );
		$info = curl_getinfo($this->ch);
		return $data;



	}
}
	
/*EOF*/