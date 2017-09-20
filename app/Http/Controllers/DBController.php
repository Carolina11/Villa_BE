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

  public function getLastSpecial()
  {
    $lastSpecial = Special::leftJoin('types', 'specials.type', '=', 'types.id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.id')
    ->select('specials.id', 'specials.name AS name', 'specials.price','specials.description', 'specials.quantity', 'specials.pairings', 'types.name AS type', 'ingredients.name AS ingredient', 'menus.name AS menu')
    ->orderBy('specials.id', 'DESC')->first();

    return Response::json(['lastSpecial' => $lastSpecial]);
  }

  public function getAllSpecials()
  {
    $allSpecials = Special::leftJoin('types', 'specials.type', '=', 'types.id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.id')
    ->select('specials.id', 'specials.name AS name', 'specials.price','specials.description', 'specials.quantity', 'specials.pairings', 'types.name AS type', 'ingredients.name AS ingredient', 'menus.name AS menu')
    ->orderBy('specials.name', 'ASC')->get();

    return Response::json(['allSpecials' => $allSpecials]);
  }

  public function getMenuSpecials(Request $request)
  {
    $onMenuID = $request->input('onMenuID');

    $menuSpecials = Special::leftJoin('types', 'specials.type', '=', 'types.id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.id')
    ->where('specials.onMenu', '=', $onMenuID)
    ->select('specials.id', 'specials.name AS name', 'specials.price','specials.description', 'specials.quantity', 'specials.pairings', 'types.name AS type', 'ingredients.name AS ingredient', 'menus.name AS menu')
    ->orderBy('specials.quantity', 'ASC')->get();

    return Response::json(['menuSpecials' => $menuSpecials]);
  }



  public function searchSpecials(Request $request)
  {
    $name = $request->input('name');
    $type = $request->input('type');
    $ingredient = $request->input('ingredient');
    $description = $request->input('description');

    $searchSpecials = Special::leftJoin('types', 'specials.type', '=', 'types.id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.id');

    if($name != NULL) {
      $searchSpecials->where('specials.name', 'LIKE', '%'.$name.'%');
    }
    if($type != NULL) {
      $searchSpecials->where('specials.type', '=', $type);
    }
    if($ingredient != NULL) {
      $searchSpecials->where('specials.ingredient', '=', $ingredient);
    }
    if($description != NULL) {
      $searchSpecials->where('specials.description', 'LIKE', '%'.$description.'%');
    }
    $searchSpecials = $searchSpecials->select('specials.id', 'specials.name AS name', 'specials.price','specials.description', 'specials.quantity', 'specials.pairings', 'types.name AS type', 'ingredients.name AS ingredient', 'menus.name AS menu')
    ->orderby('specials.name', 'ASC')
    ->get();

    return Response::json(['searchSpecials' => $searchSpecials]);
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
