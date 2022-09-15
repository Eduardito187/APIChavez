<?php
use App\Models\Guardia;
use App\Models\GuardiaSucursal;
use GraphQL\Type\Definition\Type;
$GuardiaMutations=[
    'addGuardia'=>[
        'type'=>$boolType,
        'args'=>[
            'Nombre'=>Type::nonNull(Type::string()),
            'Telefono'=>Type::nonNull(Type::string()),
            'Precio'=>Type::nonNull(Type::int()),
            'LLAVE'=>Type::nonNull(Type::int())
        ],
        'resolve'=>function($root, $args){
            $total=Guardia::distinct()->count('ID');
            $total+=1;
            $Guardia=new Guardia([
                'ID'=>$total,
                'Nombre'=>$args["Nombre"],
                'Telefono'=>$args["Telefono"],
                'Precio'=>$args["Precio"]
            ]);
            $x=$Guardia->save();
            $total1=GuardiaSucursal::distinct()->count('ID');
            $total1+=1;
            $GuardiaSucursal=new GuardiaSucursal([
                'ID'=>$total1,
                'Guardia'=>$total,
                'GS'=>$args["LLAVE"]
            ]);
            $x=$GuardiaSucursal->save();
            return array("Respuesta"=>true);
        }
    ],
    'editGuardia' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Nombre'=>Type::string(),
            'Telefono'=>Type::string(),
            'Precio'=>Type::int()
        ],
        'resolve' => function($root, $args) {
            $Guardia=Guardia::find($args['ID']);
            $v=false;
            if ($Guardia!=null) {
                Guardia::where('ID', $args['ID'])->update([
                    'Nombre'=>isset($args["Nombre"])?$args["Nombre"]:$Guardia->Nombre,
                    'Telefono'=>isset($args["Telefono"])?$args["Telefono"]:$Guardia->Telefono,
                    'Precio'=>isset($args["Precio"])?$args["Precio"]:$Guardia->Precio
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delGuardia' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Guardia = Guardia::find($args['ID']);
            $v=false;
            if ($Guardia!=null && true==false) {
                Guardia::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>