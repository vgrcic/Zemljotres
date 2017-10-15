// Zemljotres control panel script
// Author: Veselin Grcić

$(document).ready(function() {

	// remove form error message on focus
	$("#heading, #content").focus(function() {
		$(this).next().text("");
	});

});

function addHyperlink() {
	document.getElementById("textarea").value += "<a href=\"LINK\" target=\"_blank\">TEKST</a>";
}

function optionChange() {

	value = $("#target").val();
	if (!isNaN(value)) {
		// Put the values from the coresponding table row into the form
		$("#heading").val($("#post-" + value + " .heading").text());
		content = $("#post-" + value + " .content").html();
		$("#content").html(content.replace("<br>", "\n"));
		$("#submit").val("Update");
	} else {
		// Empty the form fields
		$("#heading").val("");
		$("#content").html("");
		$("#submit").val("Post");
	}
	// Remove any leftover validation errors
	$(".error").text("");
	
}


function validatePostForm() {

	if ($("#heading").val().trim() == "") {
		$("#heading").next().text("Please enter the heading");
		return false;
	}
	if ($("#content").val().trim() == "") {
		$("#content").next().text("Please enter the content");
		return false;
	}
	return true;

}