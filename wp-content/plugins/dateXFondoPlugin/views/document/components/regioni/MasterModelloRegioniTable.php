<?php

class MasterModelloRegioniTable
{
    public static function render_scripts()
    {
        ?>
        <script>
            function ExportExcel() {
                let worksheet_costituzione = ExportCostituzioneToExcel();
                let worksheet_destinazione = ExportDestinazioneToExcel();
                const new_workbook = XLSX.utils.book_new()
                XLSX.utils.book_append_sheet(new_workbook, worksheet_costituzione, "Costituzione")
                XLSX.utils.book_append_sheet(new_workbook, worksheet_destinazione, "Destinazione")
                XLSX.writeFile(new_workbook, ('xlsx' + 'ModelloRegioni.xlsx'))
            }
        </script>
            <?php


    }

    public static function render()
    {
        ?>
        <div class="container pt-3" style="width: 100%">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-link active" id="regioni-costituzione-tab" href="#regionicostituzione" role="tab"
                       aria-controls="regionicostituzione" aria-selected="true" data-toggle="pill">Costituzione</a>
                    <a class="nav-link" id="destinazione-tab" href="#destinazione" role="tab" aria-controls="destinazione"
                       aria-selected="false" data-toggle="pill">Utilizzo</a>
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-outline-primary" onclick="ExportExcel()">genera foglio excel</button>
                    </div>
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="regionicostituzione" role="tabpanel" aria-labelledby="regioni_costituzione-tab" aria-selected="true">
                    <?php
                    MasterModelloRegioniCostituzioneTable::render();
                    ?>
                </div>
                <div class="tab-pane fade" id="destinazione" role="tabpanel" aria-labelledby="destinazione-tab" aria-selected="false">
                    <?php
                    MasterModelloRegioniDestinazioneTable::render();
                    ?>
                </div>
            </div>
        </div>


        <?php
        self::render_scripts();
    }
}