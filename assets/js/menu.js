$ = jQuery.noConflict();
//
// Tabs Toggler
//

( function( $ ) {

  document.querySelector('.second-button').addEventListener('click', function () {

    document.querySelector('.animated-icon2').classList.toggle('open');
  });

}( jQuery ) );
