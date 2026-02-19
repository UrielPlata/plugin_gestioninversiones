$ = jQuery.noConflict();
//
// Tabs Toggler
//

( function( $ ) {

  $( document ).ready(function() {

    var inactive = getParameterByName("login");
    const dataregister = $( '.login_error' );

    if( inactive !== "" ){
      $(dataregister).show();
    }
  });

  function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  }

}( jQuery ) );
