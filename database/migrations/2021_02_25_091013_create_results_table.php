<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsTable extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('word_id');
            $table->longText('details');
            $table->longText('entries');
            $table->string('source');
            $table->timestamps();

            $table->foreign('word_id')
                ->references('id')
                ->on('words');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
}
