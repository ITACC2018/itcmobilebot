<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faqs extends Model
{
    //
    protected $table = 'mobile_faqs';
    protected $fillable = ['category_name', 'sub_category_name', 'sub_sub_category_name', 'question_category', 'answer_category'];
}
