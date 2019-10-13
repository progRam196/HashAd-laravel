<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('ad_text');
            $table->longText('show_text');	
            $table->text('ad_image_1');	
            $table->text('ad_image_2');	
            $table->text('ad_image_3');	
            $table->text('ad_image_4');
            $table->enum('ad_status',['A','B','T'])->default('A');
            $table->text('coordinates');
            $table->unsignedBigInteger('user_id');
            $table->text('websitelink');
            $table->string('city',50);
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('favCount')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ads');
    }
}
