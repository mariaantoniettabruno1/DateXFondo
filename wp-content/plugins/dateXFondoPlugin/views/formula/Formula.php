<?php

namespace dateXFondoPlugin;

use FormulaTable;

header('Content-Type: text/javascript');

class Formula
{
    public static function render()
    {
        $data = new FormulaTable();
        $result = $data->getArticoli();
        ?>


        <!DOCTYPE html>

        <html lang="en">

        <head>
            <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
                  integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
                  crossorigin="anonymous" referrerpolicy="no-referrer"/>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
            <style type="text/css">

                .field_description > span {
                    overflow: hidden;
                    max-height: 200px;
                    min-width: 40px;
                    min-height: 40px;
                    display: inline-block;
                }

                .field_section > select {
                    width: 150px;
                }

                #closeConditionButtonId {
                    color: #0a0a0a;
                }

                #infoPointId {
                    color: grey;
                }

            </style>
            <script>
                const articoli = JSON.parse((`<?=json_encode($result);?>`));
                const sezioni = {}
                articoli.forEach(a => {
                    if(!sezioni[a.sezione]){
                        sezioni[a.sezione] = [];
                    }
                    if(!sezioni[a.sezione].includes(a.sottosezione)){
                        sezioni[a.sezione].push(a.sottosezione);
                    }
                })
                console.log(articoli, sezioni);
            </script>
        </head>

        <body>
            
        </body>
        </html lang="en">

        <?php


    }
}