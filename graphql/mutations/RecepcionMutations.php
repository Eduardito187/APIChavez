<?php
use App\Models\Recepcion;
use GraphQL\Type\Definition\Type;
$RecepcionMutations=[
    'addRecepcion'=>[
        'type'=>$boolType,
        'args'=>[
            'Responsable'=>Type::nonNull(Type::int()),
            'Entregado'=>Type::nonNull(Type::string()),
            'Descripcion'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $total=Recepcion::distinct()->count('ID');
            $Recepcion=new Recepcion([
                'ID'=>$total+1,
                'Fecha'=>date("Y-m-d"),
                'Responsable'=>$args["Responsable"],
                'Entregado'=>$args["Entregado"],
                'Descripcion'=>$args["Descripcion"]
            ]);
            $x=$Recepcion->save();
            return array("Respuesta"=>true);
        }
    ],
    'editRecepcion' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Entregado'=>Type::nonNull(Type::string()),
            'Descripcion'=>Type::nonNull(Type::string())
        ],
        'resolve' => function($root, $args) {
            $Recepcion=Recepcion::find($args['ID']);
            $v=false;
            if ($Recepcion!=null) {
                Recepcion::where('ID', $args['ID'])->update([
                    'Entregado'=>isset($args["Entregado"])?$args["Entregado"]:$Recepcion->Entregado,
                    'Descripcion'=>isset($args["Descripcion"])?$args["Descripcion"]:$Recepcion->Descripcion
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delRecepcion' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Recepcion = Recepcion::find($args['ID']);
            $v=false;
            if ($Recepcion!=null && true==false) {
                Recepcion::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>