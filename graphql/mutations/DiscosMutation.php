<?php
use App\Models\ControlLLAVES;
use App\Models\ControlDiscos;
use App\Models\SalidaDiscos;

use GraphQL\Type\Definition\Type;
$DiscosMutation=[
    'addControlDISCO'=>[
        'type'=>$boolType,
        'args'=>[
            'Sucursal'=>Type::nonNull(Type::int()),
            'CantidadDiscos'=>Type::nonNull(Type::int()),
            'ReqFiscal'=>Type::nonNull(Type::string()),
            'FechaFin'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $ControlDiscos=new ControlDiscos([
                'ID'=>NULL,
                'Fecha'=>date("Y-m-d"),
                'Sucursal'=>$args["Sucursal"],
                'CantidadDiscos'=>$args["CantidadDiscos"],
                'ReqFiscal'=>$args["ReqFiscal"],
                'FechaFinalizacion'=>$args["FechaFin"],
                'Salida'=>NULL
            ]);
            $x=$ControlDiscos->save();
            return array("Respuesta"=>true);
        }
    ],
    'addSalidaControlDisco' => [
        'type' => $boolType,
        'args' => [
            'Control'=>Type::nonNull(Type::int()),
            'Nombre'=>Type::nonNull(Type::string()),
            'Detalle'=>Type::nonNull(Type::string())
        ],
        'resolve' => function($root, $args) {
            $v=false;
            $ControlDiscos=ControlDiscos::find($args["Control"]);
            if ($ControlDiscos!=null) {
                $fecha_now=date("Y-m-d");
                $SalidaDiscos=new SalidaDiscos([
                    'ID'=>NULL,
                    'FechaEntrega'=>$fecha_now,
                    'Nombre'=>$args["Nombre"],
                    'Detalle'=>$args["Detalle"]
                ]);
                $x=$SalidaDiscos->save();

                $SalidaDiscos_n=SalidaDiscos::where('FechaEntrega',$fecha_now)->
                where('Nombre',$args["Nombre"])->where('Detalle',$args["Detalle"])->first();
                if ($SalidaDiscos_n!=null) {
                    ControlDiscos::where('ID', $args['Control'])->update([
                        'Salida' => $SalidaDiscos_n->ID
                    ]);
                    $v=true;
                }
            }
            return array("Respuesta"=>$v);
        }
    ]
]
?>