<?php

namespace dateXFondoPlugin;

class MasterTemplateHistoryTable
{
    public static function render_scripts()
    {
        ?>
        <script>
            let fondo = '';
            let anno = 0;
            let descrizione = '';
            let version = 0;

            function renderDataTable() {
                $('#dataTemplateTableBody').html('');
                articoli.forEach(art => {
                    $('#dataTemplateTableBody').append(`
                                 <tr>
                                       <td >${art.fondo}</td>
                                       <td >${art.anno}</td>
                                       <td >${art.descrizione_fondo}</td>
                                       <td >${art.version}</td>
                                           <td>
                <button class="btn btn-primary btn-duplicate-template" data-fondo='${art.fondo}' data-anno='${art.anno}' data-desc ='${art.descrizione_fondo}' data-version='${art.version}' data-toggle="modal" data-target="#duplicateModal">Duplica</button>
                <button class="btn btn-primary btn-visualize-template" data-fondo='${art.fondo}' data-anno='${art.anno}' data-desc ='${art.descrizione_fondo}' data-version='${art.version}'>Visualizza</button>
                </td>
                </tr>
                             `);

                });
                $('.btn-duplicate-template').click(function () {
                    fondo = $(this).attr('data-fondo');
                    anno = $(this).attr('data-anno');
                    descrizione = $(this).attr('data-desc');
                    version = $(this).attr('data-version');
                });
                $('.btn-visualize-template').click(function () {
                    fondo = $(this).attr('data-fondo');
                    anno = $(this).attr('data-anno');
                    descrizione = $(this).attr('data-desc');
                    version = $(this).attr('data-version');
                });
                console.log(articoli);
            }

            $(document).ready(function () {

                renderDataTable();
                $('#duplicateTemplateButton').click(function () {
                    const payload = {
                        fondo,
                        anno,
                        descrizione,
                        version
                    }
                    console.log(payload)

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/duplicatetemplate',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#duplicateModal").modal('hide');
                            location.href = '<?= DateXFondoCommon::get_website_url() ?>/visualizza-template-fondo/';
                        },
                        error: function (response) {
                            console.error(response);
                            $("#duplicateModal").modal('hide');
                        }
                    });
                });
                $('.btn-visualize-template').click(function () {
                    location.href = '<?= DateXFondoCommon::get_website_url()?>/visualizza-template-fondo/?fondo='+fondo+'&anno='+anno+'&descrizione='+descrizione+'&version='+version;
                });
            });
        </script>
    <?php }

    public static function render()
    {

        ?>
        <table class="table" style="table-layout: fixed">
            <thead>
            <tr>
                <th style="width: 200px">Fondo</th>
                <th style="width: 100px">Anno</th>
                <th>Descrizione fondo</th>
                <th style="width: 100px">Versione</th>
                <th style="width: 202px">Azioni</th>
            </tr>

            </thead>
            <tbody id="dataTemplateTableBody">
            </tbody>
        </table>

        <div class="modal fade" id="duplicateModal" tabindex="-1" role="dialog" aria-labelledby="duplicateModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="duplicateModalLabel">Duplica Template </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vuoi duplicare questo template?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="duplicateTemplateButton">Duplica</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        self::render_scripts();
    }

}