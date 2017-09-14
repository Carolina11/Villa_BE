<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use Purifier;
use App\Type;
use App\Ingredient;
use App\Special;

class DBController extends Controller
{
  public function getLastSpecial()
  {
    $lastSpecial = Special::orderBy('id', 'DESC')->first();

    return Response::json(['lastSpecial' => $lastSpecial]);
  }

  public function getAllSpecials()
  {
    $allSpecials = Special::orderBy('name', 'ASC')->get();

    return Response::json(['allSpecials' => $allSpecials]);
  }

  public function getMarkedSpecials()
  {
    $markedSpecials = Special::orderBy('name', 'ASC')->first();

    return Response::json(['markedSpecials' => $markedSpecials]);
  }

  public function getTypes()
  {
    $types = Type::all();

    return Response::json(['types' => $types]);
  }

  public function getIngredients()
  {
    $ingredients = Ingredient::all();

    return Response::json(['ingredients' => $ingredients]);
  }

  public function storeItem(Request $request)
  {
    $rules = [
      'name' => 'required',
      'type' => 'required',
      'ingredient' => 'required',
      'description' => 'required',
      'price' => 'required'
    ];
    $validator = Validator::make(Purifier::clean($request->all()), $rules);

    if($validator->fails())
    {
      return Response::json(['error' => 'Please fill out all fields.']);
    }
    $name = $request->input('name');
    $type = $request->input('type');
    $ingredient = $request->input('ingredient');
    $description = $request->input('description');
    $pairings = $request->input('pairings');
    $price = $request->input('price');
    $onMenu = $request->input('onMenu');

    $special = new Special;
    $special->name = $name;
    $special->type = $type;
    $special->ingredient = $ingredient;
    $special->description = $description;
    $special->pairings = $pairings;
    $special->price = $price;
    $special->onMenu=0;
    $special->save();

    return Response::json(['special' => $special]);
  }


}
