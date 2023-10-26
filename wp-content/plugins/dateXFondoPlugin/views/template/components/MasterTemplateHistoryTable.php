<?php

namespace dateXFondoPlugin;

class MasterTemplateHistoryTable
{
    public static function render_scripts()
    {
        ?>
        <style>
            .btn-duplicate-template {
                color: #26282f;

            }

            .btn-duplicate-template:hover {
                color: #26282f;

            }

            .btn-all-template {
                color: #26282f;
            }

            .btn-all-template:hover {
                color: #26282f;
            }

            #duplicateTemplateButton {
                border-color: #26282f;
                background-color: #26282f;
            }

            #duplicateTemplateButton:hover {
                border-color: #870e12;
                background-color: #870e12;
            }
        </style>
        <script>
            let fondo = '';
            let anno = 0;
            let descrizione = '';
            let template_name = '';
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
                                       <td >${art.template_name}</td>
                                           <td>
                <button class="btn btn-link btn-duplicate-template" data-fondo='${art.fondo}' data-anno='${art.anno}' data-desc ='${art.descrizione_fondo}' data-version='${art.version}' data-template='${art.template_name}' data-toggle="modal" data-target="#duplicateModal" data-toggle="tooltip" title="Duplica template"><i class="fa-regular fa-copy"></i></button>
                <button class="btn btn-link btn-all-template" data-fondo='${art.fondo}' data-anno='${art.anno}' data-desc ='${art.descrizione_fondo}' data-version='${art.version}' data-template='${art.template_name}'>Fondo Completo <i class="fa-solid fa-chevron-right"></i></button>
                </td>
                </tr>
                             `);

                });
                $('.btn-duplicate-template').click(function () {
                    fondo = $(this).attr('data-fondo');
                    anno = $(this).attr('data-anno');
                    descrizione = $(this).attr('data-desc');
                    version = $(this).attr('data-version');
                    template_name = $(this).attr('data-template');

                });
                $('.btn-all-template').click(function () {
                    fondo = $(this).attr('data-fondo');
                    anno = $(this).attr('data-anno');
                    descrizione = $(this).attr('data-desc');
                    version = $(this).attr('data-version');
                    template_name = $(this).attr('data-template');
                });
            }

            $(document).ready(function () {

                renderDataTable();
                $('#duplicateTemplateButton').click(function () {
                    const payload = {
                        fondo,
                        anno,
                        descrizione,
                        version,
                        name,
                        template_name
                    }
                    console.log(payload)
                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/duplicatetemplate',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#duplicateModal").modal('hide');
                            location.href = '<?= DateXFondoCommon::get_website_url() ?>/visualizza-template-fondo/?template_name=' + template_name + ' - duplicato';
                        },
                        error: function (response) {
                            console.error(response);
                            $("#duplicateModal").modal('hide');
                        }
                    });
                });
                $('.btn-all-template').click(function () {
                    location.href = '<?= DateXFondoCommon::get_website_url()?>/tabella-join-template-formula/?fondo=' + fondo + '&anno=' + anno + '&descrizione=' + descrizione + '&version=' + version + '&template_name=' + template_name;
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
                <th style="width: 100px">Template Name</th>
                <th style="width: 15rem">Azioni</th>
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