<?php

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
        Schema::create('table_templates', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('colCount')->nullable();
            $table->string('rowCount')->nullable();
            $table->json('colData')->nullable();
            $table->json('rowData')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_templates');
    }
};
