<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('depannages', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('adresse');
            $table->string('contact_email');
            $table->string('statut');
            $table->text('description_probleme');
            $table->string('telephone');
            $table->string('type_materiel');
            $table->string('message_erreur')->nullable();
            $table->text('infos_supplementaires')->nullable();
            $table->date('date_depannage')->nullable();
            $table->string('provenance');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('depannages');
    }
};
