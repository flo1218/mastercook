jQuery(function() {    
    /*
    * This function is used to display a confirmation box
    * It's triggered when using the data-confirm tag on any href link
    */
    $("[data-toggle=\"confirm\"]").on("click", function(ev) {
      ev.preventDefault();
      var lHref = $(this).attr('href');
      var lText = this.attributes.getNamedItem("data-title") ? this.attributes.getNamedItem("data-title").value : "Are you sure?";
      // var yesLabel = this.attributes.getNamedItem("data-yes-label") ? this.attributes.getNamedItem("data-yes-label").value : 'Yes';
      // var noLabel = this.attributes.getNamedItem("data-no-label") ? this.attributes.getNamedItem("data-no-label").value : 'No';      
      var locale = this.attributes.getNamedItem("data-locale").value;

      bootbox.confirm({
        //  buttons: {
        //    confirm: {
        //        label: yesLabel,
        //        className: 'btn btn-primary'
        //    },
        //   cancel: {
        //        label: noLabel,
        //        className: 'btn btn-secondary'
        //    }
        //  },
        locale: locale,
        message: lText,
        backdrop: false,
        callback: function (confirmed) {
          if (confirmed) {
            window.location.href = lHref;
          }
        }
        });
    });
});
