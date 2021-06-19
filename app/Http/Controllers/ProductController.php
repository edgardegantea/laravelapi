<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {

            $products = Product::all();

            if ($products == null || empty($products)) {
                throw new Exception('No hay productos registrados en la base de datos', 404);
            } else {
                return response()->json([
                    'status' => 'ok',
                    'message' => 'Registros encontrados',
                    'data' => $products
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 'Error',
                'message' => 'Ha ocurrido un error'
                ]
            );
        }
    }


    public function show($id)
    {
        try {
            $product = Product::find($id);

            if ($product == null) {
                throw new Exception('Producto no encontrado', 404);
            }
            return response()->json([
                'status' => 'ok',
                'message' => 'Producto encontrado',
                'data' => $product
            ], 200);
        } catch (Exception $e) {
            return response()->json(['status' => 'Error',
                    'message' => 'Ha ocurrido un error'
                ]
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "name" => "required",
                "price" => "required",
            ]);

            if ($validator->fails()) {
                throw new Exception($validator->errors()->first(), 400);
            }

            $product = new Product();


            $product->name = $request->name;
            $product->price = $request->price;

            $result = $product->save();

            if (!$result) {
                throw new Exception('No es posible guardar el registro, por favor verifique la información introducida', 400);
            }

            $currentInsertData = Product::find($product->id_product);

            return response()->json(["status" => "ok", "message" => "Registro exitoso" . $request->name . "", "data" => $currentInsertData], 200);

        } catch (Exception $e) {

            return response()->json(["status" => "error", "message" => $e->getMessage()], $e->getCode());

        }
    }


}
