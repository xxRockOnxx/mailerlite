<?php

namespace App\Http\Controllers;

use App\Models\Field;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class FieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($subscriberId)
    {
        $subscriber = Subscriber::findOrFail($subscriberId);

        return response()->json($subscriber->fields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $subscriberId)
    {
        $subscriber = Subscriber::findOrFail($subscriberId);

        $field = $subscriber->fields()->create([
            'title' => $request->title,
            'type' => $request->type,
        ]);

        return response()->json($field->toArray(), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $field = Field::findOrFail($id);

        return response()->json($field);
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
        $field = Field::findOrFail($id);

        $field->update([
            'title' => $request->title,
            'type' => $request->type,
        ]);

        return response()->json($field->toArray());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Field::destroy($id);

        return response()->json(null, 204);
    }
}
