var postURL = "http://localhost/githubprojects/cameracontrol/cameras/";

function presetsLoadAll()
{
	$(".cameraControls").each( function() {
		presetsLoadSpecific($(this).attr("camera"));
	});
}

function presetsLoadSpecific(camera)
{
	$.ajax({
		url: postURL+"ajaxCameras.php",
		type: "POST",
		data: {
			"action": "presetsLoad",
			"camera": camera
		},
		dataType: "json",
		success: function(data) {
			if (data.message != "") {
				alert(data.message);
			}
			
			 if(typeof (data.presets) == 'undefined' ) {
				return; 
			 }
			
			// Clear any existing presets
			var divPresetList = $(".divPresetsList[camera=" + camera + "]")
			divPresetList.html("");

			for (currentPreset = 0 ; currentPreset < data.presets.length ; currentPreset++) {
				// Create the div for the new preset
				currentPresetName = data.presets[currentPreset];
				currentPresetDiv = $("<div></div>").attr("camera", camera).attr("presetname", currentPresetName).attr("id", presetsGenerateDivId(camera, currentPresetName)).addClass("presetBtn").html(currentPresetName);
				
				// Register event handlers for the new preset
				currentPresetDiv.click( function() {
					presetsSelect($(this));
				});

				// Add the new preset to the page
				currentPresetDiv.appendTo(divPresetList);
			}
		},
		error: function(data) {
			alert("Error loading existing presets");
		}
	});
	
}

function presetsGo()
{
	var selected = $(".selected");

	if (selected.length == 0) {
		alert("No preset selected");
		return;
	}

	var camera = selected.attr("camera");
	var presetName = selected.attr("presetname");

	$.ajax({
		url: postURL+"ajaxCameras.php",
		type: "POST",
		data: {
			"action": "presetsGo",
			"camera": camera,
			"presetName": presetName
		},
		dataType: "json",
		success: function(data) {
			if (data.message != "") {
				alert(data.message);
			}
		},
		error: function(data) {
			alert("Error going to preset");
		}
	});
}

function presetsNew()
{
	var presetName = prompt("Name the new preset");
	var camera = $(this).attr("camera");

	if(typeof(Storage) !== "undefined") {
		//local storage available
		localStorage.setItem(presetName+"camera", "CAMVAL");
	} else {
		//This block should not run in modern browsers with local storage
	}


	// $.ajax({
	// 	url: "ajaxCameras.php",
	// 	type: "POST",
	// 	data: {
	// 		"action": "presetsNew",
	// 		"camera": camera,
	// 		"presetName": presetName
	// 	},
	// 	dataType: "json",
	// 	success: function(data) {
	// 		if (data.message != "") {
	// 			alert(data.message);
	// 		}
	// 		presetsLoadSpecific(camera);
	// 	},
	// 	error: function(data) {
	// 		alert("Error saving new preset");
	// 	}
	// });
}


function presetsDelete()
{
	var selected = $(".selected");

	if (selected.length == 0) {
		alert("No preset selected");
		return;
	}

	var camera = selected.attr("camera");
	var presetName = selected.attr("presetname");

	if (!confirm("Are you sure you want to delete preset " + presetName)) {
		return;
	}

	$.ajax({
		url: postURL+"ajaxCameras.php",
		type: "POST",
		data: {
			"action": "presetsDelete",
			"camera": camera,
			"presetName": presetName
		},
		dataType: "json",
		success: function(data) {
			if (data.message != "") {
				alert(data.message);
			}
			presetsLoadSpecific(camera);
		},
		error: function(data) {
			alert("Error deleting preset");
		}
	});
}

function presetsGenerateDivId(camera, preset)
{
	return "presetCamera" + camera + "Preset" + preset;
}

function presetsSelect(selected)
{
	$(".selected").removeClass("selected");
	selected.addClass("selected");
}
