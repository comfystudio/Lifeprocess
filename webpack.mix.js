const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/assets/js/app.js', 'public_html/js')
//    .sass('resources/assets/sass/app.scss', 'public_html/css');

mix.combine([
    'resources/themes/limitless/assets/css/bootstrap-admin.css',
    'resources/themes/limitless/assets/css/core.css',
    'resources/themes/limitless/assets/css/colors.css',
    'resources/themes/limitless/assets/css/components.css',
    'resources/themes/limitless/assets/css/custom.css',
    'resources/themes/limitless/assets/css/jquery.datetimepicker.css',
    'resources/themes/limitless/assets/css/icons/icomoon/styles.css',
    'resources/themes/limitless/assets/css/icons/fontawesome/styles.min.css',
    'resources/themes/limitless/assets/css/datatables.bootstrap.css',
    'resources/themes/limitless/assets/css/extras/sweetalert2.css',
    'resources/themes/limitless/assets/css/emoji.css'
    'resources/themes/limitless/assets/css/bootstrap.css'
], 'public_html/themes/limitless/css/all.css')
    // .copy('resources/themes/limitless/assets/css/extras', 'public_html/build/themes/limitless/css/extras')
    // .copy('resources/themes/limitless/assets/css/receipt', 'public_html/themes/limitless/css/receipt')
    .copy('resources/themes/limitless/assets/css/icons', 'public_html/themes/limitless/css/icons')
    // .copy('resources/themes/limitless/assets/images', 'public_html/build/themes/limitless/images')
    .copy('resources/themes/limitless/assets/images', 'public_html/themes/limitless/images');


mix.combine([
        'resources/themes/limitless/assets/js/core/libraries/jquery.min.js',
        'resources/themes/limitless/assets/js/core/libraries/bootstrap.min.js',
        // 'resources/themes/limitless/assets/js/core/libraries/jquery-3.3.7.js',
        // 'resources/themes/limitless/assets/js/core/libraries/bootstrap-3.3.7.js',
        'resources/themes/limitless/assets/js/emoji/config.js',
        'resources/themes/limitless/assets/js/emoji/util.js',
        'resources/themes/limitless/assets/js/emoji/jquery.emojiarea.js',
        'resources/themes/limitless/assets/js/emoji/emoji-picker.js',
        'resources/themes/limitless/assets/js/plugins/forms/selects/select2.min.js',
        'resources/themes/limitless/assets/js/jquery.datetimepicker.js',
        'resources/themes/limitless/assets/js/plugins/tables/datatables/datatables.min.js',
        'resources/themes/limitless/assets/js/plugins/media/fancybox.min.js',
        'resources/themes/limitless/assets/js/jquery.ui.widget.js',
        'resources/themes/limitless/assets/js/jquery.iframe-transport.js',
        'resources/themes/limitless/assets/js/jquery.fileupload.js',
        'resources/themes/limitless/assets/js/bootbox.min.js',
        'resources/themes/limitless/assets/js/plugins/notifications/sweetalert2.min.js',
        'resources/themes/limitless/assets/js/jqery.fieldsaddmore.js',
        'resources/themes/limitless/assets/js/plugins/loaders/pace.min.js',
        'resources/themes/limitless/assets/js/plugins/loaders/blockui.min.js',
        'resources/themes/limitless/assets/js/plugins/forms/styling/uniform.min.js',
        'resources/themes/limitless/assets/js/plugins/visualization/d3/d3.min.js',
        'resources/themes/limitless/assets/js/plugins/visualization/d3/d3_tooltip.js',
        'resources/themes/limitless/assets/js/plugins/forms/styling/switchery.min.js',
        'resources/themes/limitless/assets/js/plugins/forms/styling/switch.min.js',
        'resources/themes/limitless/assets/js/plugins/forms/selects/bootstrap_multiselect.js',
        'resources/themes/limitless/assets/js/plugins/ui/moment/moment.min.js',
        'resources/themes/limitless/assets/js/plugins/ui/moment/moment-timezone.js',
        'resources/themes/limitless/assets/js/core/libraries/jasny_bootstra.pjasny_bootstrapmin.js',
        'resources/themes/limitless/assets/js/core/app.js',
        'resources/themes/limitless/assets/js/pages/login.js',
        'resources/themes/limitless/assets/js/locker.js',
        'resources/themes/limitless/assets/js/plugins/editors/summernote/summernote.min.js',
        'resources/themes/limitless/assets/js/plugins/ui/fullcalendar/fullcalendar.min.js',
        'resources/themes/limitless/assets/js/plugins/ui/fab.min.js',
        'resources/themes/limitless/assets/js/custom.js',
        'resources/themes/limitless/assets/js/client-custom.js'
    ], 'public_html/themes/limitless/js/async.js')
    .copy('resources/themes/limitless/assets/js/print', 'public_html/themes/limitless/js/print')
    .copy('resources/themes/limitless/assets/js/locker.js', 'public_html/themes/limitless/js/');
mix.combine([
    'resources/themes/limitless/assets/css/icons/fontawesome/styles.min.css',
    'resources/themes/limitless/assets/css/icons/icomoon/styles.css',
    'resources/themes/limitless/assets/css/core.css',
    'resources/themes/limitless/assets/css/colors.css',
    'resources/themes/limitless/assets/css/components.css',
    'resources/themes/limitless/assets/css/bootstrap.css',
    'resources/themes/limitless/assets/css/icons/fancybox/jquery.fancybox.css',
    'resources/themes/limitless/assets/css/jquery.datetimepicker.css',
    'resources/themes/limitless/assets/css/emoji.css',
    'resources/themes/limitless/assets/css/client-custom.css',
    'resources/themes/limitless/assets/css/extras/sweetalert2.css'
], 'public_html/themes/limitless/css/client-all.css')
    .copy('resources/themes/limitless/assets/css/icons', 'public_html/themes/limitless/css/icons')
    .copy('resources/themes/limitless/assets/images', 'public_html/themes/limitless/images')
    .copy('resources/themes/limitless/assets/fonts', 'public_html/themes/limitless/fonts');

mix.combine([
        'resources/themes/limitless/assets/js/core/libraries/jquery.min.js',
        'resources/themes/limitless/assets/js/plugins/media/fancybox.min.js',
        'resources/themes/limitless/assets/js/core/libraries/bootstrap.min.js',
        'resources/themes/limitless/assets/js/jquery.datetimepicker.js',
        'resources/themes/limitless/assets/js/emoji/config.js',
        'resources/themes/limitless/assets/js/emoji/util.js',
        'resources/themes/limitless/assets/js/emoji/jquery.emojiarea.js',
        'resources/themes/limitless/assets/js/emoji/emoji-picker.js',
        'resources/themes/limitless/assets/js/client-custom.js',
        'resources/themes/limitless/assets/js/plugins/notifications/sweetalert2.min.js',
        'resources/themes/limitless/assets/js/jquery.ui.widget.js',
        'resources/themes/limitless/assets/js/bootbox.min.js',
        'resources/themes/limitless/assets/js/plugins/ui/moment/moment.min.js',
        'resources/themes/limitless/assets/js/plugins/ui/moment/moment-timezone.js',
        'resources/themes/limitless/assets/js/plugins/ui/fullcalendar/fullcalendar.min.js',
       'resources/themes/limitless/assets/js/plugins/editors/summernote/summernote.min.js'
    ], 'public_html/themes/limitless/js/client-async.js')
    .copy('resources/themes/limitless/assets/js/print', 'public_html/themes/limitless/js/print')
    .copy('resources/themes/limitless/assets/js/locker.js', 'public_html/themes/limitless/js/');

    mix.copy('resources/themes/limitless/assets/css/register.css', 'public_html/themes/limitless/css/');
    mix.copy('resources/themes/limitless/assets/css/foundation.css', 'public_html/themes/limitless/css/');
    mix.copy('resources/themes/limitless/assets/css/foundation.min.css', 'public_html/themes/limitless/css/');
    mix.copy('resources/themes/limitless/assets/js/foundation.js', 'public_html/themes/limitless/js/');
    mix.copy('resources/themes/limitless/assets/js/foundation.min.js', 'public_html/themes/limitless/js/');
    mix.copy('resources/themes/limitless/assets/js/what-input.js', 'public_html/themes/limitless/js/');
    mix.copy('resources/themes/limitless/assets/js/jquery.js', 'public_html/themes/limitless/js/');
    mix.copy('resources/themes/limitless/assets/js/register_app.js', 'public_html/themes/limitless/js/');
    mix.copy('resources/themes/limitless/assets/css/appv2.css', 'public_html/themes/limitless/css/');
    mix.copy('resources/themes/limitless/assets/css/bootstrap_register.css', 'public_html/themes/limitless/css/');
    mix.copy('resources/themes/limitless/assets/js/app.js', 'public_html/themes/limitless/js/');
    mix.copy('resources/themes/limitless/assets/js/jquery_register.js', 'public_html/themes/limitless/js/');
    mix.copy('resources/themes/limitless/assets/js/bootstrap_register.js', 'public_html/themes/limitless/js/');

    mix.combine([
        'resources/themes/limitless/assets/css/icons/icomoon/styles.css',
        'resources/themes/limitless/assets/css/bootstrap_register.css',
        'resources/themes/limitless/assets/css/appv2.css',
    ], 'public_html/themes/limitless/css/register_all.css');

    mix.combine([
        'resources/themes/limitless/assets/js/core/libraries/jquery.min.js',
        'resources/themes/limitless/assets/js/plugins/forms/selects/select2.min.js',
        'resources/themes/limitless/assets/js/jquery.datetimepicker.js',
        'resources/themes/limitless/assets/js/plugins/media/fancybox.min.js',
        'resources/themes/limitless/assets/js/custom.js',
    ], 'public_html/themes/limitless/js/async_register.js')

// mix.version();