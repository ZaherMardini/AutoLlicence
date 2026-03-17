<?php

use App\Enums\LicenceStatus;
use App\Models\Driver;
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
      Schema::create('licences', function (Blueprint $table) {
        $table->id();
        $table->string('licence_number');
        $table->foreignIdFor(LicenceClass::class)->nullable(false);
        $table->foreignIdFor(Driver::class)->nullable(false);
        $table->foreignIdFor(Person::class)->index()->nullable(false);
        $table->date('issue_date')->nullable(false);
        $table->date('expiry_date')->nullable(false);
        $table->string('notes')->nullable();
        $table->string('issue_reason')->nullable(false);
        $table->string('status')->default(LicenceStatus::new->value);
        $table->string('image')->nullable();
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::dropIfExists('licences');
    }
};
