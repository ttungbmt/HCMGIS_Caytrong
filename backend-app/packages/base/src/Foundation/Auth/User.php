<?php
namespace Larabase\Foundation\Auth;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use JoelButcher\Socialstream\HasConnectedAccounts;
use Kodeine\Metable\Metable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\ModelStatus\HasStatuses;
use Spatie\Permission\Traits\HasRoles;
use \Yajra\Auditable\AuditableTrait as Blameable;

class User extends \Illuminate\Foundation\Auth\User
{
    use HasRoles, Metable, LogsActivity, Blameable, HasStatuses;
    use HasFactory, HasApiTokens, Notifiable, TwoFactorAuthenticatable, HasConnectedAccounts;
    use HasProfilePhoto {
        getProfilePhotoUrlAttribute as getPhotoUrl;
    }

    protected $metaTable = 'users_meta';

    public $hideMeta = true;

    protected static $logName = 'user';

    protected static $logAttributes = ['name', 'email', 'remember_token'];

    /**
     * Determines if the User is a Super admin
     * @return null
     */
    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (filter_var($this->profile_photo_path, FILTER_VALIDATE_URL)) {
            return $this->profile_photo_path;
        }

        return $this->getPhotoUrl();
    }
}