<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе установки.
 * Необязательно использовать веб-интерфейс, можно скопировать файл в "wp-config.php"
 * и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://ru.wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', 'C:\OpenServer\domains\cars\wp-content\plugins\wp-super-cache/' );
define( 'DB_NAME', 'cars' );

/** Имя пользователя MySQL */
define( 'DB_USER', 'cars__user' );

/** Пароль к базе данных MySQL */
define( 'DB_PASSWORD', 'm78fImKEKPVWQhDM' );

/** Имя сервера MySQL */
define( 'DB_HOST', 'localhost' );

/** Кодировка базы данных для создания таблиц. */
define( 'DB_CHARSET', 'utf8mb4' );

/** Схема сопоставления. Не меняйте, если не уверены. */
define( 'DB_COLLATE', '' );

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу. Можно сгенерировать их с помощью
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}.
 *
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными.
 * Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'PEU:D5fk$~4vRrs*KV]D4d,CBu.UU`G/1=gVQ23bRi}X90;GcHWlmls0Ks WSzQP' );
define( 'SECURE_AUTH_KEY',  'IG$,X%TY17^U+)]8EU%+Fb3N$*n@U>;XkNKVbVQ[67;Saefy1vm8wAn q:Xg;@Y[' );
define( 'LOGGED_IN_KEY',    't#Qeox%!pm#aN.93(m+MJacLRc-@$yMNS;AvJescY[b_9f5KGGuE4L<I)D]0L3Wf' );
define( 'NONCE_KEY',        'p9phLhsx>^S4y)U05=Yz~@4,X3RZaQMRa3sic0ui9Z[vj1D=Psury:oVujqVRE*f' );
define( 'AUTH_SALT',        ' qtcoY&#`$n{]f6V_jm:I!%&[/JvV|.:FIt5u_,pFp[Jr60,t#_EpZ3Iz]fyxDO+' );
define( 'SECURE_AUTH_SALT', 'kjlqv0{|W~QA-xdwp~S&:giJR9L67C5gKz5m,eTO,hr`*M$){S|94X@]iIlzah)X' );
define( 'LOGGED_IN_SALT',   'ZgvH.y*vTgA?Y$0R1w>oOi>m3&,6VF~(uI38!5fM*#97(;l%0CFLg_]sMB_(+8jh' );
define( 'NONCE_SALT',       'Nu4!lPQSC65~Oud-BsuJiUN}r>7fpqA5=^6m/&CrcS|GG-L0l.(7|h]sk$NG`),x' );

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix = 'wp_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 *
 * Информацию о других отладочных константах можно найти в документации.
 *
 * @link https://ru.wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Произвольные значения добавляйте между этой строкой и надписью "дальше не редактируем". */



/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Инициализирует переменные WordPress и подключает файлы. */
require_once ABSPATH . 'wp-settings.php';
