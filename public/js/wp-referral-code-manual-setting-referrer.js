jQuery(document).ready(function ($) {
  $("body").append(`
	<div id="referral-popup-overlay">
		<div id="referral-popup-content">
    			<form id="referral-form">
        			<div id="warning-message">
           				<p>WARNING<br>
            				Please be advised that once you commit, it cannot be changed.</p>
        			</div>

        			<label id="referral-code-label" for="referral-code"></label>
        			<input type="text" id="referral-code" name="referral-code" placeholder="Referrer Membership No." value="">

        			<div id="referral-buttons">
            				<button type="button" id="cancel-button">Cancel</button>
            				<button type="submit" id="commit-button">Commit</button>
        			</div>
    			</form>
		</div>
	</div>
`);

  // Set the click event for the close button to remove the overlay and preview image
  $("#nv-referral-code-validation-button").on("click", function () {
    $("#referral-popup-overlay").css("display", "flex"); // Show the overlay
    $("body").css("overflow", "hidden"); // Disable scrolling on the body
  });

  // Handle the form submission
  $("#referral-form").submit(function (e) {
    e.preventDefault();

    var referralCode = $("#referral-code").val();

    // Prepare the data for the AJAX request
    var data = {
      action: "manual_setting_referer", // Correct action name
      referral_code: referralCode,
    };

    // Send the AJAX request to the server
    $.post(ajaxurl, data, function (response) {
      if (response == 0) {
        $("#referral-code-label").text(
          "An error occurred: Invalid action specified."
        );
      } else if (response.success) {
        alert(
          response.data.message +
            "\nReferred by: " +
            response.data.referrer_name
        );

        $("#referral-popup-overlay").css("display", "none");
        $("body").css("overflow", "auto");
        $("#referral-code-label").text("");

        // Page reload after successful submission
        setTimeout(function () {
          // Refresh the page
          location.reload();
        }, 300);
      } else {
        switch (response.data.error_code) {
          case "REFERRAL_CODE_MISSING":
            $("#referral-code-label").text("Please enter a referral code.");
            break;
          case "REFERRAL_NOT_FOUND":
            $("#referral-code-label").text(
              "Invalid referral code. Please try again."
            );
            break;
          case "USER_NOT_LOGGED_IN":
            $("#referral-code-label").text(
              "You must be logged in to apply a referral code."
            );
            break;
          case "ALREADY_REFERRED":
            $("#referral-code-label").text("You have already been referred.");
            break;
          default:
            $("#referral-code-label").text(
              "An error occurred: " + response.data.message
            );
        }
      }
    }).fail(function () {
      $("#referral-code-label").text(
        "An error occurred: " + response.data.message
      );
    });
  });

  // Handle the Cancel button click - Close the popup
  $("#cancel-button").click(function () {
    $("#referral-popup-overlay").css("display", "none");
    $("body").css("overflow", "auto");
    $("#referral-code-label").text("");
  });
});
