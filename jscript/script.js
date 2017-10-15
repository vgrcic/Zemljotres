$(document).ready(function() {
	// on focus remove error text from input
	$("input, textarea").focus(function() {
		$(this).next(".error").text("");
	});
});

function validateEmailForm() {
	var errors = {
		ime : 'Unesite ime',
		telefon : 'Unesite broj telefona',
		tema : 'Unesite temu',
		poruka : 'Unesite tekst poruke',
	}
	var fields = ['ime', 'telefon', 'tema', 'poruka'];
	for (i = 0; i < fields.length; i++) {
		if ($("#" + fields[i]).val().trim() == "") {
			$("#" + fields[i]).next(".error").text(errors[fields[i]]);
			return false;
		}
	}
	return true;
}

function gallery(imgName) {
	document.getElementById("picture").src = "images/gallery/" + imgName + ".jpg";
	document.getElementById("screen").style.height = "465px";
}

function playTrack(id, audio) {
	if ($("#source").attr("src") == "audio/" + audio) {
		// if paused, resume
		$("audio")[0].play();
	} else {
		// load audio and play when fully loaded
		$("#source").attr("src", "audio/" + audio);
		$("audio")[0].pause();
		$("audio")[0].load();
		$("audio")[0].oncanplay = $("audio")[0].play();
	}
	// reset pause and play buttons and then add the pause button
	// this is to prevent two pause buttons from showing when a play
	// button is pressed while another track is already playing
	refreshButtons();
	$("#" + id + " .play-btn").hide();
	$("#" + id + " .play-btn").before("<img class=\"pause-btn\" height=\"17px\" width=\"17px\" src=\"images/pause_button.png\" onclick=pauseTrack(" + id + ") >");
}

function pauseTrack(id) {
	$("audio")[0].pause();
	$("#" + id + " .pause-btn").remove();
	$("#" + id + " img").show();
}

function refreshButtons() {
	// removes all pause buttons and restores the play buttons
	$(".pause-btn").remove();
	$(".play-btn").show();
}

function showLyrics(id) {
	$(".lyrics div").fadeOut(0);
	$("#lyrics-" + id).fadeIn(500);
}