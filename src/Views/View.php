<?php

namespace App\Views;

class View
{
    public static function render(string $template, array $data = [])
    {
        extract($data);

        $file = __DIR__ . '/pages/' . $template . '.php';
        //echo $template;

        if (file_exists($file)) {
            ob_start();
            include $file;
            $content = ob_get_clean();
            
            if ($template!= "/login") {

                if ($template!= "impresionVale" &&  $template!= "reporte-u-d" ){
                    
                    include __DIR__ . '/layouts/main.php';
                   
                }else{                    
                    include __DIR__ . '/layouts/impresion.php';
                }              

            }else{
                include __DIR__ . '/layouts/login.php';
            }          
           

        } else {
            echo " La vista {$template} no existe.";
        }
    }
}
