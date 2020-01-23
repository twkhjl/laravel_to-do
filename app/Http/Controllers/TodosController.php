<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use App\Helpers\AppHelper;

class TodosController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        // dd($todos);
        // $todos= Todo::get();
        $todos = Todo::OrderBy('created_at', 'desc')->get();

        if ($req->wantsJson()) {
            return view("todos/inc/cards", ['todos' => $todos]);
        }
        return view("todos/index", ['todos' => $todos]);
    }


//use bs4 modal,so we comment out the create function
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
            //     
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_todo=new Todo;
        $data=$request['form_data'];

        // app\Helpers\AppHelper.php
        append_data_from_serialize_array($new_todo,$data);

        $new_todo->save();

        return $new_todo;
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $req)
    {
        $todo = Todo::find($id);
        // $todo = Todo::where('id',$id)->first();

        if ($req->wantsJson()) {
            return $todo;
        }
        return view('todos/show', ['todo' => $todo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $todo=Todo::find($id);
        $data=$request['form_data'];

        // app\Helpers\AppHelper.php
        append_data_from_serialize_array($todo,$data);

        $todo->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // https://laravel.com/docs/5.8/eloquent#deleting-models
        $todo=Todo::find($id);
        $todo->delete();
        return back();

    }

}
