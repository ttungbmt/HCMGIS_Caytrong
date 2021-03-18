composer require laravel/jetstream
php artisan jetstream:install livewire
composer require joelbutcher/socialstream
php artisan socialstream:install
php artisan nova:install
yarn
yarn dev
php artisan migrate
php artisan nova:user


php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"


PERMISSIONS
----------
users
users.create
users.update
users.view
users.delete
users.change-password
roles
roles.create
roles.update
roles.view
roles.delete
permissions
permissions.create
permissions.update
permissions.view
permissions.delete
import
activity-log
settings
command
filemanager
logs
