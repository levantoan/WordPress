/*
More https://contactform7.com/dom-events/
*/
document.addEventListener( 'wpcf7mailsent', function( event ) {
    if ( '11884' == event.detail.contactFormId ) {
        window.location.replace('https://levantoan.com');
    }
}, false );
