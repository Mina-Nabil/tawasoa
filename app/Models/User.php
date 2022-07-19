<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    const TYPE_ADMIN = 'Admin';
    const TYPE_ENTRY = 'Entry';
    const TYPES = [self::TYPE_ADMIN, self::TYPE_ENTRY];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'fullname',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    ////static queries
    public static function newUser(string $username, string $fullname, string $type, string $password): self|bool
    {
        $newUser = new self;

        $newUser->username = $username;
        $newUser->fullname = $fullname;
        $newUser->type = $type;
        $newUser->is_active = 1;
        $newUser->password = bcrypt($password);
        try {
            $newUser->save();
            return $newUser;
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public static function login($username, $password): bool
    {
        return Auth::attempt([
            "username"  =>  $username,
            "password"  =>  $password
        ], true);
    }

    ////model functions
    public function updateInfo(string $username, string $fullname, string $type, string $password = null): bool
    {
        $this->username = $username;
        $this->fullname = $fullname;
        $this->type = $type;
        if ($password)
            $this->password = bcrypt($password);
        try {
            return $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function setActive(bool $is_active): bool
    {
        $this->is_active = $is_active;
        try {
            return $this->save();
        } catch (Exception $e) {
            report($e);
            return false;
        }
    }

    public function isAdmin(): bool
    {
        return $this->type == self::TYPE_ADMIN;
    }

    public function isEntry(): bool
    {
        return $this->type == self::TYPE_ENTRY;
    }

    ////relations
        //////relations
        public function entries():HasMany
        {
            return $this->hasMany(Entry::class);
        }
}
