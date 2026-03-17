<?php

use App\Models\Application;
use App\Models\Licence;
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
      Schema::create('detained_licences', function (Blueprint $table) {
        $table->id();
        $table->foreignIdFor(Licence::class)->nullable(false);
        $table->foreignIdFor(Application::class, 'release_application_id')->nullable(true);
        $table->foreignIdFor(User::class, 'created_by_user_id')->nullable(false);
        $table->foreignIdFor(User::class, 'released_by_user_id')->nullable();
        $table->date('release_date')->nullable();
        $table->boolean('isReleased')->default(false);
        $table->decimal('fine', 5 ,2)->default(30)->nullable(false);
        $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detained_licences');
    }
};
