jQuery(function() {    
    /*
    * This function is used to display a confirmation box
    * It's triggered when using the data-confirm tag on any href link
    */
    $("[data-toggle=\"confirm\"]").on("click", function(ev) {
      ev.preventDefault();
      var lHref = $(this).attr('href');
      var lText = this.attributes.getNamedItem("data-title") ? this.attributes.getNamedItem("data-title").value : "Are you sure?";
        
      var locale = this.attributes.getNamedItem("data-locale").value;
      if (locale === 'en') {
        default_yes = 'Yes';
        default_no = 'No';
      } else {
        default_yes = 'Oui';
        default_no = 'Non';
      }

      var yesLabel = this.attributes.getNamedItem("data-yes-label") ? this.attributes.getNamedItem("data-yes-label").value : default_yes;
      var noLabel = this.attributes.getNamedItem("data-no-label") ? this.attributes.getNamedItem("data-no-label").value : default_no;    

      bootbox.confirm({
          buttons: {
            confirm: {
                label: yesLabel,
                className: 'btn btn-primary custom-btn bi bi-floppy2'
            },
           cancel: {
                label: noLabel,
                className: 'btn btn-light custom-btn bi bi-x-circle-fill'
            }
          },
        locale: locale,
        message: lText,
        backdrop: true,
        swapButtonOrder : true,
        callback: function (confirmed) {
          if (confirmed) {
            window.location.href = lHref;
          }
        }
        });
    });
});
