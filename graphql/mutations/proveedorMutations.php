<?php
use App\Models\Proveedor;
use GraphQL\Type\Definition\Type;
$proveedorMutations=[
    'addProveedor'=>[
        'type'=>$boolType,
        'args'=>[
            'Nombre'=>Type::nonNull(Type::string()),
            'Telefono'=>Type::nonNull(Type::string()),
            'Direccion'=>Type::nonNull(Type::string()),
            'Correo'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $total=Proveedor::distinct()->count('ID');
            $Proveedor=new Proveedor([
                'ID'=>$total+1,
                'Nombre'=>$args["Nombre"],
                'Telefono'=>$args["Telefono"],
                'Direccion'=>$args["Direccion"],
                'Correo'=>$args["Correo"]
            ]);
            $x=$Proveedor->save();
            return array("Respuesta"=>true);
        }
    ],
    'editProveedor' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Nombre'=>Type::string(),
            'Telefono'=>Type::string(),
            'Direccion'=>Type::string(),
            'Correo'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $Proveedor=Proveedor::find($args['ID']);
            $v=false;
            if ($Proveedor!=null) {
                Proveedor::where('ID', $args['ID'])->update([
                    'Nombre' => isset($args["Nombre"])?$args["Nombre"]:$Proveedor->Nombre,
                    'Telefono' => isset($args["Telefono"])?$args["Telefono"]:$Proveedor->Telefono,
                    'Direccion' => isset($args["Direccion"])?$args["Direccion"]:$Proveedor->Direccion,
                    'Correo' => isset($args["Correo"])?$args["Correo"]:$Proveedor->Correo
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delProveedor' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Proveedor = Proveedor::find($args['ID']);
            $v=false;
            if ($Proveedor!=null && true==false) {
                Proveedor::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>