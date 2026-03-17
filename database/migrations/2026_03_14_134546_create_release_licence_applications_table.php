<?php

use App\Models\Application;
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
      Schema::create('release_licence_applications', function (Blueprint $table) {
        $table->id();
        $table->foreignIdFor(Application::class)->nullable(false);
        $table->foreignIdFor(Licence::class)->nullable(false);
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('release_licence_applications');
    }
};
