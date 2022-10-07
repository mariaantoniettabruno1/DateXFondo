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
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/editrow',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#duplicateModal").modal('hide');
                            //chiedere a ste come fare il location.href
                            location.href = 'https://demo.mg3.srl/date/duplicazione-template-anno-precedente/';
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
        $data = new MasterTemplateRepository();
        $results_articoli = $data->getStoredArticoli();
        foreach ($results_articoli as $art){

        ?>
        <table class="table">
            <thead>
            <tr>
                <th>Fondo</th>
                <th>Anno</th>
                <th>Descrizione fondo</th>
                <th>Versione</th>
                <th>Azioni</th>
            </tr>

            </thead>
            <tbody id="dataTemplateTableBody">
            </tbody>
        </table>
            <?php
        }
            ?>
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