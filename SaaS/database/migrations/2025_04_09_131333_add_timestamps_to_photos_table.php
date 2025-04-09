<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->timestamps();  // Cette méthode ajoute 'created_at' et 'updated_at'
        });
    }

    public function down()
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropTimestamps();  // Cette méthode supprime 'created_at' et 'updated_at'
        });
    }
};
