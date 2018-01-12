<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/01/2018
 * Time: 18:02
 */
function Calcular_Coste()
{
    $ruta = "F:/Level1/archivos/data.json";
    $ruta_escritura = "F:/Level1/archivos_finales/output.json";
    $cont = 1;
    $listado_final = ["bills" => []];
    if (file_exists($ruta))
    {
        $archivo = json_decode(implode(file($ruta)));

        foreach($archivo->users as $user)
        {
            foreach ($archivo->providers as $provider)
            {
                if($user->provider_id == $provider->id)
                {
                    $coste = ["id" => $cont,"price" => ($user->yearly_consumption * $provider->price_per_kwh) ,"user_id" => $user->id];
                    $cont++;
                    break;
                }
            }
            $listado_final['bills'][] = ($coste);
        }
        $archivo = fopen($ruta_escritura, "w");
        fwrite($archivo,json_encode($listado_final));
        fclose($archivo);
    }

}