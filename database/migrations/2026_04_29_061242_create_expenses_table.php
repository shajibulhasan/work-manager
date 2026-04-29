<?php
// database/migrations/xxxx_create_expenses_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('expense_category'); // Food, Transport, Medical etc.
            $table->string('spent_by'); // কে খরচ করছে
            $table->string('spent_at'); // কোথায় খরচ করেছে
            $table->string('paid_to')->nullable(); // কাকে টাকা দেওয়া হয়েছে
            $table->text('description')->nullable();
            $table->date('expense_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};