<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_faqs_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id');
            $table->string('category_name', 200);
            $table->integer('parent_category_id');
            $table->string('sub_category_id');
            $table->string('sub_category_name', 200);
            $table->integer('parent_sub_category_id');
            $table->integer('sub_sub_category_id');
			$table->string('sub_sub_category_name', 200);
            $table->longText('question_category');
            $table->longText('answer_category');
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
        Schema::dropIfExists('mobile_faqs_category');
    }
}
