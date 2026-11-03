<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
  use HasApiTokens, HasFactory, Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'name',
    'last_name',
    'phone',
    'email',
    'password',
    'whatsapp',
    'tlgram',
    'pais_id',
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
  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  public function pais(): BelongsTo
  {
    return $this->belongsTo(Pais::class, 'pais_id');
  }

  public function cursosPagos(): HasMany
  {
    return $this->hasMany(CursoPago::class, 'user_curso_id');
  }

  public function compras(): HasMany
  {
    return $this->hasMany(CursoUsuario::class, 'user_curso_id');
  }

  /** Compro o tiene asignado algun curso: estos usuarios nunca se eliminan */
  public function tieneCursos(): bool
  {
    return $this->cursosPagos()->exists() || $this->compras()->exists();
  }

  public function scopeSinCursos(Builder $query): Builder
  {
    return $query->doesntHave('cursosPagos')->doesntHave('compras');
  }

  public function isAdmin(): bool
  {
    return $this->roles === 'ROLE_ADMIN';
  }
}
