<?php

namespace dateXFondoPlugin;

class MasterTemplateToActiveRow
{
    public static function render_scripts()
    {
        ?>
            <style>
                .btn-active-row, #activeRowButton{
                    border-color: #26282f;
                    background-color: #26282f;
                }
                .btn-active-row:hover. #activeRowButton:hover{
                    border-color:#870e12 ;
                    background-color: #870e12;
                }
            </style>
        <script>
            let id = 0;
            let filteredArticoli = articoli;
            let fondo = '';
            let anno = 0;
            let descrizione = '';
            let version = 0;


            function renderDataTable(fondo, anno) {
                $('#dataTemplateTableBody').html('');
                if (fondo) {
                    filteredArticoli = filteredArticoli.filter(art => art.anno === anno)
                }
                if (anno) {
                    filteredArticoli = filteredArticoli.filter(art => art.fondo === fondo)
                }
                filteredArticoli.forEach(art => {
                    $('#dataTemplateTableBody').append(`
                                 <tr>
                                       <td>${art.sezione}</td>
                                       <td>${art.sottosezione}</td>
                                       <td>${art.id_articolo}</td>
                                       <td>${art.nome_articolo}</td>
                                       <td>${art.sottotitolo_articolo}</td>
                                       <td></td>
                                       <td>${art.nota}</td>
                                       <td>${art.link}</td>
                                           <td>
                <button class="btn btn-primary btn-active-row" data-toggle="modal" data-target="#activeModal" data-id='${art.id}'>Attiva</button>
                </td>
                </tr>
                             `);
                });


                $('.btn-active-row').click(function () {
                    id = $(this).attr('data-id');
                    renderEditData(id);
                });
            }

            function renderEditData(id) {
                let articolo = articoli;
                articolo = articolo.filter(art => art.id === id)
                fondo = articolo[0].fondo;
                anno = articolo[0].anno;
                descrizione = articolo[0].descrizione_fondo;
                version = articolo[0].version;
            }

            function renderFondoFilter() {
                $('#inputSelectFondo').html('<option>Seleziona Fondo</option>');
                Object.keys(fondi).forEach(fondo => {
                    $('#inputSelectFondo').append(`<option>${fondo}</option>`);
                });
            }

            function renderAnnoFilter() {
                $('#inputSelectAnno').html('<option>Seleziona Anno</option>');
                Object.keys(years).forEach(year => {
                    $('#inputSelectAnno').append(`<option>${year}</option>`);
                });
            }

            function filterAnnoFondo() {

                let anno = $('#inputSelectAnno').val();
                if (anno === 'Seleziona Anno') {
                    anno = null;
                }
                let fondo = $('#inputSelectFondo').val();
                if (fondo === 'Seleziona Fondo') {
                    fondo = null;
                }
                renderDataTable(fondo, anno);
            }

            $(document).ready(function () {

                renderDataTable();
                renderAnnoFilter();
                renderFondoFilter();

                $('#inputSelectAnno').change(function () {
                    filterAnnoFondo();
                });
                $('#inputSelectFondo').change(function () {
                    filterAnnoFondo();
                });
                $('#activeRowButton').click(function () {
                    const payload = {
                        id,
                        fondo,
                        anno,
                        descrizione,
                        version
                    }
                    console.log(payload)

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/activerow',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#activeModal").modal('hide');
                            filteredArticoli = filteredArticoli.filter(art => art.id !== id)
                            filterAnnoFondo();
                            $(".alert-active-row-success").show();
                            $(".alert-active-row-success").fadeTo(2000, 500).slideUp(500, function(){
                                $(".alert-active-row-success").slideUp(500);
                            });

                        },
                        error: function (response) {
                            console.error(response);
                            $("#activeModal").modal('hide');
                            $(".alert-active-row-wrong").show();
                            $(".alert-active-row-wrong").fadeTo(2000, 500).slideUp(500, function(){
                                $(".alert-active-row-wrong").slideUp(500);
                            });
                        }
                    });
                });
            });
        </script>
        <?php
    }

    public static function render()
    { ?>
        <div class="col-6 pb-3">
            <select class="custom-select" id="inputSelectFondo">
            </select>
        </div>
        <div class="col-6 pb-3">
            <select class="custom-select" id="inputSelectAnno">
            </select>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Sezione</th>
                <th>Sottosezione</th>
                <th>Id Articolo</th>
                <th>Nome Articolo</th>
                <th>Sottotitolo Articolo</th>
                <th>Descrizione Articolo</th>
                <th>Nota</th>
                <th>Link</th>
                <th>Azioni</th>
            </tr>

            </thead>
            <?php $data = new MasterTemplateRepository();
            $results_articoli = $data->getStoredArticoli();
            $arrLength = count($results_articoli);
            for ($results_articoli = 0; $results_articoli <= $arrLength; $results_articoli++) { ?>
                <tbody id="dataTemplateTableBody">
                </tbody>
                <?php
            }

            ?>
        </table>

        <div class="modal fade" id="activeModal" tabindex="-1" role="dialog" aria-labelledby="activeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="activeModalLabel">Attiva riga selezionata </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vuoi attivare questa riga?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="activeRowButton">Attiva</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-success alert-active-row-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Attivazione riga eseguita correttamente!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-active-row-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Attivazione riga non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        self::render_scripts();
    }


}