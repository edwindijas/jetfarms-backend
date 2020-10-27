<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('crop')->references('crops', 'id');
            $table->integer('price')->comment("Price in multiples of 100");
            $table->integer('units')->comment("Units available");
            $table->text('description')->comment("product descriptions");
            $table->timestamp('opening_date');
            $table->timestamp('closing_date');
            $table->string('state_message')->nullable();
            $table->enum('state', ['open', 'closed', 'suspended'])->default('open')->comment("Open | closed | suspended");
            $table->boolean('preorder')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('investments');
    }
}
