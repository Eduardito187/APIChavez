<?php
use App\Models\Sucursal;
use GraphQL\Type\Definition\Type;
$sucursalMutations=[
    'addSucursal'=>[
        'type'=>$boolType,
        'args'=>[
            'ID'=>Type::int(),
            'Nombre'=>Type::nonNull(Type::string()),
            'CodigoSucursal'=>Type::nonNull(Type::string()),
            'Telefono'=>Type::nonNull(Type::string()),
            'Direccion'=>Type::nonNull(Type::string()),
            'TelfInterno'=>Type::nonNull(Type::string()),
            'Correo'=>Type::nonNull(Type::string()),
            'Region'=>Type::nonNUll(Type::string())
        ],
        'resolve'=>function($root, $args){
            $Sucursal=new Sucursal([
                'ID'=>NULL,
                'Nombre'=>$args["Nombre"],
                'CodigoSucursal'=>$args["CodigoSucursal"],
                'Telefono'=>$args["Telefono"],
                'Direccion'=>$args["Direccion"],
                'TelfInterno'=>$args["TelfInterno"],
                'Correo'=>$args["Correo"],
                'Region'=>$args["Region"]
            ]);
            $x=$Sucursal->save();
            return array("Respuesta"=>true);
        }
    ],
    'editSucursal' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Nombre'=>Type::string(),
            'CodigoSucursal'=>Type::string(),
            'Telefono'=>Type::string(),
            'Direccion'=>Type::string(),
            'TelfInterno'=>Type::string(),
            'Correo'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $Sucursal=Sucursal::find($args['ID']);
            $v=false;
            if ($Sucursal!=null) {
                Sucursal::where('ID', $args['ID'])->update([
                    'Nombre'=>isset($args["Nombre"])?$args["Nombre"]:$Sucursal->Nombre,
                    'CodigoSucursal'=>isset($args["CodigoSucursal"])?$args["CodigoSucursal"]:$Sucursal->CodigoSucursal,
                    'Telefono'=>isset($args["Telefono"])?$args["Telefono"]:$Sucursal->Telefono,
                    'Direccion'=>isset($args["Direccion"])?$args["Direccion"]:$Sucursal->Direccion,
                    'TelfInterno'=>isset($args["TelfInterno"])?$args["TelfInterno"]:$Sucursal->TelfInterno,
                    'Correo'=>isset($args["Correo"])?$args["Correo"]:$Sucursal->Correo
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delSucursal' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Sucursal = Sucursal::find($args['ID']);
            $v=false;
            if ($Sucursal!=null && true==false) {
                Sucursal::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>