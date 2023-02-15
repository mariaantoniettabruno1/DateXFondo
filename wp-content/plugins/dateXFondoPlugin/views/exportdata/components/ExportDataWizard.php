<?php

use dateXFondoPlugin\DateXFondoCommon;

class ExportDataWizard
{
    public static function render_scripts()
    {
        ?>
            <style>
                .btn-select-data{
                    width: 105px;
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
                    console.log(fondo)
                    console.log(anno)
                    console.log(template_name)
                    console.log(version)


                })

                $('.btn-selected-data').click(function () {
                    $(this).attr("style", "display:none");
                    $(this).prev().attr("style", "display:block");
                });

            }

            $(document).ready(function () {
                renderDataTable();
                const cities = [];
                $("input:checked").map(function(){
                    cities.push($(this).val());
                }).get();
        

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
                                    <input class="form-check-input" type="checkbox" name="cities" value="Torino" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                        Torino
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="cities" value="Ivrea" id="defaultCheck2">
                                    <label class="form-check-label" for="defaultCheck2">
                                       Ivrea
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
                                            class="fa-solid fa-triangle-exclamation text-warning"></i>Ricordati di selezionare solo un template</small>
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
                    <button class="btn btn-primary">Esporta</button>
                </div>
            </div>

        <?php
        self::render_scripts();
    }
}