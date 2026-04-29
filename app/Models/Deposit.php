<?php
// app/Models/Deposit.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'depositor_name',
        'received_by',
        'purpose',
        'deposit_date'
    ];

    protected $casts = [
        'deposit_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}