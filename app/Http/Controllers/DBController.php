<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Response;
use Purifier;
use App\Type;
use App\Ingredient;
use App\Special;
use App\Menu;

class DBController extends Controller
{
  public function getLastSpecial()
  {
    $lastSpecial = Special::leftJoin('types', 'specials.type', '=', 'types.type_id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.ing_id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.menu_id')
    ->orderBy('id', 'DESC')->first();

    return Response::json(['lastSpecial' => $lastSpecial]);
  }

  public function getAllSpecials()
  {
    $allSpecials = Special::leftJoin('types', 'specials.type', '=', 'types.type_id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.ing_id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.menu_id')
    ->orderBy('dish', 'ASC')->get();

    return Response::json(['allSpecials' => $allSpecials]);
  }

  public function getMarkedSpecials()
  {
    $markedSpecials = Special::leftJoin('types', 'specials.type', '=', 'types.type_id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.ing_id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.menu_id')
    ->orderBy('dish', 'ASC')->first();

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
  public function getMenus()
  {
    $menus = Menu::all();

    return Response::json(['menus' => $menus]);
  }
  public function storeItem(Request $request)
  {
    $rules = [
      'dish' => 'required',
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
    $dish = $request->input('dish');
    $type = $request->input('type');
    $ingredient = $request->input('ingredient');
    $description = $request->input('description');
    $pairings = $request->input('pairings');
    $price = $request->input('price');
    $onMenu = $request->input('onMenu');

    $special = new Special;
    $special->dish = $dish;
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
