<?php

use App\Enums\ApplicationStatus;
use App\Models\LicenceClass;
use App\Models\Person;
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
        Schema::create('local_licenses', function (Blueprint $table) {
          $table->id();
          $table->foreignIdFor(LicenceClass::class)->nullable(false);
          $table->foreignIdFor(Person::class)->nullable(false);
          $table->foreignIdFor(User::class, 'created_by_user')->nullable(false);
          $table->decimal('fees',5,2);
          $table->string('application_status')->default(ApplicationStatus::New->value);
          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_licenses');
    }
};
