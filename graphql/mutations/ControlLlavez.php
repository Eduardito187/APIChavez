<?php
use App\Models\ControlLLAVES;
use GraphQL\Type\Definition\Type;
$ControlLlavez=[
    'addControlLLAVES'=>[
        'type'=>$boolType,
        'args'=>[
            'Sucursal'=>Type::nonNull(Type::int()),
            'Entrega'=>Type::nonNull(Type::string()),
            'Observacion'=>Type::nonNull(Type::string()),
            'Responsable'=>Type::nonNull(Type::int())
        ],
        'resolve'=>function($root, $args){
            $total=ControlLLAVES::distinct()->count('ID');
            $ControlLLAVES=new ControlLLAVES([
                'ID'=>$total+1,
                'FechaInicio'=>date("Y-m-d"),
                'FechaDevolucion'=>null,
                'Sucursal'=>$args["Sucursal"],
                'Entrega'=>$args["Entrega"],
                'Observacion'=>$args["Observacion"],
                'Responsable'=>$args["Responsable"]
            ]);
            $x=$ControlLLAVES->save();
            return array("Respuesta"=>true);
        }
    ],
    'editControlLLAVES' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Sucursal'=>Type::int(),
            'Entrega'=>Type::string(),
            'Observacion'=>Type::string(),
            'Responsable'=>Type::int()
        ],
        'resolve' => function($root, $args) {
            $ControlLLAVES=ControlLLAVES::find($args['ID']);
            $v=false;
            if ($ControlLLAVES!=null) {
                ControlLLAVES::where('ID', $args['ID'])->update([
                    'Sucursal'=>isset($args["Sucursal"])?$args["Sucursal"]:$ControlLLAVES->Sucursal,
                    'Entrega'=>isset($args["Entrega"])?$args["Entrega"]:$ControlLLAVES->Entrega,
                    'Observacion'=>isset($args["Observacion"])?$args["Observacion"]:$ControlLLAVES->Observacion,
                    'Responsable'=>isset($args["Responsable"])?$args["Responsable"]:$ControlLLAVES->Responsable
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delControlLLAVES' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $ControlLLAVES = ControlLLAVES::find($args['ID']);
            $v=false;
            if ($ControlLLAVES!=null && true==false) {
                ControlLLAVES::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'ControlLLAVES_FIN' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $ControlLLAVES=ControlLLAVES::find($args['ID']);
            $v=false;
            if ($ControlLLAVES!=null) {
                ControlLLAVES::where('ID', $args['ID'])->update([
                    'FechaDevolucion'=>date("Y-m-d")
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>