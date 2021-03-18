# MIGRATION
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider "OwenIt\Auditing\AuditingServiceProvider" --tag="migrations"
php artisan vendor:publish --provider="Spatie\ModelStatus\ModelStatusServiceProvider" --tag="migrations"

# LARACASTS
https://github.com/javoscript/laravel-macroable-models
https://github.com/rap2hpoutre/fast-excel
https://tailflow.github.io/laravel-orion-docs/
https://github.com/asantibanez/laravel-eloquent-state-machines

https://github.com/spatie/laravel-settings
https://php-flasher.github.io/
https://github.com/optimistdigital/nova-translations-loader
https://github.com/protonemedia/laravel-form-components
https://github.com/blade-ui-kit/blade-heroicons
https://github.com/lazychaser/laravel-nestedset

# SPATIE
https://github.com/spatie/laravel-searchable
https://github.com/spatie/macroable
https://github.com/spatie/laravel-model-states
https://github.com/spatie/laravel-collection-macros
https://github.com/spatie/laravel-schemaless-attributes
https://github.com/spatie/laravel-directory-cleanup
https://github.com/spatie/laravel-menu
https://github.com/spatie/valuestore
https://github.com/spatie/query-string
https://github.com/spatie/laravel-model-cleanup
https://github.com/spatie/eloquent-sortable
https://github.com/spatie/laravel-model-status
https://github.com/spatie/laravel-sluggable
https://github.com/spatie/laravel-cronless-schedule
https://github.com/spatie/form-backend-validation
https://github.com/spatie/laravel-or-abort
https://github.com/teepluss/laravel4-api

# CUSTOM FIELD
https://vanrossum.dev/16-how-to-make-wordpress-like-custom-fields-for-laravel-models
https://github.com/givebutter/laravel-custom-fields
https://www.laraship.com/docs/laraship/customize-laraship/custom-fields/
https://packalyst.com/packages/package/ironshark/laravel-extendable

------------------
https://demo.activeitzone.com/ecommerce/admin
https://cms.botble.com/admin

# SETTINGS
plugins


appearance
    widgets
    menus
    theme/all
    theme/options
    custom-css
    
settings
    general    
        + site_name
        + site_title
        + site_description
        + site_keywords
        + site_logo
        + site_favicon
        + site_url
        + author
        + admin_email
        + phone
        + date_format
        + time_format
        + timezone
        + active_language
        + users_can_register
        + default_role
        + admin_login_background
        + html_scripts_header
        + html_scripts_footer
        + html_scripts_after_body
        + html_scripts_before_body
    company
        + name
        + address
        + city
        + state
        + country
        + phone
    media
    social-login
        + GOOGLE_CLIENT_ID
        + GOOGLE_CLIENT_SECRET
    activation
        + maintenance_mode
        + email_verification
        + facebook_login
        + google_login
    email-templates
    email-config
        + email_protocol: sendmail, mailgun, smtp
        + smtp_host
        + smtp_port
        + smtp_email
        + smtp_username
        + smtp_email_charset
        + email_encryption
        + email_from_address
        + email_from_name
        + email_signature
        + test_email
    file_system
        + AWS_ACCESS_KEY_ID
    pusher
        + pusher_app_id
        + pusher_app_key
    google-analytics
    google-recaptcha
        + google_recaptcha
        + CAPTCHA_KEY


system
    + roles
    + users
    + info
    + audit-logs
    + backups
    + request-logs
    + server-status
