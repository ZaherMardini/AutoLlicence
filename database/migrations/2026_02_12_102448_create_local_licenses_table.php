<?php

use App\Models\Application;
use App\Models\LicenceClass;
use App\Models\Person;
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
        Schema::create('local_licences', function (Blueprint $table) {
          $table->id();
          $table->foreignIdFor(LicenceClass::class)->nullable(false);
          $table->foreignIdFor(Person::class)->nullable(false)->constrained()->cascadeOnDelete();
          $table->foreignIdFor(Application::class)->nullable(false)->constrained()->cascadeOnDelete();
          $table->timestamps();

          $table->unique(['person_id','licence_class_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_licences');
    }
};
