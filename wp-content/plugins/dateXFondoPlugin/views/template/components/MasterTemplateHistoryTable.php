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
                                       <td data-fondo='${art.fondo}'>${art.fondo}</td>
                                       <td data-anno='${art.anno}'>${art.anno}</td>
                                       <td data-desc = '${art.descrizione_fondo}' >${art.descrizione_fondo}</td>
                                       <td data-version = '${art.version}'>${art.version}</td>
                                           <td>
                <button class="btn btn-primary btn-duplicate-template" data-toggle="modal" data-target="#duplicateModal">Duplica</button>
                <button class="btn btn-primary btn-visualize-template">Visualizza</button>
                </td>
                </tr>
                             `);
                });
                console.log(articoli);


                $('.btn-duplicate-template').click(function () {
                    fondo = $(this).attr('data-fondo');
                    anno = $(this).attr('data-anno');
                    descrizione = $(this).attr('data-desc');
                    version = $(this).attr('data-version');
                });
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
                    //console.log(payload)

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