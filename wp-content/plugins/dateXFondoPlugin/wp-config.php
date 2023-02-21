<?php
/**
 * Il file base di configurazione di WordPress.
 *
 * Questo file viene utilizzato, durante l’installazione, dallo script
 * di creazione di wp-config.php. Non è necessario utilizzarlo solo via web
 * puoi copiare questo file in «wp-config.php» e riempire i valori corretti.
 *
 * Questo file definisce le seguenti configurazioni:
 *
 * * Impostazioni MySQL
 * * Chiavi Segrete
 * * Prefisso Tabella
 * * ABSPATH
 *
 * * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Impostazioni MySQL - È possibile ottenere queste informazioni dal proprio fornitore di hosting ** //
/** Il nome del database di WordPress */
define( 'DB_NAME', "c5datexfondo" );


/** Nome utente del database MySQL */
define( 'DB_USER', "c5date" );


/** Password del database MySQL */
define( 'DB_PASSWORD', "AHh9e!Uuu9uN" );


/** Hostname MySQL  */
define( 'DB_HOST', "localhost" );


/** Charset del Database da utilizzare nella creazione delle tabelle. */
define( 'DB_CHARSET', 'utf8mb4' );


/** Il tipo di Collazione del Database. Da non modificare se non si ha idea di cosa sia. */
define('DB_COLLATE', '');

define('DB_PORT','3306');
/**#@+
 * Chiavi Univoche di Autenticazione e di Salatura.
 *
 * Modificarle con frasi univoche differenti!
 * È possibile generare tali chiavi utilizzando {@link https://api.wordpress.org/secret-key/1.1/salt/ servizio di chiavi-segrete di WordPress.org}
 * È possibile cambiare queste chiavi in qualsiasi momento, per invalidare tuttii cookie esistenti. Ciò forzerà tutti gli utenti ad effettuare nuovamente il login.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'uk3wVAD:)s{7SJBC5C1<Y^gTy.Gi74G;H7tG0S/;NJYVl-`Qt0]0X<Gd_TPs-Od6' );

define( 'SECURE_AUTH_KEY',  'It_XSVjip0q4&4,S~!:qMc;3Xt(orGu]CZD/|SV&emE?sD/K*g3PS99jC.i;O% N' );

define( 'LOGGED_IN_KEY',    'nyD&U_zX{3HD3([mWjKz7#~EIeg7a,AR@]4[_EDYRDQ:o$/SB(w3,7O`QYG8=LUg' );

define( 'NONCE_KEY',        '![lVO/S/T7Vn2VCJ*pzi_ECmV7u{,n,g`oqq1|K_,raT|+Rm_|}h<pVq1;eIW+{R' );

define( 'AUTH_SALT',        '!KJbj)dy1eli^5vFu,Sg{0IEQIXm)eDa,?vCBJG[tgGb)9f_7HP!,~NF1{1p@Erp' );

define( 'SECURE_AUTH_SALT', '<qVN/3Yu $uB,`HD p9esZ`(1WVo/xpZi$u,wXvh9aOA+k68tY;/CN$;`OTGB;=(' );

define( 'LOGGED_IN_SALT',   'eZNtlp8J/K#LDC#-:py{vr]3]>7DffL.|Bkn^QcBfWyy#Oc/O;7bV-vWc. h?w~<' );

define( 'NONCE_SALT',       'yK/[MX`%#Fb>nL!*r94*7p{J3p*-$v2y^nBv<@}*_RlWEos>W1<28N$nl8tZeRJZ' );


/**#@-*/

/**
 * Prefisso Tabella del Database WordPress.
 *
 * È possibile avere installazioni multiple su di un unico database
 * fornendo a ciascuna installazione un prefisso univoco.
 * Solo numeri, lettere e sottolineatura!
 */
$table_prefix = 'wp_';


/**
 * Per gli sviluppatori: modalità di debug di WordPress.
 *
 * Modificare questa voce a TRUE per abilitare la visualizzazione degli avvisi durante lo sviluppo
 * È fortemente raccomandato agli svilupaptori di temi e plugin di utilizare
 * WP_DEBUG all’interno dei loro ambienti di sviluppo.
 *
 * Per informazioni sulle altre costanti che possono essere utilizzate per il debug,
 * leggi la documentazione
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );


/* Finito, interrompere le modifiche! Buon blogging. */

/** Path assoluto alla directory di WordPress. */
define( 'WP_SITEURL', 'http://datexfondo.online/' );
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Imposta le variabili di WordPress ed include i file. */
require_once(ABSPATH . 'wp-settings.php');
