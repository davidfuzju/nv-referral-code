jQuery(document).ready(function ($) {
  $("body").append(`
	<div id="wrc-referral-popup-overlay">
		<div id="wrc-referral-popup-content">
    			<form id="wrc-referral-form">
        			<div id="wrc-warning-message">
           				<p>${translation.warning_title}<br>
            				${translation.warning_description}</p>
        			</div>

        			<label id="wrc-referral-code-label" for="wrc-referral-code"></label>
        			<input type="text" id="wrc-referral-code" name="wrc-referral-code" placeholder="${translation.input_placeholder}" value="">

        			<div id="wrc-referral-buttons">
            				<button type="button" id="wrc-cancel-button">${translation.cancel_button_title}</button>
            				<button type="submit" id="wrc-commit-button">${translation.commit_button_title}</button>
        			</div>
    			</form>
		</div>
	</div>
`);

  $("#wrc-referral-popup-overlay").css("display", "flex"); // Show the overlay
  $("body").css("overflow", "hidden"); // Disable scrolling on the body

  // Handle the form submission
  $("#wrc-referral-form").submit(function (e) {
    e.preventDefault();

    var referralCode = $("#wrc-referral-code").val();

    // Prepare the data for the AJAX request
    var data = {
      action: "manual_setting_referrer", // Correct action name
      referral_code: referralCode,
    };

    // Send the AJAX request to the server
    $.post(ajaxurl, data, function (response) {
      if (response == 0) {
        $("#wrc-referral-code-label").text(translation.error);
      } else if (response.success) {
        alert(response.data.message + response.data.referrer_name);

        $("#wrc-referral-popup-overlay").css("display", "none");
        $("body").css("overflow", "auto");
        $("#wrc-referral-code-label").text("");

        // Page reload after successful submission
        setTimeout(function () {
          // Refresh the page
          location.reload();
        }, 300);
      } else {
        switch (response.data.error_code) {
          case "REFERRAL_CODE_MISSING":
            break;
          case "REFERRAL_NOT_FOUND":
            break;
          case "USER_NOT_LOGGED_IN":
            break;
          case "ALREADY_REFERRED":
            break;
          default:
            break;
        }
        $("#wrc-referral-code-label").text(response.data.message);
      }
    }).fail(function () {
      $("#wrc-referral-code-label").text(response.data.message);
    });
  });

  // Handle the Cancel button click - Close the popup
  $("#wrc-cancel-button").click(function () {
    $("#wrc-referral-popup-overlay").css("display", "none");
    $("body").css("overflow", "auto");
    $("#wrc-referral-code-label").text("");
  });
});
