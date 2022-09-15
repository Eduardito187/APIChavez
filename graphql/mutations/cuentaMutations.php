<?php
use App\Models\Cuenta;
use GraphQL\Type\Definition\Type;
$cuentaMutations=[
    'addCuenta'=>[
        'type'=>$boolType,
        'args'=>[
            'usuario'=>Type::nonNull(Type::string()),
            'contra'=>Type::nonNull(Type::string()),
            'nombre'=>Type::nonNull(Type::string()),
            'RangoID'=>Type::nonNull(Type::int())
        ],
        'resolve'=>function($root, $args){
            $total=Cuenta::distinct()->count('ID');
            $cuenta=new Cuenta([
                'ID'=>$total+1,
                'usuario'=>$args["usuario"],
                'contra'=>md5($args["contra"]),
                'nombre'=>$args["nombre"],
                'foto'=>"",
                'RangoID'=>$args["RangoID"],
                'DIR'=>"",
                'FechaCreado'=>date("Y-m-d h:i:s"),
                'FechaActualizado'=>NULL,
                'FechaEliminado'=>NULL
            ]);
            $x=$cuenta->save();
            return array("Respuesta"=>true);
        }
    ],
    'editCuenta' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'usuario'=>Type::string(),
            'contra'=>Type::string(),
            'nombre'=>Type::string(),
            'foto'=>Type::string(),
            'RangoID'=>Type::int(),
            'DIR'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $cuenta=Cuenta::find($args['ID']);
            $v=false;
            if ($cuenta!=null) {
                Cuenta::where('ID', $args['ID'])->update([
                    'usuario' => isset($args["usuario"])?$args["usuario"]:$cuenta->usuario,
                    'contra' => isset($args["contra"])?md5($args["contra"]):$cuenta->contra,
                    'nombre' => isset($args["nombre"])?$args["nombre"]:$cuenta->nombre,
                    'foto' => isset($args["foto"])?$args["foto"]:$cuenta->foto,
                    'RangoID' => isset($args["RangoID"])?$args["RangoID"]:$cuenta->RangoID,
                    'DIR' => isset($args["DIR"])?$args["DIR"]:$cuenta->DIR,
                    'FechaActualizado' => date("Y-m-d h:i:s")
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delCuenta' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $cuenta = Cuenta::find($args['ID']);
            $v=false;
            if ($cuenta!=null) {
                //Cuenta::where('ID', $args['ID'])->delete();
                Cuenta::where('ID', $args['ID'])->update([
                    'FechaEliminado' => date("Y-m-d h:i:s")
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'SetPWD' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'contra'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $cuenta=Cuenta::find($args['ID']);
            $v=false;
            if ($cuenta!=null) {
                Cuenta::where('ID', $args['ID'])->update([
                    'contra' => isset($args["contra"])?md5($args["contra"]):$cuenta->contra
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>