<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FaqsCategory;
use DataTables;
use Session;
use DB;

class CategoryFaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = new FaqsCategory;
        $category = FaqsCategory::where('category_id', '!=' , 0)->orderBy('category_id')->pluck('category_name', 'category_id');
        // dd($category);
        $sub_category = FaqsCategory::where('category_id', '!=' , 0)->orderBy('category_name')->pluck('category_name', 'category_id');
        $sub_sub_category = FaqsCategory::where('category_id', '!=' , 0)->orderBy('category_name')->pluck('category_name', 'category_id');
        return view('category.category_faqs', compact('faqs', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request['type_form']){
            
            if($request['type_form'] == 'form1'){
                $this->validate($request, [
                    'category_name' => 'required'
                ]);
            
                $input = $request->all();
                $cek = FaqsCategory::where('category_name', $input['category_name'])->first();
                if(empty($cek)){
                    $faqsCategory = new FaqsCategory;
                    $faqsCategory->category_id = FaqsCategory::max('category_id') + 1;
                    $faqsCategory->category_name = $input['category_name'];
                    $faqsCategory->save();
                } 
                Session::flash('flash_message', 'Category successfully added!');
            }
            if($request['type_form'] == 'form2'){
                
                $this->validate($request, [
                    'category_name' => 'required',
                    'sub_category_name' => 'required'
                ]);
            
                $input = $request->all();
                $cek = FaqsCategory::where('category_id', $input['category_name'])->first();
                if(!empty($cek)){
                    $faqsCategory = new FaqsCategory;
                    $faqsCategory->parent_category_id = $request['category_name'];
                    $faqsCategory->category_name = $cek->category_name;
                    $lastRow = FaqsCategory::where('sub_category_id', '!=' , 0)->orderBy('sub_category_id', 'ASC')->get();
                    $lastKey = $lastRow->keys()->last();
                    $faqsCategory->sub_category_id = !empty($lastRow[$lastKey]->sub_category_id) ? $lastRow[$lastKey]->sub_category_id + 1: 1;
                    $faqsCategory->sub_category_name = ucwords($input['sub_category_name']);/*$cek->category_name .' '. ucwords($input['sub_category_name']);*/
                    $faqsCategory->save();
                } 
                Session::flash('flash_message', 'Sub Category successfully added!');
            }

            if($request['type_form'] == 'form3'){
                $this->validate($request, [
                    'category_name' => 'required',
                    'sub_category_name' => 'required',
                    'sub_sub_category_name' => 'required'
                ]);
                $input = $request->all();
                $cekCategory = FaqsCategory::where('category_id', $input['category_name'])->first();
                $cekSubCategory = FaqsCategory::where('sub_category_id', $input['sub_category_name'])->first();
                if(!empty($cekCategory) AND !empty($cekSubCategory)){
                    $faqsCategory = new FaqsCategory;
                    $faqsCategory->category_name = $cekCategory->category_name;
                    $faqsCategory->parent_sub_category_id = $request['sub_category_name'];
                    $faqsCategory->sub_category_name = $cekSubCategory->sub_category_name;
                    $sublastRow = FaqsCategory::where('sub_sub_category_id', '!=' , 0)->orderBy('sub_sub_category_id', 'ASC')->get();
                    $lastKey = $sublastRow->keys()->last();
                    $faqsCategory->sub_sub_category_id = !empty($sublastRow[$lastKey]->sub_sub_category_id) ? $sublastRow[$lastKey]->sub_sub_category_id + 1: 1;
                    $faqsCategory->sub_sub_category_name = ucwords($input['sub_sub_category_name']);/*$cekSubCategory->sub_category_name .' '. ucwords($input['sub_sub_category_name']);*/
                    $faqsCategory->save();
                } 
                Session::flash('flash_message', 'Sub Sub Category successfully added!');
            }
            return redirect('category-faqs');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Process datatables ajax request.
     *
     * @return \Illuminate\Http\JsonResponse
     * parent_sub_category_id, parent_category_id, `category_id`
     */
    public function anyData()
    {
        $model = FaqsCategory::query();
        return DataTables::eloquent($model)
                ->filter(function ($query) {
                    $query->where('parent_sub_category_id', '!=' , 0);
                })
                ->order(function ($query) {
                    $query->orderBy('parent_sub_category_id', 'asc');
                    $query->orderBy('parent_category_id', 'asc');
                    $query->orderBy('category_id', 'asc');
                })
                ->toJson();
        // $query = DB::table('mobile_faqs_category')->orderBy('name', 'desc');
        // return DataTables::queryBuilder($query)->toJson();
    }
}
