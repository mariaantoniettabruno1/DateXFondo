<?php

namespace dateXFondoPlugin;

use dateXFondoPlugin\MasterTemplateRepository;

header('Content-Type: text/javascript');

class MasterTemplate
{
    public static function render()
    {

        $data = new MasterTemplateRepository();
        if (isset($_GET['fondo']) || isset($_GET['anno']) || isset($_GET['descrizione']) || isset($_GET['version'])) {
            $results_articoli = $data->visualize_template($_GET['fondo'], $_GET['anno'], $_GET['descrizione'], $_GET['version']);

        } else {
            $results_articoli = $data->getArticoli();
        }
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
                let articoli = JSON.parse((`<?=json_encode($results_articoli);?>`));
                const sezioni = {}
                articoli.forEach(a => {
                    if (!sezioni[a.sezione]) {
                        sezioni[a.sezione] = [];
                    }
                    if (!sezioni[a.sezione].includes(a.sottosezione)) {
                        sezioni[a.sezione].push(a.sottosezione);
                    }
                });
                console.log(articoli)
            </script>
        </head>

        <body>
        <div class="container-fluid">
            <div class="row">
                <?php
                MasterTemplateHeader::render();
                ?>

            </div>

            <div class="row">
                <?php
                MasterTemplateTable::render();
                ?>
            </div>
            <div class="row pt-2 justify-content-end">
                <div class="col align-self-start">
                    <?php
                    MasterTemplateStopEditingButton::render();
                    ?>
                </div>
                <div class="pr-2">
                    <?php
                    MasterTemplateNewRow::render();
                    ?>
                </div>
                <div class="pr-2">
                    <?php
                    MasterTemplateNewSpecialRow::render();
                    ?>
                </div>
                <div class="pr-2">
                    <?php
                    MasterTemplateNewDecurtationRow::render();
                    ?>
                </div>
            </div>
        </body>
        </html lang="en">

        <?php
    }


}