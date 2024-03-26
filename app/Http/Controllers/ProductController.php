<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
  public function testIndex()
  {
    return Product::all();
  }
  public function testStore(Request $request)
  {
    Product::create([$request->all()]);
  }
}
