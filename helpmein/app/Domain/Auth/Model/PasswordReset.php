<?php
namespace App\Domain\Auth\Model;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = ['token', 'email'];

    public $timestamps = false;

    protected $primaryKey = 'email';

    public $incrementing = false;
}
