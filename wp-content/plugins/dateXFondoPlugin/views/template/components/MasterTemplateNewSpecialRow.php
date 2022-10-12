<?php

namespace dateXFondoPlugin;

class MasterTemplateNewSpecialRow
{
    public static function render_scripts()
    {
        ?>
        <script>
            function renderSectionFilter() {
                $('#selectSpRowSezione').html('<option>Seleziona Sezione</option>');
                Object.keys(sezioni).forEach(sez => {
                    $('#selectSpRowSezione').append(`<option>${sez}</option>`);
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

                $('#selectSpRowSezione').change(function () {
                    const section = $('#selectSpRowSezione').val();
                    if (section !== 'Seleziona Sezione') {
                        filterSubsections(section);
                    } else {
                        $('#selectNewSpRowSottosezione').html('');
                    }
                });
                $('.subsSpButtonGroup1').click(function () {
                    $('#selectNewSpRowSottosezione').show();
                    $('#spNewSottosezione').attr('style', 'display:none');
                });
                $('.subsSpButtonGroup2').click(function () {
                    $('#spNewSottosezione').attr('style', 'display:block');
                    $('#selectNewSpRowSottosezione').hide();
                });
                $('#addNewSpecialRowButton').click(function () {
                    {
                        //inserire validazione campi obbligatori
                        let id = $('#newRowSpIdArticolo').val();
                        let nome = parseInt($('#newRowSpNomeArticolo').val());
                        let sottotitolo = $('#newRowSpSottotitoloArticolo').val();
                        let sezione = '';
                        if (sezione !== 'Seleziona Sezione') {
                            sezione = $('#selectNewSpRowSezione').val();
                        }
                        let sottosezione = '';
                        if ($('#selectNewSpRowSottosezione').val() != null || $('#selectNewSpRowSottosezione').val() !== 'Seleziona Sottosezione') {
                            sottosezione = $('#selectNewSpRowSottosezione').val();
                        } else if ($('#spNewSottosezione').val() != null) {
                            sottosezione = $('#spNewSottosezione').val();
                        }
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
                                    articoli.push(response);
                                    $("#addSpecialRowModal").modal('hide');
                                    renderEditDataTable(payload);
                                }
                                console.log(response);
                            },
                            error: function (response) {
                                console.error(response);
                                $("#addSpecialRowModal").modal('hide');

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
        $data = new MasterTemplateRepository();
        if (isset($_GET['fondo']) || isset($_GET['anno']) || isset($_GET['descrizione']) || isset($_GET['version'])) {
            $results_articoli = $data->visualize_template($_GET['fondo'], $_GET['anno'], $_GET['descrizione'], $_GET['version']);

        } else {
            $results_articoli = $data->getArticoli();
        }        if ($results_articoli[0]['editable'] == '1') {
            ?>
            <button class="btn btn-outline-primary" id="btnSpecialRow" data-toggle="modal"
                    data-target="#addSpecialRowModal">Aggiungi riga speciale
            </button>
            <?php
        } else {
            ?>
            <button class="btn btn-outline-primary" id="btnSpecialRow" data-toggle="modal"
                    data-target="#addSpecialRowModal" disabled>Aggiungi riga speciale
            </button>
            <?php
        }
        ?>
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
                            <label for="selectSezione"><b>Sezione: </b></label>
                            <select class="custom-select" id="selectSpRowSezione">
                            </select>
                        </div>
                        <div class="form-group" id="divSelectSpSottosezione">
                            <br>
                            <div class="btn-group pb-3" role="group" aria-label="Basic example">
                                <button type="button" class="btn  btn-outline-primary subsSpButtonGroup1"
                                >Seleziona Sottosezione
                                </button>
                                <button type="button" class="btn btn-outline-primary subsSpButtonGroup2"
                                >Nuova Sottosezione
                                </button>
                            </div>
                            <div class="form-group">
                                <select class="custom-select" id="selectNewSpRowSottosezione">
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="spNewSottosezione" style="display:none">
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