<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Ramsey\Uuid\Guid\Guid;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'email',
        'username',
        'password',
        'encrypted_token',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
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

    // Add this method for JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    // Add this method for JWT
    public function getJWTCustomClaims()
    {
        return [];
    }

     // Jika menggunakan UUID sebagai primary key, kamu harus memberi tahu Eloquent
     public $incrementing = false; // Matikan auto-increment
     protected $keyType = 'string'; // Set key type menjadi string (UUID adalah string)
 
     // Menambahkan event 'creating' untuk menghasilkan UUID sebelum menyimpan data
     protected static function booted()
     {
         static::creating(function ($user) {
             // Menetapkan UUID jika belum ada
             if (!$user->id) {
                 $user->id = (string) Guid::uuid4(); // Menghasilkan UUID v4
             }
         });
     }
}
