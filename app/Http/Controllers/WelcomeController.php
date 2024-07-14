<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [];
        if (false === Storage::disk('local')->exists('data.json')) {
            return view('welcome', compact('data'));
        }

        $data = Storage::disk('local')->get('data.json');
        $data = json_decode($data, true);
        return view('welcome', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $input = $request->except(['_token']);
        $input['id'] = uniqid();
        $input['timestamp'] = now();
        if (false === Storage::disk('local')->exists('data.json')) {
            Storage::disk('local')->put('data.json', json_encode([$input]));
            return response()->json($input);
        }

        $data = Storage::disk('local')->get('data.json');
        $data = json_decode($data, true);
        $output = [$input, ...$data];
        Storage::disk('local')->put('data.json', json_encode($output));

        return response()->json($input);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (false === Storage::disk('local')->exists('data.json')) {
            return response()->json(null);
        }

        $data = Storage::disk('local')->get('data.json');
        $data = json_decode($data, true);
        $key = array_search($id, array_column($data, 'id'));
        $input = array_replace($data[$key], $request->except(['_token']));
        $data[$key] = $input;
        Storage::disk('local')->put('data.json', json_encode($data));

        return response()->json($input);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
