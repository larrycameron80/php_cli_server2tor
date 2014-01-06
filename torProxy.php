<?php
	
	/*
		
		Tor bridge for PHP CLI built-in server.
		This will run as a router script.
		
		http://www.php.net/manual/en/features.commandline.webserver.php
		
		Copyright 2014 Ort43v
		
		Licensed under the Apache License, Version 2.0 (the "License");
		you may not use this file except in compliance with the License.
		You may obtain a copy of the License at
		
			http://www.apache.org/licenses/LICENSE-2.0
		
		Unless required by applicable law or agreed to in writing, software
		distributed under the License is distributed on an "AS IS" BASIS,
		WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
		See the License for the specific language governing permissions and
		limitations under the License.
		
	*/
	
	const SCRIPT_VERSION = '1.0';
	
	ini_set('display_errors', 1);
	
	$host = '127.0.0.1';
	$port = '9050';
	
	$url = $_REQUEST['uri'];
	if (!$url) {
		header('Content-Length: 0', true, 404);
	}
	
	$userAgent = $_REQUEST['userAgent'];
	if (!$userAgent) {
		$userAgent = 'Mozilla/5.0 (compatible; MSIE 10.6; Windows NT 6.1; Trident/5.0; InfoPath.2; SLCC1; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729; .NET CLR 2.0.50727) 3gpp-gba UNTRUSTED/1.0';
	}
	
	$ch = curl_init();
	
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_PROXY, "http://$host:$port/");
	curl_setopt($ch, CURLOPT_PROXYTYPE, 7);
	
	$output = curl_exec($ch);
	
	$curl_error = curl_error($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close($ch);
	
	if (!headers_sent()) {
		header('X-Powered-By: Tor for PHP Built-in Server (' . SCRIPT_VERSION . ')', true, $code);
	}
	
	echo $output;
	if ($curl_error) file_put_contents('php://stderr', "cURL error:\n{$curl_error}\n");
	
	