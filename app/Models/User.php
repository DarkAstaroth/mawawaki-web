<?php

namespace App\Models;

use App\Models\Gestion\Actividad;
use App\Models\Gestion\Asistencia;
use App\Models\Gestion\Cliente;
use App\Models\Notificacion;
use App\Models\Gestion\Documentacion;
use App\Models\Gestion\Personal;
use App\Notifications\PasswordReset;
use App\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use LaravelPermissionToVueJS;
    use HasRoles;
    use HasUuids;
    use SoftDeletes;


    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;



    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'persona_id',
        'name',
        'email',
        'nombres',
        'paterno',
        'materno',
        'solicitud',
        'tipo_solicitud',
        'password',
        'gauth_id',
        'gauth_type',
        'profile_photo_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id');
    }

    public function documentos()
    {
        return $this->hasMany(Documentacion::class);
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'UsuarioID');
    }

    public function personal()
    {
        return $this->hasOne(Personal::class, 'UsuarioID');
    }


    public function notificaciones(): HasMany
    {
        return $this->hasMany(Notificacion::class, 'usuario_id', 'id');
    }

    public function actividades(): HasMany
    {
        return $this->hasMany(Actividad::class, 'id_usuario');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail($this->verificationUrl($this)));
    }

    public function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'UsuarioID', 'id');
    }
}
