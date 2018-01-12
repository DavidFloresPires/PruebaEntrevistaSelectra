<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 12/01/2018
 * Time: 18:02
 */
function Calcular_Coste()
{
    $ruta = "F:/Level4/archivos/data.json";
    $ruta_escritura = "F:/Level4/archivos_finales/output.json";
    $cont = 1;
    $listado_final = ["bills" => []];
    if (file_exists($ruta))
    {
        $archivo = json_decode(implode(file($ruta)));

        foreach($archivo->contracts as $contract)
        {
            foreach ($archivo->providers as $provider)
            {
                if($contract->provider_id == $provider->id)
                {
                    $coste_x_kw = $provider->price_per_kwh;
                    break;
                }
            }
            foreach ($archivo->users as $user)
            {
                if($contract->user_id == $user->id)
                {
                    $user_fin = $user;
                    break;
                }
            }
            $cost = $user_fin->yearly_consumption * $coste_x_kw;
            if($contract->contract_length <= 1)
            {
                $cost = $cost * 0.9 * $contract->contract_length;
            }
            else
            {
                if($contract->contract_length <= 3)
                    $cost = $cost * 0.8 * $contract->contract_length;
                else
                    $cost = $cost * 0.75 * $contract->contract_length;
            }
            if($contract->green)
            {
                $cost -=  ($user_fin->yearly_consumption * 0.05);
            }
            $insurance = 18.25 * $contract->contract_length;
            $coste = ["id" => $cont,"commission" => ["insurance_fee" =>  $insurance, "provider_fee" => $cost - $insurance,"selectra_fee" => $this->calculate_selectra($cost - $insurance)],"price" => $cost ,"user_id" => $user_fin->id];
            $cont++;


            $listado_final['bills'][] = ($coste);
        }
        $archivo = fopen($ruta_escritura, "w");
        fwrite($archivo,json_encode($listado_final));
        fclose($archivo);
    }
}