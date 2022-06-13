<?php

namespace App\Http\Controllers;

use App\Http\Requests\FieldRequest;
use App\Http\Requests\FieldsRequest;
use App\Http\Requests\SubscriberRequest;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class SubscribersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subscribers = Subscriber::all();

        return response()->json($subscribers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubscriberRequest $request)
    {
        $subscriber = Subscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'state' => $request->state,
        ]);

        return response()->json($subscriber, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subscriber = Subscriber::findOrFail($id);

        return response()->json($subscriber);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SubscriberRequest $request, $id)
    {
        $subscriber = Subscriber::findOrFail($id);

        $subscriber->update([
            'email' => $request->email,
            'name' => $request->name,
            'state' => $request->state,
        ]);

        return response()->json($subscriber, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Subscriber::destroy($id);

        return response()->json(null, 204);
    }

    public function listFields($id)
    {
        $subscriber = Subscriber::findOrFail($id);

        return response()->json($subscriber->fields);
    }

    public function addField(FieldRequest $request, $id)
    {
        $subscriber = Subscriber::findOrFail($id);

        $field = $subscriber->fields()->create([
            'title' => $request->title,
            'type' => $request->type,
        ]);

        return response()->json($field->toArray(), 201);
    }

    public function setFields(FieldsRequest $request, $id)
    {
        $subscriber = Subscriber::findOrFail($id);

        $subscriber->fields()->delete();
        $subscriber->fields()->createMany($request->input());

        return response()->json($subscriber->fields);
    }
}
