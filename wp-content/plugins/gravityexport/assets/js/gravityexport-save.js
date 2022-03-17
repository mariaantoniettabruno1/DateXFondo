window.addEventListener( 'DOMContentLoaded', ( event ) => {
    const { ajaxAction, formInputFieldNamePrefix, formInputFieldParentContainerPrefix, incompleteTestMessage, nonce } = window.gravityexport_save_strings || {};

    const $testConnectionButton = document.getElementById( 'connection-test' );

    let $testResultElement = document.getElementById( 'connection-test-result' );
    if ( $testResultElement ) {
        $testResultElement = $testResultElement.querySelector( 'p' );
    }

    const toggleTestButtonState = state => {
        $testConnectionButton.disabled = $testConnectionButton.ariaDisabled = state === 'disabled';
        $testConnectionButton.querySelector( '.spinner' ).hidden = !$testConnectionButton.disabled;
    };

    const showTestResult = ( status, message ) => {
        $testResultElement.hidden = false;
        $testResultElement.closest( 'div' ).classList.add( status );

        $testResultElement.innerHTML = message;
    };

    const clearTestResult = () => {
        $testResultElement.hidden = true;
        $testResultElement.closest( 'div' ).classList.remove( 'success', 'failure' );

        $testResultElement.innerHTML = '';
    };

    const testConnection = () => {
        clearTestResult();

        toggleTestButtonState( 'disabled' );

        if ( !ajaxAction ) {
            return;
        }

        const body = new FormData();

        body.append( 'action', ajaxAction );
        body.append( '_nonce', nonce );

        // Map GF setting input field name (prefixed according to GF version) to setting parameter (see GravityKit\GravityExport\Save\Service\ConnectionManager:testConnection())
        const inputNameToSettingParameterMap = {
            [ formInputFieldNamePrefix + 'storage_type' ]: 'service',
            [ formInputFieldNamePrefix + 'ftp_host' ]: 'host',
            [ formInputFieldNamePrefix + 'ftp_port' ]: 'port',
            [ formInputFieldNamePrefix + 'ftp_ssl' ]: 'ssl',
            [ formInputFieldNamePrefix + 'ftp_passive' ]: 'passive',
            [ formInputFieldNamePrefix + 'ftp_username' ]: 'username',
            [ formInputFieldNamePrefix + 'ftp_password' ]: 'password',
            [ formInputFieldNamePrefix + 'sftp_private_key' ]: 'private_key',
            [ formInputFieldNamePrefix + 'sftp_private_key_passphrase' ]: 'private_key_passphrase',
            [ formInputFieldNamePrefix + 'storage_path' ]: 'path',
        };

        document.querySelectorAll( `[name^="${ formInputFieldNamePrefix }"]` ).forEach( input => {
            if ( !input.name in inputNameToSettingParameterMap || ( input.type === 'radio' && !input.checked ) ) {
                return;
            }

            if ( inputNameToSettingParameterMap[ input.name ] === 'service' ) {
                body.append( 'service', input.value );
            } else {
                body.append( 'settings[' + inputNameToSettingParameterMap[ input.name ] + ']', input.value );
            }
        } );

        async function runTest () {
            const response = await fetch( window.ajaxurl, {
                method: 'POST',
                body
            } );

            if ( response.status >= 400 && response.status < 600 ) {
                return response;
            }

            return response.json();
        }

        runTest()
          .then( response => {
              if ( !response || !response.data ) {
                  throw new Error( incompleteTestMessage );
              } else if ( !response.success ) {
                  throw new Error( response.data );
              } else {
                  showTestResult( 'success', response.data );
              }
          } )
          .catch( error => showTestResult( 'failure', error.message ) )
          .finally( toggleTestButtonState );
    };

    if ( $testConnectionButton ) {
        $testConnectionButton.addEventListener( 'click', testConnection );
    }

    // Toggle private key/key passphrase fields when SFTP (SSL input value "2") is selected & change port.
    document.querySelectorAll( '[id^="ftp_ssl"]' ).forEach( el => el.addEventListener( 'change', ssl => {
        document.querySelectorAll( '[id^="sftp_private"]' ).forEach( sftpField => {
            const $parentContainerEl = sftpField.closest( `[id^="${ formInputFieldParentContainerPrefix }"]` );
            const isSftp = ssl.target.value === '2';

            $parentContainerEl.hidden = !isSftp;

            document.getElementById( 'ftp_port' ).value = isSftp ? 22 : 21;
        } );
    } ) );

    // Clear path before changing storage type.
    document.querySelectorAll( '[id^="storage_type"]:not(:checked)' ).forEach( el => el.addEventListener( 'click', () => {
        document.getElementById( 'storage_path' ).value = '';
    } ) );

    // Hide the custom filename container if the filename_format is not "custom".
    document.querySelectorAll( '[id^="filename_format"]' ).forEach( el => el.addEventListener( 'change', e => {
        document.querySelector( '[id$="_filename"]' ).hidden = e.target.value !== 'custom';
    } ) );

    // Hide filename formats based on the export type setting.
    document.querySelectorAll( '[id^="file_type"]' ).forEach( exportType => exportType.addEventListener( 'change', e => {
        const exportType = e.target.value;

        document.querySelectorAll( '[id*="radio-choice-filename_format"]' ).forEach( $fileFormatContainerEl => {
            const $fileFormatInputEl = $fileFormatContainerEl.querySelector( 'input' );

            $fileFormatContainerEl.hidden = !$fileFormatInputEl.value.match( new RegExp( `^${exportType}` ) ) && $fileFormatInputEl.value !== 'custom';
        } );
    } ) );

    if ( document.querySelector( '[id^="file_type"]:checked' ) ) {
        document.querySelectorAll( '[id^="file_type"]:checked,[id^="filename_format"]:checked' ).forEach( el => {
            el.dispatchEvent( new Event( 'change' ) );
        } );
    }
} );
