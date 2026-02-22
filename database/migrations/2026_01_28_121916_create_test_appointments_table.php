<?php

use App\Models\LocalLicence;
use App\Models\Person;
use App\Models\TestType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use PHPUnit\TextUI\TestDirectoryNotFoundException;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('test_appointments', function (Blueprint $table) {
          $table->id();
          $table->foreignIdFor(TestType::class)->nullable(false);
          $table->foreignIdFor(Person::class)->nullable(false);
          $table->foreignIdFor(LocalLicence::class)->nullable(false);
          $table->boolean('isLocked')->default(false);
          $table->date('appointment_date')->nullable(false);
          $table->decimal('paid_fees', 4,1)->nullable(false);
          $table->timestamps();

          // $table->unique(['test_type_id', 'person_id', 'local_licence_id', 'isLocked']); where isLocked = false
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_appointments');
    }
};
