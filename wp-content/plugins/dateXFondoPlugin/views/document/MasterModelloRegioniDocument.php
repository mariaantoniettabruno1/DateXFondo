<?php

namespace dateXFondoPlugin;

class MasterModelloRegioniDocument
{
public static function render()
{
    $data = new RegioniDocumentRepository();
//    if(isset($_GET['version'])){
//        //$results_articoli_costituzione = $data->getHistoryCostituzioneArticoli($_GET['editor_name'],$_GET['version']);
//        //$results_articoli_destinazione = $data->getHistoryDestinazioneArticoli($_GET['editor_name'],$_GET['version']);
//    }
//    else{
//
//    }
    $results_articoli_costituzione = $data->getCostituzioneArticoli($_GET['editor_name']);
    $results_articoli_destinazione = $data->getDestinazioneArticoli($_GET['editor_name']);



    ?>  <!DOCTYPE html>

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

    </head>

    <script>
        let articoli_costituzione = JSON.parse((`<?=json_encode($results_articoli_costituzione);?>`));
        let articoli_destinazione = JSON.parse((`<?=json_encode($results_articoli_destinazione);?>`));
        const sezioni_costituzione = {}
        const sezioni_destinazione = {}
        articoli_costituzione.forEach(a => {
            if (!sezioni_costituzione[a.sezione]) {
                sezioni_costituzione[a.sezione] = [];
            }
            if (!sezioni_costituzione[a.sezione].includes(a.sottosezione)) {
                sezioni_costituzione[a.sezione].push(a.sottosezione);
            }
        });    articoli_destinazione.forEach(a => {
            if (!sezioni_destinazione[a.sezione]) {
                sezioni_destinazione[a.sezione] = [];
            }
            if (!sezioni_destinazione[a.sezione].includes(a.sottosezione)) {
                sezioni_destinazione[a.sezione].push(a.sottosezione);
            }
        });
        console.log(sezioni_destinazione)
         window.onbeforeunload = confirmExit;
         function confirmExit() {
             return "You have attempted to leave this page. Are you sure?";
         }
    </script>

    <body>
    <div class="container-fluid">
        <div class="row pb-2">
            <?php
            \MasterModelloRegioniHeader::render();
            ?>
        </div>

        <div class="row">
            <?php
            \MasterModelloRegioniTable::render();
            ?>
        </div>
        <div class="d-flex justify-content-between pt-3">
            <div>
                <?php
                \MasterModelloRegioniStopEdit::render();
                ?>
            </div>
            <div>
                <?php
                    \MasterModelloRegioniCostituzioneRow::render();
                ?>
                <?php
                \ModelloRegioniDestinazioneRow::render();
                ?>
            </div>

        </div>
    </div>
    </body>
    </html lang="en">
<?php
}
}