<?php

namespace dateXFondoPlugin;

class MasterTemplateNewSpecialRow
{
    public static function render_scripts()
    {
        ?>
        <script>
            function renderSectionFilter() {
                $('#selectNewSpRowSezione').html('<option>Seleziona Sezione</option>');
                Object.keys(sezioni).forEach(sez => {
                    $('#selectNewSpRowSezione').append(`<option>${sez}</option>`);
                });
            }

            function filterSubsections(section) {
                $('#selectNewSpRowSottosezione').html('<option>Seleziona Sottosezione</option>');
                sezioni[section].forEach(ssez => {
                    $('#selectNewSpRowSottosezione').append(`<option>${ssez}</option>`);
                });
            }

            $(document).ready(function () {
                renderSectionFilter();
                $('#selectNewSpRowSezione').change(function () {
                    const section = $('#selectNewSpRowSezione').val();
                    if (section !== 'Seleziona Sezione') {
                        $('#selectNewSpRowSottosezione').attr('disabled', false);
                        filterSubsections(section);
                    } else {
                        $('#selectNewSpRowSottosezione').attr('disabled', true);
                        $('#selectNewSpRowSottosezione').html('');
                    }
                });
                $('#spSubsectionButtonGroup1').click(function () {
                    $('#selectNewSpRowSottosezione').show();
                    $('#newSpRowSottosezione').hide();
                });
                $('#spSubsectionButtonGroup2').click(function () {
                    $('#newSpRowSottosezione').show();
                    $('#selectNewSpRowSottosezione').hide();
                });
                $('#addNewSpecialRowButton').click(function () {
                    {
                        //inserire validazione campi obbligatori
                        let id = $('#newRowSpIdArticolo').val();
                        let nome = parseInt($('#newRowSpNomeArticolo').val());
                        let sottotitolo = $('#newRowSpSottotitoloArticolo').val();
                        let sezione = $('#selectNewSpRowSezione').val();
                        //inserire controllo su input piuttosto che su select
                        let sottosezione = $('#selectNewSpRowSottosezione').val();
                        let nota = $('#newRowSpNota').val();
                        let link = $('#newRowSpLink').val();
                        let ordinamento = $('#newRowSpOrdinamento').val();
                        let fondo = $('#inputFondo').val();
                        let anno = $('#inputAnno').val();
                        let descrizione_fondo = $('#inputDescrizioneFondo').val();
                        let row_type = 'special';

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
                            url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/newrowsp',
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
        <button class="btn btn-outline-primary" id="btnSpecialRow" data-toggle="modal"
                data-target="#addSpecialRowModal">Aggiungi riga speciale
        </button>
        <div class="modal fade" id="addSpecialRowModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><b>Nuova riga speciale:</b></h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true"></span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="selectSezione"><b>Sezione:</b></label>
                            <select class="custom-select" id="selectNewSpRowSezione">
                            </select>
                        </div>
                        <div class="form-group">
                            <br>
                            <div class="btn-group pb-3" role="group" aria-label="Basic example">
                                <button type="button" class="btn  btn-outline-primary spSubsectionButtonGroup1">
                                    Seleziona Sottosezione
                                </button>
                                <button type="button" class="btn btn-outline-primary spSubsectionButtonGroup2">
                                    Nuova Sottosezione
                                </button>
                            </div>
                            <div class="form-group">
                                <select class="custom-select" id="selectNewSpRowSottosezione">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="newSpRowSottosezione" style="display:none">
                        </div>
                        <div class="form-group">
                            <label for="ordinamento"><b>Ordinamento:</b></label>
                            <input type="text" class="form-control" id="newRowSpOrdinamento">
                        </div>
                        <div class="form-group">
                            <label for="inputIdArticolo"><b>Id Articolo:</b></label>
                            <input type="text" class="form-control" id="newRowSpIdArticolo">
                        </div>
                        <div class="form-group">
                            <label for="idNomeArticolo"><b>Articolo:</b> </label>
                            <input type="text" class="form-control" id="newRowSpNomeArticolo">
                        </div>
                        <div class="form-group">
                            <label for="idSottotitoloArticolo"><b>Sottotitolo Articolo: </b></label>
                            <textarea class="form-control"
                                      id="newRowSpSottotitoloArticolo"></textarea>

                        </div>
                        <div class="form-group">
                            <label for="idDescrizioneArticolo"><b>Descrizione Articolo: </b></label>
                            <textarea class="form-control"
                                      id="newRowSpDescrizioneArticolo"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="idNota"><b>Nota</b></label>
                            <textarea class="form-control"
                                      id="newRowSpNota"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="idLinkAssociato"><b>Link associato: </b></label>
                            <input type="text" class="form-control" id="newRowSpLink">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="addNewSpecialRowButton">Aggiungi Riga</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        self::render_scripts();

    }
}