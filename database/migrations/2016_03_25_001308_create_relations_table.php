<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'relations',
            function (Blueprint $table) {
                $table->unsignedInteger('organisation_id')->nullable();
                $table->unsignedInteger('parent_id')->nullable();
                $table->unique(['organisation_id', 'parent_id']);
                $table->foreign('organisation_id', 'organisation_id_fk')
                    ->references('id')->on('organisations')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
                $table->foreign('parent_id', 'parent_id_fk')
                    ->references('id')->on('organisations')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('relations');
    }
}
