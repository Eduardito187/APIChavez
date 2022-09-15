<?php
use App\Models\Producto;
use App\Models\ProductoLote;
use GraphQL\Type\Definition\Type;
$ProductoMutations=[
    'addProducto'=>[
        'type'=>$boolType,
        'args'=>[
            'Factura'=>Type::nonNull(Type::string()),
            'Nombre'=>Type::nonNull(Type::string()),
            'Modelo'=>Type::nonNull(Type::string()),
            'Proveedor'=>Type::nonNull(Type::int()),
            'Costo'=>Type::nonNull(Type::int()),
            'Cantidad'=>Type::nonNull(Type::int()),
            'Lote'=>Type::nonNull(Type::int())
        ],
        'resolve'=>function($root, $args){
            $total=Producto::distinct()->count('ID');
            $total+=1;
            $Producto=new Producto([
                'ID'=>$total,
                'Factura'=>$args["Factura"],
                'Nombre'=>$args["Nombre"],
                'Modelo'=>$args["Modelo"],
                'Proveedor'=>$args["Proveedor"],
                'Costo'=>$args["Costo"],
                'Cantidad'=>$args["Cantidad"]
            ]);
            $x=$Producto->save();
            $total1=ProductoLote::distinct()->count('ID');
            $total1+=1;
            $ProductoLote=new ProductoLote([
                'ID'=>$total1,
                'Lote'=>$args["Lote"],
                'Producto'=>$total,
                'FechaSalida'=>null,
                'Responsable'=>null,
                'Sucursal'=>null,
                'Cantidad'=>null
            ]);
            $x=$ProductoLote->save();
            return array("Respuesta"=>true);
        }
    ],
    'editProducto' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Factura'=>Type::nonNull(Type::string()),
            'Nombre'=>Type::nonNull(Type::string()),
            'Modelo'=>Type::nonNull(Type::string()),
            'Proveedor'=>Type::int(),
            'Costo'=>Type::int(),
            'Cantidad'=>Type::int()
        ],
        'resolve' => function($root, $args) {
            $Producto=Producto::find($args['ID']);
            $v=false;
            if ($Producto!=null) {
                Producto::where('ID', $args['ID'])->update([
                    'Factura'=>isset($args["Factura"])?$args["Factura"]:$Producto->Factura,
                    'Nombre'=>isset($args["Nombre"])?$args["Nombre"]:$Producto->Nombre,
                    'Modelo'=>isset($args["Modelo"])?$args["Modelo"]:$Producto->Modelo,
                    'Proveedor'=>isset($args["Proveedor"])?$args["Proveedor"]:$Producto->Proveedor,
                    'Costo'=>isset($args["Costo"])?$args["Costo"]:$Producto->Costo,
                    'Cantidad'=>isset($args["Cantidad"])?$args["Cantidad"]:$Producto->Cantidad
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delProducto' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Producto = Producto::find($args['ID']);
            $v=false;
            if ($Producto!=null && true==false) {
                Producto::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'SalidaProducto'=>[
        'type'=>$boolType,
        'args'=>[
            'Lote'=>Type::nonNull(Type::int()),
            'Producto'=>Type::nonNull(Type::int()),
            'Responsable'=>Type::nonNull(Type::int()),
            'Sucursal'=>Type::nonNull(Type::int()),
            'Cantidad'=>Type::nonNull(Type::int())
        ],
        'resolve'=>function($root, $args){
            $Producto=Producto::find($args['Producto']);
            $v=false;
            if ($Producto!=null) {
                if ($Producto->Cantidad>=$args["Cantidad"]) {

                    $data_P_L = ProductoLote::where('Lote', $args["Lote"])->where('Producto', $args["Producto"])->get()->toArray();
                    $z=false;
                    Producto::where('ID', $args['Producto'])->update([
                        'Cantidad'=>$Producto->Cantidad-=$args["Cantidad"]
                    ]);

                    foreach ($data_P_L as $valor) {
                        if ($valor["Responsable"]==null && $valor["Sucursal"]==null && $valor["Cantidad"]==null && $z==false) {
                            $z=true;
                            ProductoLote::where('ID', $valor["ID"])->update([
                                'FechaSalida'=>date("Y-m-d"),
                                'Cantidad'=>$args["Cantidad"],
                                'Responsable'=>$args["Responsable"],
                                'Sucursal'=>$args["Sucursal"]
                            ]);
                        }
                          
                    }
                    if ($z==false) {
                        $ProductoLote=new ProductoLote([
                            'ID'=>NULL,
                            'Lote'=>$args["Lote"],
                            'Producto'=>$args["Producto"],
                            'FechaSalida'=>date("Y-m-d"),
                            'Responsable'=>$args["Responsable"],
                            'Sucursal'=>$args["Sucursal"],
                            'Cantidad'=>$args["Cantidad"]
                        ]);
                        $x=$ProductoLote->save();
                    }
                    $v=true;
                }
            }
            return array("Respuesta"=>$v);
        }
    ]
]
?>