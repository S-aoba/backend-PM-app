<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
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
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function projects()
    {
        // 関係: User 多対多 Project
        return $this->belongsToMany(Project::class, 'project_users');
    }

    public function roles()
    {
        // 関係: User 多対多 Role
        return $this->belongsToMany(Role::class, 'project_users');
    }

    public function isAdmin(Project $project)
    {

        $user = $project->users->where('id', Auth::id())->first();

        if (is_null($user)) {
            return false;
        }

        return $user->pivot->role_id === 1;
    }
}
