function changeIris()
{
	var camera = $(this).attr("camera");
	var direction = $(this).attr("direction");

	$.ajax({
		url: "ajaxCameras.php",
		type: "POST",
		data: {
			"action": "changeIris",
			"camera": camera,
			"direction": direction
		},
		dataType: "json",
		success: function(data) {
			if (data.message != "") {
				alert(data.message);
			}
		},
		error: function(data) {
			alert("Error changing iris");
		}
	});
}

