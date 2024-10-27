jQuery(document).ready(function ($) {
  $("#wrc-search-userid-button").click(function () {
    var username = $("#wrc-username-input").val().trim();

    if (username === "") {
      $("#wrc-user-id-result")
        .text("Please enter a username.")
        .removeClass("success")
        .addClass("error");
      return;
    }

    // Prepare the data for the AJAX request
    var data = {
      action: "get_user_id_by_username",
      username: username,
    };

    // Send AJAX request
    $.post(ajaxurl, data, function (response) {
      if (response.success) {
        $("#wrc-user-id-result")
          .text("User ID: " + response.data.userid)
          .removeClass("error")
          .addClass("success");
      } else {
        $("#wrc-user-id-result")
          .text("User not found.")
          .removeClass("success")
          .addClass("error");
      }
    }).fail(function () {
      $("#wrc-user-id-result")
        .text("An error occurred. Please try again.")
        .removeClass("success")
        .addClass("error");
    });
  });
});
