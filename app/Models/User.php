<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    // Add these methods to User model
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    // Calculate current balance
    public function getCurrentBalanceAttribute()
    {
        $totalDeposits = $this->deposits()->sum('amount');
        $totalExpenses = $this->expenses()->sum('amount');
        return $totalDeposits - $totalExpenses;
    }
}