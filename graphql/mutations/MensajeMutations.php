<?php
use App\Models\Mensajes;
use GraphQL\Type\Definition\Type;
$MensajeMutations=[
    'EnviarMSG'=>[
        'type'=>$mensajesType,
        'args'=>[
            'De'=>Type::nonNull(Type::int()),
            'Para'=>Type::nonNull(Type::int()),
            'Texto'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $date=date("Y-m-d H:i:s");
            $msg=new Mensajes([
                'ID'=>NULL,
                'De'=>$args["De"],
                'Para'=>$args["Para"],
                'Texto'=>$args["Texto"],
                'Fecha'=>$date,
                'Leido'=>0,
                'F_Leido'=>NULL
            ]);
            $x=$msg->save();
            $Mensaje=Mensajes::where('Fecha',$date)->first();
            return $Mensaje;
        }
    ],
    'EditMSG' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int()),
            'De' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Mensaje=Mensajes::find($args['ID']);
            $v=false;
            if ($Mensaje!=null) {
                if ($Mensaje->De==$args["De"]) {
                    Mensajes::where('ID', $args['ID'])->update([
                        'Texto' => isset($args["Texto"])?$args["Texto"]:$Mensaje->Texto
                    ]);
                    $v=true;
                }
            }
            return array("Respuesta"=>$v);
        }
    ],
    'DelMSG' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int()),
            'De' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Mensaje = Mensajes::find($args['ID']);
            $v=false;
            if ($Mensaje!=null) {
                if ($Mensaje->De==$args["De"]) {
                    Mensajes::where('ID', $args['ID'])->delete();
                    $v=true;
                }
            }
            return array("Respuesta"=>$v);
        }
    ],
    'LeerMSG' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int()),
            'De' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Mensaje = Mensajes::find($args['ID']);
            $v=false;
            if ($Mensaje!=null) {
                if ($Mensaje->De==$args["De"]) {
                    Mensajes::where('ID', $args['ID'])->update([
                        'Leido' => 1,
                        'F_Leido'=> date("Y-m-d H:i:s")
                    ]);
                    $v=true;
                }
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>