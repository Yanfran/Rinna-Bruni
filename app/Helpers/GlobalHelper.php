<?php

namespace App\Helpers;

use App\Models\Empresas;

class GlobalHelper
{
    public static function getEmpresa($id)
    {
        return Empresas::find($id);
    }

    public static function generateAfiliacionCode($user, $id, $idTienda, $idDistri = null)
    {      
        
        

        // Define la cantidad de dígitos que deseas para el ID (por ejemplo, 5 dígitos)
        $idLength = 5;
        $idLengthTienda = 2;

        // Formatea el ID con ceros a la izquierda
        $formattedId = str_pad($id, $idLength, '0', STR_PAD_LEFT);

        // Formatea el IDDistri con ceros a la izquierda
        $formattedId2 = str_pad($idDistri, $idLength, '0', STR_PAD_LEFT);

        // Formatea el IDTienda con ceros a la izquierda
        $formattedId3 = str_pad($idTienda, $idLengthTienda, '0', STR_PAD_LEFT);

        if ($user == 'E') {
            
            $codigo = $user.''.$formattedId3.'-'.$formattedId; // Ejemplo: E02-00045    
            return $codigo;

        } else if ($user == 'D') {
            
            $codigo = $user.''.$formattedId3.'-'.$formattedId; // Ejemplo: D02-00045    
            return $codigo;

        } else if ($user == 'S') {

            $codigo = $user.''.$formattedId3.'-'.$formattedId; // Ejemplo: S03-00045    
            return $codigo;

        } else if ($user == 'SD'){

            $codigo = $user.''.$formattedId3.'-'.$formattedId2. '-'.$formattedId; // Ejemplo: SD01-00045-00041    
            return $codigo;

        }

    }
}
