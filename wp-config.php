<?php
/**
 * Podstawowa konfiguracja WordPressa.
 *
 * Ten plik zawiera konfiguracje: ustawień MySQL-a, prefiksu tabel
 * w bazie danych, tajnych kluczy i ABSPATH. Więcej informacji
 * znajduje się na stronie
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Kodeksu. Ustawienia MySQL-a możesz zdobyć
 * od administratora Twojego serwera.
 *
 * Ten plik jest używany przez skrypt automatycznie tworzący plik
 * wp-config.php podczas instalacji. Nie musisz korzystać z tego
 * skryptu, możesz po prostu skopiować ten plik, nazwać go
 * "wp-config.php" i wprowadzić do niego odpowiednie wartości.
 *
 * @package WordPress
 */

// ** Ustawienia MySQL-a - możesz uzyskać je od administratora Twojego serwera ** //
/** Nazwa bazy danych, której używać ma WordPress */
define('DB_NAME', 'stef_pol_bd');

/** Nazwa użytkownika bazy danych MySQL */
define('DB_USER', 'Dan');

/** Hasło użytkownika bazy danych MySQL */
define('DB_PASSWORD', 'Itsukushima1313');

/** Nazwa hosta serwera MySQL */
define('DB_HOST', 'localhost');

/** Kodowanie bazy danych używane do stworzenia tabel w bazie danych. */
define('DB_CHARSET', 'utf8mb4');

/** Typ porównań w bazie danych. Nie zmieniaj tego ustawienia, jeśli masz jakieś wątpliwości. */
define('DB_COLLATE', '');

/**#@+
 * Unikatowe klucze uwierzytelniania i sole.
 *
 * Zmień każdy klucz tak, aby był inną, unikatową frazą!
 * Możesz wygenerować klucze przy pomocy {@link https://api.wordpress.org/secret-key/1.1/salt/ serwisu generującego tajne klucze witryny WordPress.org}
 * Klucze te mogą zostać zmienione w dowolnej chwili, aby uczynić nieważnymi wszelkie istniejące ciasteczka. Uczynienie tego zmusi wszystkich użytkowników do ponownego zalogowania się.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'UHhh2tX#19nGr$-xCt$d{l.i{t4kODMgURG0U?n.4lEt+$?L61z]GIa}=2|Gv^$$');
define('SECURE_AUTH_KEY',  'hI|rI,Vq?d`X1=CJqij;MTdf|5& -OYZ#C+0`tr0p-Nm=*_6}Fta=v %D{6YA.I@');
define('LOGGED_IN_KEY',    ']]P#~ |RDE%ah<upeH/<g1.mxwaT!,+$rOYBpzq:q|lE[NvZ u;VJ7<:*OT8G~]9');
define('NONCE_KEY',        'r [+N(s~+% k:O6vDK#V&G8}HEPe`4IxFdW>XC]7+nQ{9tLgbbjx==u_5+0dGy=f');
define('AUTH_SALT',        '7f/$pVAZDe6r7<o8CLr]Xw!X>tQQmal[*.}&{#h,6aX9Ao_l6kV u!F+ B2d_V09');
define('SECURE_AUTH_SALT', 'vnziu(>V*6nF#oxA:l+|1MLYLV9-n@R#XZ gD+qZ?ci/!=#74K]7onAv(0uND;^m');
define('LOGGED_IN_SALT',   'zT`om0F`d18qd{+RY)&9 C8}wv}Q&-NNnwv,4X/.6^x-~9+MW?X9MxX?~JY]w0t:');
define('NONCE_SALT',       'p8Lpv+Ho.ZGah8s?VERGbX-hdg]BP,iEAznlCM7M9,)uEp&G/Q3*O/@2KT5JY+q5');

/**#@-*/

/**
 * Prefiks tabel WordPressa w bazie danych.
 *
 * Możesz posiadać kilka instalacji WordPressa w jednej bazie danych,
 * jeżeli nadasz każdej z nich unikalny prefiks.
 * Tylko cyfry, litery i znaki podkreślenia, proszę!
 */
$table_prefix  = 'wp_';

/**
 * Dla programistów: tryb debugowania WordPressa.
 *
 * Zmień wartość tej stałej na true, aby włączyć wyświetlanie ostrzeżeń
 * podczas modyfikowania kodu WordPressa.
 * Wielce zalecane jest, aby twórcy wtyczek oraz motywów używali
 * WP_DEBUG w miejscach pracy nad nimi.
 */
define('WP_DEBUG', false);

/* To wszystko, zakończ edycję w tym miejscu! Miłego blogowania! */

/** Absolutna ścieżka do katalogu WordPressa. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Ustawia zmienne WordPressa i dołączane pliki. */
require_once(ABSPATH . 'wp-settings.php');
