<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return Movie::all();
    }
    public function store(Request $request)
    {
        return response()
            ->json(
                Movie::create($request->all()),
                201
            );
    } 
    public function show(int $id)
    {
        $serie = Movie::find($id);
        if (is_null($serie)) {
            return response()->json('', 204);
        }
        return response()->json($serie);
    }
    public function update(int $id, Request $request)
    {
        $movie = Movie::find($id);
        if (is_null($movie)) {
            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);
        }
        $movie->fill($request->all());
        $movie->save();
        return $movie;
    }
    public function destroy(int $id)
    {
        $qtdRecursosRemovidos = Movie::destroy($id);
        if ($qtdRecursosRemovidos === 0) {
            return response()->json([
                'erro' => 'Recurso não encontrado'
            ], 404);
        }
        return response()->json('', 204);
    }
}