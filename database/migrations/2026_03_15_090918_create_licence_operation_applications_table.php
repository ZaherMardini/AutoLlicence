<?php

use App\Models\Application;
use App\Models\ApplicationType;
use App\Models\Licence;
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
      Schema::create('licence_operation_applications', function (Blueprint $table) {
        $table->id();
        $table->foreignIdFor(Licence::class)->nullable(false);
        $table->foreignIdFor(Application::class)->constrained()->cascadeOnDelete()->nullable(false);
        $table->foreignIdFor(ApplicationType::class)->nullable(false);
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licence_operation_applications');
    }
};
