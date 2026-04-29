<?php
// app/Models/Expense.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'expense_category',
        'spent_by',
        'spent_at',
        'paid_to',
        'description',
        'expense_date'
    ];

    protected $casts = [
        'expense_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Add this method to Expense model
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}