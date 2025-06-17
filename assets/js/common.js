jQuery(function () {
  // Manage dbl click to edit page
  $(".jsTableRow").on("dblclick", function () {
    //debugger;
    // Vérifier si la catégorie est interne
    if ($(this).data('is-internal') === true) {
      return; // Ne pas rediriger si isInternal est true
    }

    let link = $(location).attr('href');
    let position = link.indexOf("?");
    link = link.substring(0, position != -1 ? position : link.length);
    let textContent = $(this).closest('tr').children('td:first').text().trim();
    $(location).attr('href', link + '/edit/' + encodeURIComponent(textContent));
  });

  // Manage table filters
  $("#recipeFilter").on("keyup", function () {
    let value = $(this).val().toLowerCase();
    $("#recipeTable tr").filter(function () {
      if ($('#recipeTable tr').index($(this)) != 0) {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
      }
    });
  });
  
  // Manage table filters reset button
  $("#recipeFilterReset").on("click", function () {
    $('#recipeFilter').val("");
    $('#recipeFilter').trigger("keyup");
  });
  
  // Manage confirmation Modal dialog
  $('#confirmDeleteModal').on('show.bs.modal', function (e) {
    // Define label
    var label = $(e.relatedTarget).data('label');
    if (label) {
      $(".modal-body").text(label);
    }

    // Define link on confirm button
    var href = $(e.relatedTarget).data('href');
    $("#confirmDelete").on("click", (function (e) {
      window.location.href = href;
    }))
  });

  // Apply Bootstrap style to the vich-images checkbox
  $('#user_imageFile_delete, #recipe_imageFile_delete').addClass("form-check-input");

  // Display alert message box
  if ($('.alert').length) {
    $('.alert').fadeTo(250, 1).delay(5000).slideUp(500, 0);
  }

  // Set active to current menu link
  jQuery.find(".nav-link").forEach((link) => {
    if (link.href === window.location.href) {
        link.classList.add("active");
        link.setAttribute("aria-current", "page");
    }
});
});
