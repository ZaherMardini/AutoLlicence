<?php

use App\Models\TestAppointment;
use App\Models\TestType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tests', function (Blueprint $table) {
          $table->id();
          $table->foreignIdFor(TestType::class)->nullable(false);
          $table->foreignIdFor(TestAppointment::class)->constrained()->cascadeOnDelete()->nullable(false);
          $table->foreignIdFor(User::class, 'created_by_user_id')->nullable(false);
          $table->boolean('result');
          $table->string('notes');
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tests');
    }
};
