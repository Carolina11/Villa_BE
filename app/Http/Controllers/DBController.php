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
use App\Seasonalbeer;

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
  public function getSeasonalBeers()
  {
    $seasonalBeers = Seasonalbeer::all();

    return Response::json(['seasonalBeers' => $seasonalBeers]);
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
    $type = $request->input('type');

    $menuSpecials = Special::join('types', 'specials.type', '=', 'types.id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.id')
    ->select('specials.id', 'specials.name AS name', 'specials.price','specials.description', 'specials.quantity', 'specials.pairings', 'specials.onMenu', 'types.name AS type', 'ingredients.name AS ingredient', 'menus.name AS menu')
    ->where('specials.onMenu', '=', $onMenuID);

    if($type != NULL) {
      $menuSpecials->where('specials.type', '=', $type);
    }

    $menuSpecials = $menuSpecials->select('specials.id', 'specials.name AS name', 'specials.price','specials.description', 'specials.quantity', 'specials.pairings', 'types.name AS type', 'ingredients.name AS ingredient', 'menus.name AS menu', 'menus.id AS menuID', 'specials.onMenu')
    ->orderBy('specials.quantity', 'DESC')->get();

    return Response::json(['menuSpecials' => $menuSpecials]);
  }

  public function searchSpecials(Request $request)
  {
    $id = $request->input('id');
    $name = $request->input('name');
    $type = $request->input('type');
    $ingredient = $request->input('ingredient');
    $description = $request->input('description');

    $searchSpecials = Special::leftJoin('types', 'specials.type', '=', 'types.id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.id');

    if ($id != NULL) {
      $searchSpecials->where('specials.id', '=', $id);
    }
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
    $searchSpecials = $searchSpecials->select('specials.id', 'specials.name AS name', 'specials.price','specials.description', 'specials.quantity', 'specials.pairings', 'specials.onMenu', 'types.name AS type', 'ingredients.name AS ingredient', 'menus.name AS menu', 'types.id AS typeID', 'ingredients.id AS ingredientID', 'menus.id AS menuID')
    ->orderby('specials.name', 'ASC')
    ->get();

    return Response::json(['searchSpecials' => $searchSpecials]);
}

  public function storeItem(Request $request)
  {
    $rules = [
      'name' => 'required',
      'type' => 'required',
      'price' => 'required'
    ];
    $validator = Validator::make(Purifier::clean($request->all()), $rules);

    if($validator->fails())
    {
      return Response::json(['error' => 'Please be sure to fill out "name", "price", and "type of dish".']);
    }
    else {
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

    return Response::json(['special' => $special, 'onMenu' => 0]);
    }
  }

  public function updateItem(Request $request)
  {
    $id = $request->input('id');
    $name = $request->input('name');
    $type = $request->input('type');
    $ingredient = $request->input('ingredient');
    $description = $request->input('description');
    $pairings = $request->input('pairings');
    $price = $request->input('price');
    $onMenu  = $request->input('menuID');

    $item = Special::find($id);

    if($type == NULL) {
      $type = $item->type;
    }
    if($ingredient == NULL) {
      $ingredient = $item->ingredientID;
    }
    if($onMenu == NULL) {
      $onMenu = $item->onMenu;
    }
    if($description == NULL) {
      $description = '';
    }
    if($pairings == NULL) {
      $pairings = '';
    }

    $updateItem =  Special::where('id', '=', $id)->update(['name' => $name, 'type' => $type, 'ingredient' => $ingredient, 'description' => $description, 'pairings' => $pairings, 'price' => $price, 'onMenu' => $onMenu]);

    $searchSpecials = Special::leftJoin('types', 'specials.type', '=', 'types.id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.id')
    ->select('specials.id', 'specials.name AS name', 'specials.price','specials.description', 'specials.quantity', 'specials.pairings', 'types.name AS type', 'ingredients.name AS ingredient', 'menus.name AS menu', 'specials.onMenu')
    ->where('specials.id', '=', $id)
    ->orderBy('specials.name', 'ASC')->get();

    return Response::json(['searchSpecials' => $searchSpecials]);
  }

  public function updateSeasonalBeer(Request $request)
  {
    $id = $request->input('id');
    $beerName = $request->input('beerName');

    $updateSeasonalBeer = Seasonalbeer::where('id', '=', $id)->update(['beerName' => $beerName]);

    return Response::json(['beerNameUpdated' => $beerName]);
  }


  public function toggleMenu(Request $request)
  {
    $id = $request->input('id');
    $onMenu = $request->input('onMenu');

    $toggleMenu =  Special::where('id', '=', $id)->update(['onMenu' => $onMenu]);

    $searchSpecials = Special::leftJoin('types', 'specials.type', '=', 'types.id')
    ->leftJoin('ingredients', 'specials.ingredient', '=', 'ingredients.id')
    ->leftJoin('menus', 'specials.onMenu', '=', 'menus.id')
    ->select('specials.id', 'specials.name AS name', 'specials.price','specials.description', 'specials.quantity', 'specials.pairings', 'types.name AS type', 'ingredients.name AS ingredient', 'menus.name AS menu', 'specials.onMenu')
    ->where('specials.id', '=', $id)
    ->orderBy('specials.name', 'ASC')->get();

    return Response::json(['searchSpecials' => $searchSpecials, 'success' => 'Updated!']);
   }


}
