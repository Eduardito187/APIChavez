<?php
use App\Models\Prioridad;
use GraphQL\Type\Definition\Type;
$prioridadMutations=[
    'addPrioridad'=>[
        'type'=>$boolType,
        'args'=>[
            'Nombre'=>Type::nonNull(Type::string()),
            'Descripcion'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $total=Prioridad::distinct()->count('ID');
            $Prioridad=new Prioridad([
                'ID'=>$total+1,
                'Nombre'=>$args["Nombre"],
                'Descripcion'=>$args["Descripcion"]
            ]);
            $x=$Prioridad->save();
            return array("Respuesta"=>true);
        }
    ],
    'editPrioridad' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Nombre'=>Type::string(),
            'Descripcion'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $Prioridad=Prioridad::find($args['ID']);
            $v=false;
            if ($Prioridad!=null) {
                Prioridad::where('ID', $args['ID'])->update([
                    'Nombre'=>isset($args["Nombre"])?$args["Nombre"]:$Prioridad->Nombre,
                    'Descripcion'=>isset($args["Descripcion"])?$args["Descripcion"]:$Prioridad->Descripcion
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delPrioridad' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Prioridad = Prioridad::find($args['ID']);
            $v=false;
            if ($Prioridad!=null && true==false) {
                Prioridad::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>