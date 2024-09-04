jQuery(function () {
  // Manage dbl click to edit page
  $(".jsTableRow").on("dblclick", function () {
    let link = $(location).attr('href');
    let position = link.indexOf("?");
    link = link.substring(0, position != -1 ? position : link.length);
    $(location).attr('href', link + '/edit/' + $(this).closest('tr').children('td:first').text());
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
});
