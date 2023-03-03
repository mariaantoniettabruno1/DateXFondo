<?php

use dateXFondoPlugin\DateXFondoCommon;

class ExportDataWizard
{
    public static function render_scripts()
    {
        ?>
        <style>
            .btn-select-data {
                width: 105px;
            }
            .btn-export, #exportDataButton, .btn-selected-data{
                border-color: #26282f;
                background-color: #26282f;
            }
            .btn-export:hover {
                border-color:#870e12 ;
                background-color: #870e12;
            }
            .btn-selected-data:hover {
                border-color:#870e12 ;
                background-color: #870e12;
            }
            #exportDataButton:hover {
                border-color:#870e12 ;
                background-color: #870e12;
            }
            .btn-select-data {
                border-color: #26282f;
                color: #26282f;
            }

            .btn-select-data:hover, .btn-select-data:active {
                border-color: #870e12;
                color: #870e12;
                background-color: white;
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

                template.forEach(art => {
                    $('#dataTemplateTableBody').append(`
                                 <tr>

                                       <td >${art.fondo}</td>
                                       <td >${art.anno}</td>
                                       <td >${art.descrizione_fondo}</td>
                                       <td >${art.version}</td>
                                       <td >${art.template_name}</td>
                                       <td><button class="btn btn-outline-primary btn-select-data" data-fondo='${art.fondo}' data-anno='${art.anno}' data-version='${art.version}' data-template_name='${art.template_name}'>Seleziona</button>
                                      <button class="btn btn-primary btn-selected-data" style="display:none">Selezionato</button></td>
                </tr>
                             `);

                });
                $('.btn-select-data').click(function () {
                    $(this).attr("style", "display:none");
                    $(this).next().attr("style", "display:block");
                    fondo = $(this).attr('data-fondo');
                    anno = $(this).attr('data-anno');
                    template_name = $(this).attr('data-template_name');
                    version = $(this).attr('data-version');

                })

                $('.btn-selected-data').click(function () {
                    $(this).attr("style", "display:none");
                    $(this).prev().attr("style", "display:block");
                });

            }

            $(document).ready(function () {
                renderDataTable();

                $('#exportDataButton').click(function () {

                    const cities = [];
                    $("input:checked").map(function () {
                        cities.push($(this).val());
                    }).get();

                    const payload = {
                        fondo,
                        anno,
                        template_name,
                        version,
                        cities
                    }
                    console.log(payload)

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/exportdata',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#exportModal").modal('hide');
                            $(".alert-export-success").show();
                            $(".alert-export-success").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-export-success").slideUp(500);
                            });
                        },
                        error: function (response) {
                            console.error(response);
                            $("#exportModal").modal('hide');
                            $(".alert-export-wrong").show();
                            $(".alert-export-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-export-wrong").slideUp(500);
                            });
                        }
                    });
                })


            });
        </script>
        <?php
    }

    public static function render()
    {
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-5">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Seleziona i comuni:</h5>
                            <div class="form-check" id="citiesCheckbox">
                                <input class="form-check-input" type="checkbox" name="cities" value="rubiana"
                                       id="defaultCheck1">
                                <label class="form-check-label" for="defaultCheck1">
                                    Rubiana
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="cities" value="spotorno"
                                       id="defaultCheck2">
                                <label class="form-check-label" for="defaultCheck2">
                                    Spotorno
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="cities" value="robassomero"
                                       id="defaultCheck3">
                                <label class="form-check-label" for="defaultCheck3">
                                    Robassomero
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Seleziona i template:</h5>
                            <small id="warningSaveEdit" class="form-text text-dark pb-2"><i
                                        class="fa-solid fa-triangle-exclamation text-warning"></i>Ricordati di
                                selezionare solo un template</small>
                            <table class="table">
                                <thead>
                                <tr>

                                    <th style="width: 200px">Fondo</th>
                                    <th style="width: 100px">Anno</th>
                                    <th>Descrizione fondo</th>
                                    <th style="width: 100px">Versione</th>
                                    <th style="width: 100px">Template Name</th>
                                    <th style="width: 200px"></th>
                                </tr>

                                </thead>
                                <tbody id="dataTemplateTableBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <button class="btn btn-primary btn-export" data-toggle="modal" data-target="#exportModal">Esporta
                </button>
            </div>
        </div>
        <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exportModalLabel">Esporta dati </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vuoi esportare i dati selezionati?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="exportDataButton">Esporta</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-success alert-export-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Esportazione dati andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-export-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Esportazione dati non riuscita.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        self::render_scripts();
    }
}