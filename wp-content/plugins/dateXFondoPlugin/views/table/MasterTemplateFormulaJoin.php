<?php

namespace dateXFondoPlugin;

use dateXFondoPlugin\MasterTemplateRepository;
use MasterJoinTable;

header('Content-Type: text/javascript');

class MasterTemplateFormulaJoin
{
    public static function render()
    {
        $data = new MasterJoinTableRepository();
        $results_articoli = $data->getJoinedArticoli();
        $results_formula = $data->getJoinedFormulas();
        $results_joined = $data->getJoinedRecords();

        ?>
        <!DOCTYPE html>

        <html lang="en">

        <head>
            <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
            <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
                    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                    crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
                    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                    crossorigin="anonymous"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
                  integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
                  crossorigin="anonymous" referrerpolicy="no-referrer"/>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

            <script>
                const articoli = JSON.parse((`<?=json_encode($results_articoli);?>`));
                const formulas = JSON.parse((`<?=json_encode($results_formula);?>`));
                const joined = JSON.parse((`<?=json_encode($results_joined);?>`));
                let joined_record = [
                    ...articoli,
                    ...formulas
                ];

                const joinedIndexes = {}
                joined.forEach(r => {
                    if (r.type === "0") {
                        joinedIndexes["T" + r.external_id] = r
                    } else {
                        joinedIndexes["F" + r.external_id] = r
                    }
                })
                console.log(joined_record, joinedIndexes)

                const sezioni = {}
                articoli.forEach(a => {
                    if (!sezioni[a.sezione]) {
                        sezioni[a.sezione] = [];
                    }
                    if (!sezioni[a.sezione].includes(a.sottosezione)) {
                        sezioni[a.sezione].push(a.sottosezione);
                    }
                });
            </script>
        </head>

        <body>
        <div class="container-fluid">
            <div class="row">
                <?php
                MasterJoinTable::render();
                ?>

            </div>
        </div>
        </body>
        </html lang="en">
        <?php
    }
}