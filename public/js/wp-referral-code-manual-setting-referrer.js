jQuery(document).ready(function ($) {
  $("body").append(`
	<div id="wrc-referral-popup-overlay">
		<div id="wrc-referral-popup-content">
    			<form id="wrc-referral-form">
        			<div id="wrc-warning-message">
           				<p>${translations.manual_setting_referrer_warning_title}<br>
            				${translations.manual_setting_referrer_warning_description}</p>
        			</div>

        			<label id="wrc-referral-code-label" for="wrc-referral-code"></label>
        			<input type="text" id="wrc-referral-code" name="wrc-referral-code" placeholder="${translation.manual_setting_referrer_input_placeholder}" value="">

        			<div id="wrc-referral-buttons">
            				<button type="button" id="wrc-cancel-button">${translation.manual_setting_referrer_cancel_button_title}</button>
            				<button type="submit" id="wrc-commit-button">${translation.manual_setting_referrer_commit_button_title}</button>
        			</div>
    			</form>
		</div>
	</div>
`);

  // Set the click event for the close button to remove the overlay and preview image
  $("#nv-referral-code-manual-setting-referrer-button").on(
    "click",
    function () {
      $("#wrc-referral-popup-overlay").css("display", "flex"); // Show the overlay
      $("body").css("overflow", "hidden"); // Disable scrolling on the body
    }
  );

  // Handle the form submission
  $("#wrc-referral-form").submit(function (e) {
    e.preventDefault();

    var referralCode = $("#wrc-referral-code").val();

    // Prepare the data for the AJAX request
    var data = {
      action: "manual_setting_referer", // Correct action name
      referral_code: referralCode,
    };

    // Send the AJAX request to the server
    $.post(ajaxurl, data, function (response) {
      if (response == 0) {
        $("#wrc-referral-code-label").text(
          translations.manual_setting_referrer_error
        );
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
