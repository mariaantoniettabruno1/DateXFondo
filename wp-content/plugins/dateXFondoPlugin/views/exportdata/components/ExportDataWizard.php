<?php

use dateXFondoPlugin\DateXFondoCommon;

class ExportDataWizard
{
    public static function render_scripts()
    {
        ?>
            <style>
                .selected {
                    background-color: #870e12;
                    color: #FFF;
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

                </tr>
                             `);

                });
                $("#dataTemplateTableBody tr").click(function(){
                    $(this).addClass('selected').siblings().removeClass('selected');
                    const value=$(this).find('td:first').html();
                });
            }

            $(document).ready(function () {

                renderDataTable();
            });
        </script>
        <?php
    }

    public static function render()
    {
        ?>
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Seleziona i comuni:</h5>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
                                    <label class="form-check-label" for="defaultCheck1">
                                        Default checkbox
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="defaultCheck2">
                                    <label class="form-check-label" for="defaultCheck2">
                                        Disabled checkbox
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Seleziona i template:</h5>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th style="width: 200px">Fondo</th>
                                        <th style="width: 100px">Anno</th>
                                        <th>Descrizione fondo</th>
                                        <th style="width: 100px">Versione</th>
                                        <th style="width: 100px">Template Name</th>
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