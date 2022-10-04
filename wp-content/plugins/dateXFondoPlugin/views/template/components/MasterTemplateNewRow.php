<?php

namespace dateXFondoPlugin;

class MasterTemplateNewRow
{
    public static function render_scripts()
    {
        ?>
        <script>
            function renderSectionFilter() {
                $('#selectNewRowSezione').html('<option>Seleziona Sezione</option>');
                Object.keys(sezioni).forEach(sez => {
                    $('#selectNewRowSezione').append(`<option>${sez}</option>`);
                });
            }

            function filterSubsections(section) {
                $('#selectNewRowSottosezione').html('<option>Seleziona Sottosezione</option>');
                sezioni[section].forEach(ssez => {
                    $('#selectNewRowSottosezione').append(`<option>${ssez}</option>`);
                });
            }

            $(document).ready(function () {
                renderSectionFilter();
                $('#selectNewRowSezione').change(function () {
                    const section = $('#selectNewRowSezione').val();
                    if (section !== 'Seleziona Sezione') {
                        $('#selectNewRowSottosezione').attr('disabled', false);
                        filterSubsections(section);
                    } else {
                        $('#selectNewRowSottosezione').attr('disabled', true);
                        $('#selectNewRowSottosezione').html('');
                    }
                });
                $('#subsectionButtonGroup1').click(function () {
                    $('#selectNewRowSottosezione').show();
                    $('#newRowSottosezione').hide();
                });
                $('#subsectionButtonGroup2').click(function () {
                    $('#newRowSottosezione').show();
                    $('#selectNewRowSottosezione').hide();
                });
                $('#addNewRowButton').click(function () {
                    {
                        //inserire validazione campi obbligatori
                        let id = $('#newRowIdArticolo').val();
                        let nome = parseInt($('#newRowNomeArticolo').val());
                        let sottotitolo = $('#newRowSottotitoloArticolo').val();
                        let sezione = $('#selectNewRowSezione').val();
                        //inserire controllo su input piuttosto che su select
                        let sottosezione = $('#selectNewRowSottosezione').val();
                        let nota = $('#newRowNota').val();
                        let link = $('#newRowLink').val();
                        let ordinamento = $('#newRowOrdinamento').val();
                        let fondo = $('#inputFondo').val();
                        let anno = $('#inputAnno').val();
                        let descrizione_fondo = $('#inputDescrizioneFondo').val();
                        let row_type = 'basic';

                        const payload = {
                            fondo,
                            anno,
                            descrizione_fondo,
                            ordinamento,
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
                                if (response["id"]) {
                                    //articoli.push(response);
                                }
                                console.log(response);
                            },
                            error: function (response) {
                                console.error(response);
                            }
                        });
                    }
                });
            })
        </script>
        <?php

    }

    public static function render()
    {
        ?>
        <button class="btn btn-outline-primary" data-toggle="modal"
                data-target="#addRowModal">Aggiungi riga
        </button>

        <button class="btn btn-outline-primary">Aggiungi riga speciale</button>
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
                            <label for="selectSezione"><b>Sezione:</b></label>
                            <select class="custom-select" id="selectNewRowSezione">
                            </select>
                        </div>
                        <div class="form-group">
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
                            <input type="text" class="form-control" id="newRowSottosezione"
                                   value='' name="newRowSottosezione">
                        </div>
                        <div class="form-group">
                            <label for="ordinamento"><b>Ordinamento:</b></label>
                            <input type="text" class="form-control" id="newRowOrdinamento">
                        </div>
                        <div class="form-group">
                            <label for="inputIdArticolo"><b>Id Articolo:</b></label>
                            <input type="text" class="form-control" id="newRowIdArticolo">
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
        <?php
        self::render_scripts();
    }
}