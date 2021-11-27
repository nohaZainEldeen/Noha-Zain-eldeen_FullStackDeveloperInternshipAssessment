$(function () {
  "use strict";
  $("[placeholder]")
    .focus(function () {
      $(this).attr("data-text", $(this).attr("placeholder"));
      $(this).attr("placeholder", " ");
    })
    .blur(function () {
      $(this).attr("placeholder", $(this).attr("data-text"));
    });
  // Add * on required field
  $("input").each(function () {
    if ($(this).attr("required") === "required") {
      $(this).after('<span class="asterisk">*</span>');
    }
  });
  // Convert Password field to text
  var passField = $(".password");
  $(".show-pass").hover(
    function () {
      passField.attr("type", "text");
    },
    function () {
      passField.attr("type", "password");
    }
  );
  // confirmation message on delete btn
  $(".confirm").click(function () {
    return confirm("Are You Sure?");
  });
});
