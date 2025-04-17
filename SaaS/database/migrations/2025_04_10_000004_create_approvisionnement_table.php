<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('approvisionnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('depannage_id')->constrained()->onDelete('cascade');
            $table->string('statut')->default('À planifier');
            $table->date('date_validation')->nullable();
            $table->boolean('archived')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('approvisionnements');
    }
};
