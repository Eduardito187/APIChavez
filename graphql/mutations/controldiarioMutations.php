<?php
use App\Models\ControlDiario;
use GraphQL\Type\Definition\Type;
$controldiarioMutations=[
    'addControl'=>[
        'type'=>$boolType,
        'args'=>[
            'Sucursal'=>Type::nonNull(Type::int()),
            'Empresa'=>Type::nonNull(Type::int()),
            'Guardia'=>Type::nonNull(Type::int()),
            'Calculo'=>Type::nonNull(Type::int()),
            'Observacion'=>Type::nonNull(Type::string()),
            'GS'=>Type::nonNull(Type::int())
        ],
        'resolve'=>function($root, $args){
            $total=ControlDiario::distinct()->count('ID');
            $data=new ControlDiario([
                'ID'=>$total+1,
                'Sucursal'=>$args["Sucursal"],
                'Empresa'=>$args["Empresa"],
                'Guardia'=>$args["Guardia"],
                'Calculo'=>$args["Calculo"],
                'Observacion'=>$args["Observacion"],
                'Fecha'=>date("Y-m-d"),
                'GS'=>$args["GS"],
            ]);
            $x=$data->save();
            return array("Respuesta"=>true);
        }
    ],
    'editContral' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Sucursal'=>Type::int(),
            'Empresa'=>Type::int(),
            'Guardia'=>Type::int(),
            'Calculo'=>Type::int(),
            'Observacion'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $a=ControlDiario::find($args['ID']);
            $v=false;
            if ($a!=null) {
                ControlDiario::where('ID', $args['ID'])->update([
                    'Sucursal'=>isset($args["Sucursal"])?$args["Sucursal"]:$a->Sucursal,
                    'Empresa'=>isset($args["Empresa"])?$args["Empresa"]:$a->Empresa,
                    'Guardia'=>isset($args["Guardia"])?$args["Guardia"]:$a->Guardia,
                    'Calculo'=>isset($args["Calculo"])?$args["Calculo"]:$a->Calculo,
                    'Observacion'=>isset($args["Observacion"])?$args["Observacion"]:$a->Observacion,
                    'Fecha'=>$a->Fecha
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delControl' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $a = ControlDiario::find($args['ID']);
            $v=false;
            if ($a!=null) {
                ControlDiario::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>