<?php
use App\Models\Lotes;
use GraphQL\Type\Definition\Type;
$LoteMutations=[
    'addLotes'=>[
        'type'=>$boolType,
        'args'=>[
            'Nombre'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $total=Lotes::distinct()->count('ID');
            $Lotes=new Lotes([
                'ID'=>$total+1,
                'Nombre'=>$args["Nombre"]
            ]);
            $x=$Lotes->save();
            return array("Respuesta"=>true);
        }
    ],
    'editLotes' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Nombre'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $Lotes=Lotes::find($args['ID']);
            $v=false;
            if ($Lotes!=null) {
                Lotes::where('ID', $args['ID'])->update([
                    'Nombre'=>isset($args["Nombre"])?$args["Nombre"]:$Lotes->Nombre
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delLotes' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Lotes = Lotes::find($args['ID']);
            $v=false;
            if ($Lotes!=null && true==false) {
                Lotes::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>