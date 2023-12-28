<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{
private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;

    }

    public function index(Request $request){ ///recebe dados de request, então foram injetados

        ///$products = $this->product->all(); ///retorne tudo

        ///return response()->json($products);

        $products = $this->product; ///variavel recebe instancia de modelo product

        if($request->has('conditions')){ ///se o link de request possuir condições
            $expression = explode(';', $request->get('conditions')); ///separe as condições em um array

            foreach($expression as $e){///para cada condição, coloca ele num "$e"
                $exp = explode('=', $e);///dentro de cada "$e" separa-se a chave e valor, colocando em um array de suas posições ($exp)
                $products = $products->where($exp[0], $exp[1]);///SQL com where, posição [0] é chave, [1] é valor
            }
        }
        
        if($request->has('fields')){///se o link de request possuir filtros
            $fields = $request->get('fields');
            $products = $products->selectRaw($fields);
        }
        
        return new ProductCollection($products->paginate(10));  
    }
     public function show($id){

        $product = $this->product->find($id);
        ///return response()->json($product);
        return new ProductResource($product);
     }

     public function update(Request $request){

        $data = $request->all();
        $product = $this->product->findOrFail($data['id']);

        $product->update($data);

        return response()->json($product);
    }
    
    public function delete($id){

        $product = $this->product->find($id);

        $product->delete();

        return response()->json(['data' => ['msg' => 'The product was removed!']]);
    }


    public function save(Request $request){

        $data = $request->all();
        $product = $this->product->create($data);

        return response()->json($product);
    }
}
