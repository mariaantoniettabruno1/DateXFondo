<?php

namespace dateXFondoPlugin;

class MasterTemplateNewDecurtationRow
{
    public static function render_scripts()
    {
        ?>
        <script>
            function renderSectionFilter() {
                $('#selectNewDecSezione').html('<option>Seleziona Sezione</option>');
                Object.keys(sezioni).forEach(sez => {
                    $('#selectNewDecSezione').append(`<option>${sez}</option>`);
                });
            }

            function filterSubsections(section) {
                $('#selectNewDecSottosezione').html('<option>Seleziona Sottosezione</option>');
                sezioni[section].forEach(ssez => {
                    $('#selectNewDecSottosezione').append(`<option>${ssez}</option>`);
                });
            }

            $(document).ready(function () {
                renderSectionFilter();
                $('#selectNewDecSezione').change(function () {
                    const section = $('#selectNewDecSezione').val();
                    if (section !== 'Seleziona Sezione') {
                        $('#selectNewDecSottosezione').attr('disabled', false);
                        filterSubsections(section);
                    } else {
                        $('#selectNewDecSottosezione').attr('disabled', true);
                        $('#selectNewDecSottosezione').html('');
                    }
                });
                $('#subsDecButtonGroup1').click(function () {
                    $('#selectNewDecSottosezione').show();
                    $('#decNewSottosezione').hide();
                });
                $('#subsDecButtonGroup2').click(function () {
                    $('#decNewSottosezione').show();
                    $('#selectNewDecSottosezione').hide();
                });
                $('#addNewDecurtationButton').click(function () {
                    {
                        //inserire validazione campi obbligatori
                        //inserire anche il campo descrizione articolo
                        let id = $('#decIdArticolo').val();
                        let sezione = $('#selectNewDecSezione').val();
                        //inserire controllo su input piuttosto che su select
                        let sottosezione = $('#selectNewDecSottosezione').val();
                        let nota = $('#decNota').val();
                        let link = $('#typeDec:checked').val();
                        let ordinamento = $('#decOrdinamento').val();
                        let fondo = $('#inputFondo').val();
                        let anno = $('#inputAnno').val();
                        let descrizione_fondo = $('#inputDescrizioneFondo').val();
                        let row_type = 'decurtazione';

                        const payload = {
                            fondo,
                            anno,
                            descrizione_fondo,
                            ordinamento,
                            id,
                            sezione,
                            sottosezione,
                            nota,
                            link,
                            row_type
                        }
                        console.log(payload)
                        $.ajax({
                            url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/newdec',
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
        <button class="btn btn-outline-primary" id="btnDecurtazione" data-toggle="modal"
                data-target="#addRowDecModal">Aggiungi decurtazione
        </button>
        <div class="modal fade" id="addRowDecModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Nuova decurtazione:</b></h5>
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group">
                    <label for="selectSezione"><b>Sezione: </b></label>
                    <select class="custom-select" id="selectNewDecSezione">
                    </select>
                </div>
                <div class="form-group" id="divSelectSottosezione">
                    <br>
                    <div class="btn-group pb-3" role="group" aria-label="Basic example">
                        <button type="button" class="btn  btn-outline-primary subsDecButtonGroup1"
                        >Seleziona Sottosezione
                        </button>
                        <button type="button" class="btn btn-outline-primary subsDecButtonGroup2"
                                >Nuova Sottosezione
                        </button>
                    </div>
                    <div class="form-group">
                        <select class="custom-select" id="selectNewDecSottosezione">
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="decNewSottosezione">
                </div>
                <div class="form-group">
                    <label for="ordinamento"><b>Ordinamento: </b></label>
                    <input type="text" class="form-control" id="decOrdinamento"
                           value='' name="decOrdinamento">
                </div>
                <div class="form-group">
                    <label for="idArticolo"><b>Id Articolo: </b></label>
                    <input type="text" class="form-control" id="decIdArticolo">
                </div>
                <label for="inputNota"><b>Tipologia decurtazione:</b> </label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="typeDec" id="percentualeSelected"
                           value="%">
                    <label class="form-check-label" for="percentualeSelected">
                        %
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="typeDec" id="valAbsSelected"
                           value="ValoreAssoluto">
                    <label class="form-check-label" for="valAbsSelected">
                        Valore Assoluto
                    </label>
                </div>
                <br>
                <div class="form-group">
                    <label for="decDescrizioneArticolo"><b>Descrizione:</b></label>
                    <textarea class="form-control"
                              id="decDescrizioneArticolo"
                              name="decDescrizioneArticolo"></textarea>
                </div>
                <div class="form-group">
                    <label for="decNota"><b>Nota:</b></label>
                    <textarea class="form-control"
                              id="decNota"
                              name="decNota"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" id="addNewDecurtationButton">Aggiungi riga</button>
            </div>
        </div>
        <?php
        self::render_scripts();

    }

}