<?php

namespace dateXFondoPlugin;

class MasterTemplateNewRow
{
    public static function render_scripts()
    {
        ?>
        <style>
            #idAddRow {
                border-color: #26282f;
                color: #26282f;
            }

            #idAddRow:hover, #idAddRow:active {
                border-color: #870e12;
                color: #870e12;
                background-color: white;
            }

            #addNewRowButton {
                border-color: #26282f;
                background-color: #26282f;

            }

            #addNewRowButton:hover {
                border-color: #870e12;
                background-color: #870e12;
            }

            .subsectionButtonGroup1, .subsectionButtonGroup2 {
                border-color: #26282f;
                color: #26282f;
                background-color: white;

            }

            .subsectionButtonGroup1:active, .subsectionButtonGroup2:active, .subsectionButtonGroup1:hover, .subsectionButtonGroup2:hover {
                border-color: #26282f;
                color: #26282f;
                background-color: white;
            }
        </style>
        <script>


            function renderSectionFilterRow() {
                $('#selectNewRowSezione').html('<option>Seleziona Sezione</option>');
                Object.keys(sezioni).forEach(sez => {
                    $('#selectNewRowSezione').append(`<option>${sez}</option>`);
                });
            }

            function filterSubsectionsRow(section) {
                $('#selectNewRowSottosezione').html('<option>Seleziona Sottosezione</option>');
                sezioni[section].forEach(ssez => {
                    $('#selectNewRowSottosezione').append(`<option>${ssez}</option>`);
                });
            }

            function clearInputRow() {
                $('#selectNewRowSezione').prop('selectedIndex', 0);
                $('#selectNewRowSottosezione').prop('selectedIndex', -1);
                $('#newRowSottosezione').val('');
                $('#newRowIdArticolo').val('');
                $('#newRowNomeArticolo').val();
                $('#newRowSottotitoloArticolo').val('');
                $('#newRowNota').val('');
                $('#newRowLink').val('');
                $('#inputFondo').val('');
                $('#inputAnno').val('');
                $('#inputDescrizioneFondo').val('');
            }

            $(document).ready(function () {
                clearInputRow();
                renderSectionFilterRow();
                $('#selectNewRowSezione').change(function () {
                    const section = $('#selectNewRowSezione').val();
                    if (section !== 'Seleziona Sezione') {
                        filterSubsectionsRow(section);
                    } else {
                        $('#selectNewRowSottosezione').html('');
                    }
                });
                $('.subsectionButtonGroup1').click(function () {
                    $('#selectNewRowSottosezione').show();
                    $('#newRowSottosezione').attr('style', 'display:none');
                });
                $('.subsectionButtonGroup2').click(function () {
                    $('#newRowSottosezione').attr('style', 'display:block');
                    $('#selectNewRowSottosezione').hide();
                });
                $('#addNewRowButton').click(function () {
                    {
                        $("#errorIDArticolo").attr('style', 'display:none');
                        <?php //inserire validazione campi obbligatori ?>

                        let id = $('#newRowIdArticolo').val();
                        let nome = $('#newRowNomeArticolo').val();
                        let sottotitolo = $('#newRowSottotitoloArticolo').val();
                        let sezione = '';
                        if (sezione !== 'Seleziona Sezione') {
                            sezione = $('#selectNewRowSezione').val();
                        }
                        let sottosezione = '';
                        if ($('#selectNewRowSottosezione').val() != null || $('#selectNewRowSottosezione').val() !== 'Seleziona Sottosezione') {
                            sottosezione = $('#selectNewRowSottosezione').val();
                        } else if ($('#newRowSottosezione').val() != null) {
                            sottosezione = $('#newRowSottosezione').val();
                        }
                        let nota = $('#newRowNota').val();
                        let link = $('#newRowLink').val();
                        let fondo = $('#inputFondo').val();
                        let anno = $('#inputAnno').val();
                        let descrizione_fondo = $('#inputDescrizioneFondo').val();
                        let row_type = 'basic';
                        if (articoli.find(art => art.id_articolo === id) === undefined) {
                            const payload = {
                                fondo,
                                anno,
                                descrizione_fondo,
                                id,
                                nome,
                                sottotitolo,
                                sezione,
                                sottosezione,
                                nota,
                                link,
                                row_type
                            }
                            console.log(payload)
                            $.ajax({
                                url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/newrow',
                                data: payload,
                                type: "POST",
                                success: function (response) {
                                    $("#addRowModal").modal('hide');
                                    renderEditDataTable(payload);
                                    console.log(response);
                                    $(".alert-new-row-success").show();
                                    $(".alert-new-row-success").fadeTo(2000, 500).slideUp(500, function () {
                                        $(".alert-new-row-success").slideUp(500);
                                    });
                                    clearInputRow();
                                },
                                error: function (response) {
                                    $("#addRowModal").modal('hide');
                                    console.error(response);
                                    $(".alert-new-row-wrong").show();
                                    $(".alert-new-row-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                        $(".alert-new-row-wrong").slideUp(500);
                                    });
                                }
                            });
                        } else {
                            $("#errorIDArticolo").attr('style', 'display:block');
                        }


                    }
                });
            })
        </script>
        <?php

    }

    public static function render()
    {
        $data = new MasterTemplateRepository();
        if (isset($_GET['fondo']) || isset($_GET['anno']) || isset($_GET['descrizione']) || isset($_GET['version'])) {
            $results_articoli = $data->visualize_template($_GET['fondo'], $_GET['anno'], $_GET['descrizione'], $_GET['version']);

        } else {
            $results_articoli = $data->getArticoli();
        }
        if ($results_articoli[0]['editable'] == '1') {
            ?>
            <button class="btn btn-outline-primary" data-toggle="modal"
                    data-target="#addRowModal" id="idAddRow">Aggiungi riga
            </button>
            <?php
        } else {
            ?>
            <button class="btn btn-outline-primary" data-toggle="modal"
                    data-target="#addRowModal" id="idAddRow" disabled>Aggiungi riga
            </button>
            <?php
        }
        ?>
        <div class="modal fade" id="addRowModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Nuova riga:</b></h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="selectRowSezione"><b>Sezione:</b></label>
                            <select class="custom-select" id="selectNewRowSezione">
                            </select>
                        </div>
                        <div class="form-group" id="divSelectNewRowSottosezione">
                            <br>
                            <div class="btn-group pb-3" role="group" aria-label="Basic example">
                                <button type="button" class="btn  btn-outline-primary subsectionButtonGroup1">
                                    Seleziona Sottosezione
                                </button>
                                <button type="button" class="btn btn-outline-primary subsectionButtonGroup2">
                                    Nuova Sottosezione
                                </button>
                            </div>
                            <div class="form-group">
                                <select class="custom-select" id="selectNewRowSottosezione">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="newRowSottosezione" style="display:none">
                        </div>
                        <div class="form-group">
                            <label for="inputIdArticolo"><b>Id Articolo:</b></label>
                            <input type="text" class="form-control" id="newRowIdArticolo">
                            <small id="errorIDArticolo" class="form-text text-danger" style="display: none">Id Articolo
                                già presente</small>
                        </div>
                        <div class="form-group">
                            <label for="inputNomeArticolo"><b>Articolo:</b> </label>
                            <input type="text" class="form-control" id="newRowNomeArticolo">
                        </div>
                        <div class="form-group">
                            <label for="idSottotitoloArticolo"><b>Sottotitolo Articolo: </b></label>
                            <textarea class="form-control"
                                      id="newRowSottotitoloArticolo"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="idDescrizioneArticolo"><b>Descrizione Articolo: </b></label>
                            <textarea class="form-control"
                                      id="newRowDescrizioneArticolo"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="idNota"><b>Nota</b></label>
                            <textarea class="form-control"
                                      id="newRowNota"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="idLinkAssociato"><b>Link associato: </b></label>
                            <input type="text" class="form-control" id="newRowLink">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="addNewRowButton">Aggiungi riga</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-success alert-new-row-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Nuova riga aggiunta correttamente!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-new-row-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Aggiunta nuova riga non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        self::render_scripts();
    }
}