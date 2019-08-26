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
            $table->unsigned('user_id');
            $table->text('websitelink');
            $table->unsigned('city');
            $table->unsigned('views');
            $table->unsigned('favCount');
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
