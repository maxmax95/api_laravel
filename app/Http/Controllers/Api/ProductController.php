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

        $products = $this->product; ///variavel recebe instancia de modelo product (molde)...ou seja...a variavel product sera instancia do modelo

        if($request->has('conditions')){ ///se o link de request possuir condições
            
            ///$expression = $request->get('conditions');
            ///dd($expression);
            
            $expression = explode(';', $request->get('conditions')); ///separe as condições em um array

            foreach($expression as $e){///para cada condição, coloca ele num "$e"
                $exp = explode(':', $e);///dentro de cada "$e" separa-se a chave e valor, colocando em um array de suas posições ($exp)
                $products = $products->where($exp[0], $exp[1], $exp[2]);///SQL com where, posição [0] é chave, [1] é valor
                ///dd($exp);
            }
        }
        
        if($request->has('fields')){///se o link de request possuir filtros
            $fields = $request->get('fields');///captura os filtros,
            $products = $products->selectRaw($fields);///seleciona so o que foi pedido no filtro...
            ///(raw...nao precisa estar separado por virgula e aspas simples)
        }
        
        return new ProductCollection($products->paginate(10));///apos passar nos if`s a variavel products é "encaixada" na cololection(forma) e paginada...  
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
