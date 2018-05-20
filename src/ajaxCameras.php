<?php
	require_once "ajaxPresets.php";
	require_once "ajaxMovebystep.php";

	$return["result"] = TRUE;
	$return["message"] = "";

	$camera = $_POST["camera"];
	$presetName = $_POST["presetName"];
	$direction = $_POST["direction"];

	switch($_POST["action"]) {
		case "ajaxTest":
			$return["message"] = "Test succeeded";
			break;

		// Move by step
		case "changeIris":
			changeIris($camera, $direction);
			break;

		// Presets
		case "presetsLoad":
			presetsLoad($camera);
			break;

		case "presetsGo":
			presetsGo($camera, $presetName);
			break;

		case "presetsNew":
			presetsNew($camera, $presetName);
			break;

		case "presetsDelete":
			presetsDelete($camera, $presetName);
			break;

		default:
			$return["result"] = FALSE;
			$return["message"] = "Action {$_POST["action"]} not recognized";
			break;
	}

	echo json_encode($return);

	// Camera actions
	function gotoLocation($camera, $pan, $tilt, $zoom) {
		$ip = cameraNumberToIp($camera);
		$curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,"http://$ip/axis-cgi/com/ptz.cgi?pan=$pan&tilt=$tilt&zoom=$zoom");
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		curl_exec($curl_handle);
		curl_close($curl_handle);
	}

	function getCurrentPosition($camera) {
		$ip = cameraNumberToIp($camera);
		$curl_handle=curl_init();
		curl_setopt($curl_handle,CURLOPT_URL,"http://$ip/axis-cgi/com/ptz.cgi?query=position");
		curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
		$rawPositions = curl_exec($curl_handle);
		curl_close($curl_handle);

		$positionsArray = split("\r\n", $rawPositions);

		foreach ($positionsArray as $currentToken) {
			if ($currentToken != "") {
				$currentKeyValuePair = split("=", $currentToken);
				chop($currentKeyValuePair[1]);
				$return[$currentKeyValuePair[0]] = $currentKeyValuePair[1];
			}
		}

		return $return;
	}

	function cameraNumberToIp($camera) {
		switch ($camera) {
			case "1" :
				return "10.0.5.5";
				break;

			case "2" :
				return "10.0.5.6";
				break;

			default :
				die("Unknown camera number");
				break;
		}
	}

?>
