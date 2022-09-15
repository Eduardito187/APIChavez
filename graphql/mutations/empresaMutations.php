<?php
use App\Models\EmpresaGuardia;
use GraphQL\Type\Definition\Type;
$empresaMutations=[
    'addEmpresa'=>[
        'type'=>$boolType,
        'args'=>[
            'Nombre'=>Type::nonNull(Type::string()),
            'Telefono'=>Type::nonNull(Type::string()),
            'Direccion'=>Type::nonNull(Type::string()),
            'Correo'=>Type::nonNull(Type::string()),
            'Supervisores'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $total=EmpresaGuardia::distinct()->count('ID');
            $Empresa=new EmpresaGuardia([
                'ID'=>$total+1,
                'Nombre'=>$args["Nombre"],
                'Telefono'=>$args["Telefono"],
                'Direccion'=>$args["Direccion"],
                'Correo'=>$args["Correo"],
                'Supervisores'=>$args["Supervisores"]
            ]);
            $x=$Empresa->save();
            return array("Respuesta"=>true);
        }
    ],
    'editEmpresa' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Nombre'=>Type::string(),
            'Telefono'=>Type::string(),
            'Direccion'=>Type::string(),
            'Correo'=>Type::string(),
            'Supervisores'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $Empresa=EmpresaGuardia::find($args['ID']);
            $v=false;
            if ($Empresa!=null) {
                EmpresaGuardia::where('ID', $args['ID'])->update([
                    'Nombre' => isset($args["Nombre"])?$args["Nombre"]:$Empresa->Nombre,
                    'Telefono' => isset($args["Telefono"])?$args["Telefono"]:$Empresa->Telefono,
                    'Direccion' => isset($args["Direccion"])?$args["Direccion"]:$Empresa->Direccion,
                    'Correo' => isset($args["Correo"])?$args["Correo"]:$Empresa->Correo,
                    'Supervisores' => isset($args["Supervisores"])?$args["Supervisores"]:$Empresa->Supervisores
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delEmpresa' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Empresa = EmpresaGuardia::find($args['ID']);
            $v=false;
            if ($Empresa!=null) {
                EmpresaGuardia::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>