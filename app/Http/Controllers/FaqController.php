<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faqs;
use App\FaqsCategory;
use DataTables;
use Session;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('datatables.faq');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $faqs = new Faqs;
        $items = FaqsCategory::where('category_id', '!=' , 0)->orderBy('category_name')->pluck('category_name', 'category_id');
        return view('datatables.faq_create', compact('faqs', 'items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $faqs = new Faqs;
        $this->validate($request, [
            'category_name' => 'required',
            'sub_category_name' => 'required',
            'sub_sub_category_name' => 'required',
            'question_category' => 'required',
            'answer_category' => 'required'
        ]);
    
        $input = $request->all();
        $faqs->category_id = $input['category_name'];
        $faqs->category_name = !empty(FaqsCategory::where('category_id', $faqs->category_id)->firstOrFail()->category_name) ? FaqsCategory::where('category_id', $faqs->category_id)->firstOrFail()->category_name : '';
        $faqs->sub_category_id = $input['sub_category_name'];
        $faqs->sub_category_name = !empty(FaqsCategory::where('sub_category_id', $faqs->sub_category_id)->firstOrFail()->sub_category_name) ? FaqsCategory::where('sub_category_id', $faqs->sub_category_id)->firstOrFail()->sub_category_name : '';
        $faqs->sub_sub_category_id = $input['sub_sub_category_name'];
        $faqs->sub_sub_category_name = !empty(FaqsCategory::where('sub_sub_category_id', $faqs->sub_sub_category_id)->firstOrFail()->sub_sub_category_name) ? FaqsCategory::where('sub_sub_category_id', $faqs->sub_sub_category_id)->firstOrFail()->sub_sub_category_name : '';
        $faqs->question_category = $input['question_category'];
        $faqs->answer_category = $input['answer_category'];
        $faqs->updated_at = date("Y-m-d H:i:s");
        $faqs->save();
    
        Session::flash('flash_message', 'Faqs successfully added!');
        return redirect('faq');
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
        $faqs = Faqs::findOrFail($id);
        $items = FaqsCategory::where('category_id', '!=' , 0)->orderBy('category_name')->pluck('category_name', 'category_id');
        return view('datatables.faq_edit', compact('faqs', 'items'));
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
        $faqs = Faqs::findOrFail($id);
        $this->validate($request, [
            'category_name' => 'required',
            'sub_category_name' => 'required',
            'sub_sub_category_name' => 'required',
            'question_category' => 'required',
            'answer_category' => 'required'
        ]);
    
        $input = $request->all();
        $faqs->category_id = $input['category_name'];
        $faqs->category_name = !empty(FaqsCategory::where('category_id', $faqs->category_id)->firstOrFail()->category_name) ? FaqsCategory::where('category_id', $faqs->category_id)->firstOrFail()->category_name : '';
        $faqs->sub_category_id = $input['sub_category_name'];
        $faqs->sub_category_name = !empty(FaqsCategory::where('sub_category_id', $faqs->sub_category_id)->firstOrFail()->sub_category_name) ? FaqsCategory::where('sub_category_id', $faqs->sub_category_id)->firstOrFail()->sub_category_name : '';
        $faqs->sub_sub_category_id = $input['sub_sub_category_name'];
        $faqs->sub_sub_category_name = !empty(FaqsCategory::where('sub_sub_category_id', $faqs->sub_sub_category_id)->firstOrFail()->sub_sub_category_name) ? FaqsCategory::where('sub_sub_category_id', $faqs->sub_sub_category_id)->firstOrFail()->sub_sub_category_name : '';
        $faqs->question_category = $input['question_category'];
        $faqs->answer_category = $input['answer_category'];
        $faqs->updated_at = date("Y-m-d H:i:s");
        $faqs->save();
    
        Session::flash('flash_message', 'Faqs successfully updated!');
        return redirect()->back();
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
     */
    public function anyData()
    {
        $model = Faqs::query();
        return DataTables::eloquent($model)
            //->addColumn('action', "<a href='{{ url('page') }}'>Edit</a> - <a href='#'>Delete</a>")
            ->addColumn('action', function ($model) {
                return '<a href="faq/'.$model->id.'/edit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i> &nbsp;&nbsp;Edit&nbsp;&nbsp; </a>
                <a href="faq/'.$model->id.'/edit" class="btn btn-primary btn-sm"><i class="glyphicon glyphicon-edit"></i> Delete</a>';
            })
            ->rawColumns(['link', 'action'])
            ->toJson();
    }

    public function getCategory($id, $type = '') {
        if(!empty($id) AND !empty($type)){
            $items = [];
            if($type == 'category')
                $items = FaqsCategory::where('parent_category_id', $id)->orderBy('sub_category_name')->pluck('sub_category_name', 'sub_category_id');
            if($type == 'sub_category')
                $items = FaqsCategory::where('parent_sub_category_id', $id)->orderBy('sub_sub_category_name')->pluck('sub_sub_category_name', 'sub_sub_category_id');
            
            if(!empty($items))
                return $items->toJson();
            else
                return json_encode($items);
        }
        
    }
}
