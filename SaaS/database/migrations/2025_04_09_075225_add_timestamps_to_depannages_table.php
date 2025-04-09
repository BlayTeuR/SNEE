<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimestampsToDepannagesTable extends Migration
{
    public function up()
    {
        Schema::table('depannages', function (Blueprint $table) {
            $table->timestamps(); // Ajoute les colonnes created_at et updated_at
        });
    }

    public function down()
    {
        Schema::table('depannages', function (Blueprint $table) {
            $table->dropTimestamps(); // Supprime les colonnes created_at et updated_at
        });
    }
}
