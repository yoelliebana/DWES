<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;

class CatalogController extends Controller
{

	public function getHome()
	{
		return redirect()->action('CatalogController@getIndex');
	}

   public function getIndex()
{
    $movies = \App\Models\Movie::all();
    return view('catalog.index')->with('arrayPeliculas', $movies);
}

    public function getShow($id)
    {
		$movies = \App\Models\Movie::findOrFail($id);
		return view('catalog.show')->with('movies', $movies);
    }

    public function getCreate()
    {
        return view('catalog.create');
    }

    public function postCreate(Request $request)
    {
        $movie = new Movie();
        $movie->title = $request->input('title');
        $movie->year = $request->input('year');
        $movie->director = $request->input('director');
        $movie->poster = $request->input('poster');
        $movie->synopsis = $request->input('synopsis');
        $movie->rented = false;
        $movie->save();

        return redirect()->action([CatalogController::class, 'getShow'], ['id' => $movie->id]);
    }

    public function getEdit($id)
    {
		$movies = \App\Models\Movie::findOrFail($id);
		return view('catalog.edit')->with('movies', $movies);
        //return view('catalog.edit', array('id'=>$id));
    }

    public function putUpdate(Request $request, $id)
{
    $movie = \App\Models\Movie::findOrFail($id);

    $movie->title = $request->input('title');
    $movie->year = $request->input('year');
    $movie->director = $request->input('director');
    $movie->poster = $request->input('poster');
    $movie->synopsis = $request->input('synopsis');

    $movie->save();

    return redirect()->action([CatalogController::class, 'getShow'], ['id' => $id]);
}
}
