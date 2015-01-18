<?php
	function changeIris($camera, $direction) {
		global $return;

		if ($direction == "open") {
			$change = 500;
		}
		else if ($direction == "close") {
			$change = -500;
		}
		else {
			$return["message"] = "Unknown iris direction $direction";
			return;
		}

		$ip = cameraNumberToIp($camera);
		$curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,"http://$ip/axis-cgi/com/ptz.cgi?riris=$change");
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		curl_exec($curl_handle);
		curl_close($curl_handle);
	}
?>
