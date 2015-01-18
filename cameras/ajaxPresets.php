<?php
	function presetsLoad($camera) {
		global $return;

		// Load the existing presets
		$filename = presetsFilename($camera);
		$presetsJson = file_get_contents($filename);
		if ($presetsJson == "" || $presetsJson == "null") {
			return;
		}
		$presets = json_decode($presetsJson);

		// Grab the names
		foreach ($presets->presets as $currentPreset) {
			$return["presets"][] = $currentPreset->name;
		}
	}

	function presetsGo($camera, $presetName) {
		global $return;

		// Load the existing presets
		$filename = presetsFilename($camera);
		$presetsJson = file_get_contents($filename);
		$presets = json_decode($presetsJson);

		// Find the matching preset
		foreach ($presets->presets as $currentPreset) {
			if ($currentPreset->name == $presetName) {
				gotoLocation($camera, $currentPreset->pan, $currentPreset->tilt, $currentPreset->zoom);
			}
		}
	}

	function presetsNew($camera, $presetName) {
		global $return;

		// Get the current position
		$currentPosition = getCurrentPosition($camera);
		$currentPosition["name"] = $presetName;

		// Load the existing presets
		$filename = presetsFilename($camera);
		$presetsJson = file_get_contents($filename);
		$presets = json_decode($presetsJson);

		// Add the new preset
		$presets->presets[] = $currentPosition;

		// Save the changes
		$presetsJson = json_encode($presets);
		file_put_contents($filename, $presetsJson);
	}

	function presetsDelete($camera, $presetName) {
		global $return;

		// Load the existing presets
		$filename = presetsFilename($camera);
		$presetsJson = file_get_contents($filename);
		$oldpresets = json_decode($presetsJson);

		// Create the new presets array
		foreach ($oldpresets->presets as $currentPreset) {
			if ($currentPreset->name != $presetName) {
				$presets->presets[] = $currentPreset;
			}
		}

		// Save the changes
		$presetsJson = json_encode($presets);
		file_put_contents($filename, $presetsJson);
	}

	function presetsFilename($camera) {
		return "Camera" . $camera . "Presets.jsn"; 
	}
?>
