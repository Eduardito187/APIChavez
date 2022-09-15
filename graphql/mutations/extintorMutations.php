<?php
use App\Models\Extintor;
use App\Models\ExtintorDato;
use GraphQL\Type\Definition\Type;
$extintorMutations=[
    'addExtintor'=>[
        'type'=>$ContadorType,
        'args'=>[
            'Tipo'=>Type::nonNull(Type::string()),
            'Cantidad'=>Type::nonNull(Type::int()),
            'Sucursal'=>Type::nonNull(Type::int()),
            'Proveedor'=>Type::nonNull(Type::int())
        ],
        'resolve'=>function($root, $args){
            $created=date("Y-m-d H:i:s");
            $Extintor=new Extintor([
                'ID'=>NULL,
                'Fecha'=>date("Y-m-d"),
                'Tipo'=>$args["Tipo"],
                'Cantidad'=>$args["Cantidad"],
                'Sucursal'=>$args["Sucursal"],
                'Proveedor'=>$args["Proveedor"],
                'Creacion'=>$created
            ]);
            $x=$Extintor->save();
            $Elemento=Extintor::where('Creacion',$created)->get()->toArray();
            return array("Cantidad"=>$Elemento[0]["ID"]);
        }
    ],
    'editExtintor' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Tipo'=>Type::string(),
            'Cantidad'=>Type::int(),
            'Sucursal'=>Type::int(),
            'Proveedor'=>Type::int()
        ],
        'resolve' => function($root, $args) {
            $Extintor=Extintor::find($args['ID']);
            $v=false;
            if ($Extintor!=null) {
                Extintor::where('ID', $args['ID'])->update([
                    'Fecha'=>$Extintor->Fecha,
                    'Tipo'=>isset($args["Tipo"])?$args["Tipo"]:$Extintor->Tipo,
                    'Cantidad'=>isset($args["Cantidad"])?$args["Cantidad"]:$Extintor->Cantidad,
                    'Sucursal'=>isset($args["Sucursal"])?$args["Sucursal"]:$Extintor->Sucursal,
                    'Proveedor'=>isset($args["Proveedor"])?$args["Proveedor"]:$Extintor->Proveedor
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delExtintor' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Extintor = Extintor::find($args['ID']);
            $v=false;
            if ($Extintor!=null && true==false) {
                Extintor::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'AddExtintorLlave' => [
        'type'=>$boolType,
        'args'=>[
            'Codigo'=>Type::int(),
            'PH'=>Type::int(),
            'Peso'=>Type::nonNull(Type::string()),
            'Recargo'=>Type::nonNull(Type::string()),
            'Llave'=>Type::nonNull(Type::int()),
            'Observacion'=>Type::string()
        ],
        'resolve'=>function($root, $args){
            $Extintor=new ExtintorDato([
                'ID'=>NULL,
                'Codigo'=>$args["Codigo"],
                'PH'=>$args["PH"],
                'Peso'=>$args["Peso"],
                'Recargo'=>$args["Recargo"],
                'Extintores'=>$args["Llave"],
                'Observacion'=>$args["Observacion"]
            ]);
            $x=$Extintor->save();
            return array("Respuesta"=>true);
        }
    ],
    'UpdateExtintorLlave'=>[
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Codigo'=>Type::int(),
            'PH'=>Type::int(),
            'Observacion'=>Type::nonNull(Type::string()),
            'Recargo'=>Type::nonNull(Type::string())
        ],
        'resolve' => function($root, $args) {
            $v=false;
            if($args["ID"]!=0){
                $PH;
                $COD;
                if ($args["PH"]==0) {
                    $PH=NULL;
                }else{
                    $PH=$args["PH"];
                }
                if ($args["Codigo"]==0) {
                    $COD=NULL;
                }else{
                    $COD=$args["Codigo"];
                }
                $Extintor=ExtintorDato::find($args['ID']);
                if ($Extintor!=null) {
                    ExtintorDato::where('ID', $args['ID'])->update([
                        'Codigo'=>$COD!=$Extintor->Codigo?$COD:$Extintor->Codigo,
                        'PH'=>$PH!=$Extintor->PH?$PH:$Extintor->PH,
                        'Observacion'=>isset($args["Observacion"])?$args["Observacion"]:$Extintor->Observacion,
                        'Recargo'=>isset($args["Recargo"])?$args["Recargo"]:$Extintor->Recargo
                    ]);
                    $v=true;
                }
            }
            
            return array("Respuesta"=>$v);
        }
    ],
    'RecargarExtintor'=>[
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Observacion'=>Type::nonNull(Type::string()),
            'Recargo'=>Type::nonNull(Type::string())
        ],
        'resolve' => function($root, $args) {
            $v=false;
            if($args["ID"]!=0){
                $Extintor=ExtintorDato::find($args['ID']);
                if ($Extintor!=null) {
                    ExtintorDato::where('ID', $args['ID'])->update([
                        'Observacion'=>isset($args["Observacion"])?$args["Observacion"]:$Extintor->Observacion,
                        'Recargo'=>isset($args["Recargo"])?$args["Recargo"]:$Extintor->Recargo
                    ]);
                    $v=true;
                }
            }
            
            return array("Respuesta"=>$v);
        }
    ]
]
?>