<?php
use App\Models\EmpresaGuardiaSucursal;
use GraphQL\Type\Definition\Type;
$EmpresaGuardiaSucursalMutations=[
    'addEmpresaGuardiaSucursal'=>[
        'type'=>$boolType,
        'args'=>[
            'Empresa'=>Type::nonNull(Type::int()),
            'Sucursal'=>Type::nonNull(Type::int()),
            'Ingreso'=>Type::nonNull(Type::string()),
            'Salida'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $total=EmpresaGuardiaSucursal::distinct()->count('ID');
            $Empresa=new EmpresaGuardiaSucursal([
                'ID'=>$total+1,
                'Empresa'=>$args["Empresa"],
                'Sucursal'=>$args["Sucursal"],
                'Ingreso'=>$args["Ingreso"],
                'Salida'=>$args["Salida"]
            ]);
            $x=$Empresa->save();
            return array("Respuesta"=>true);
        }
    ],
    'editEmpresaGuardiaSucursal' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Empresa'=>Type::int(),
            'Sucursal'=>Type::int(),
            'Ingreso'=>Type::string(),
            'Salida'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $Empresa=EmpresaGuardiaSucursal::find($args['ID']);
            $v=false;
            if ($Empresa!=null) {
                EmpresaGuardiaSucursal::where('ID', $args['ID'])->update([
                    'Empresa' => isset($args["Empresa"])?$args["Empresa"]:$Empresa->Empresa,
                    'Sucursal' => isset($args["Sucursal"])?$args["Sucursal"]:$Empresa->Sucursal,
                    'Ingreso' => isset($args["Ingreso"])?$args["Ingreso"]:$Empresa->Ingreso,
                    'Salida' => isset($args["Salida"])?$args["Salida"]:$Empresa->Salida
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delEmpresaGuardiaSucursal' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Empresa = EmpresaGuardiaSucursal::find($args['ID']);
            $v=false;
            if ($Empresa!=null) {
                EmpresaGuardiaSucursal::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>