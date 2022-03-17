window.addEventListener( 'DOMContentLoaded', ( event ) => {
    // Expand clickable region of collapsible sections.
    document.querySelectorAll( '.gform-settings-panel--collapsible legend' ).forEach( el => {
        el.style.cursor = 'pointer';

        el.addEventListener( 'click', e => {
            e.target.closest( 'fieldset' ).querySelector( 'input' ).click();
        } );
    } );

    // Display optional active/inactive content when feed is activated/deactivated.
    document.querySelectorAll( '.manage-column img, .gform-status-indicator' ).forEach( el => el.addEventListener( 'click', e => {
        const parent = e.target.closest( 'tr' );
        const activeContent = parent.querySelector( '.active-content' );
        const inactiveContent = parent.querySelector( '.inactive-content' );

        if ( !activeContent || !inactiveContent ) {
            return;
        }

        activeContent.hidden ^= true;
        inactiveContent.hidden ^= true;
    } ) );
} );
