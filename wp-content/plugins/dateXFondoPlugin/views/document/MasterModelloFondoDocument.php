<?php

namespace dateXFondoPlugin;


use DocumentRepository;
use MasterModelloFondoDocumentTable;


class MasterModelloFondoDocument
{
    public static function render()
    {
        $data = new DocumentRepository();
        $results_articoli = $data->getArticoli('Emanuele Lesca');
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
                <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

                <script>
                </script>
            </head>

            <script>
                let articoli = JSON.parse((`<?=json_encode($results_articoli);?>`));
                const sezioni = []
                console.log(articoli)

                articoli.forEach(a => {
                    if (!sezioni.includes(a.sezione)) {
                        sezioni.push(a.sezione);
                    }
                });
            </script>
            <body>
            <div class="container-fluid">

                    <?php
                  MasterModelloFondoDocumentTable::render();
                  ?>

            </body>
            </html lang="en">

            <?php
    }
}