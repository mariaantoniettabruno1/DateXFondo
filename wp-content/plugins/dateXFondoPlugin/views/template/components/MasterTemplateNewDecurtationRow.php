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

            function clearInputDec() {
                $('#selectNewDecSezione').prop('selectedIndex', 0);
                $('#selectNewDecSottosezione').prop('selectedIndex', -1);
                $('#decNewSottosezione').val('');
                $('#decIdArticolo').val('');
                $('#decNota').val('');
                $('#typeDec').attr('checked', false);
            }

            $(document).ready(function () {
                clearInputDec();
                renderSectionFilter();
                $('#selectNewDecSezione').change(function () {
                    const section = $('#selectNewDecSezione').val();
                    if (section !== 'Seleziona Sezione') {
                        filterSubsections(section);
                    } else {
                        $('#selectNewDecSottosezione').html('');
                    }
                });
                $('.subsDecButtonGroup1').click(function () {
                    $('#selectNewDecSottosezione').show();
                    $('#decNewSottosezione').hide();
                });
                $('.subsDecButtonGroup2').click(function () {
                    $('#decNewSottosezione').attr('style', 'display:block');
                    $('#selectNewDecSottosezione').hide();
                });
                $('#addNewDecurtationButton').click(function () {
                    {
                        $("#errorIDArticoloDec").attr('style', 'display:none');

                        let id_articolo = $('#decIdArticolo').val();
                        let sezione = '';
                        if (sezione !== 'Seleziona Sezione') {
                            sezione = $('#selectNewDecSezione').val();
                        }
                        let sottosezione = '';
                        if ($('#selectNewDecSottosezione').val() != null || $('#selectNewRowSottosezione').val() !== 'Seleziona Sottosezione') {
                            sottosezione = $('#selectNewDecSottosezione').val();
                        } else if ($('#decNewSottosezione').val() != null) {
                            sottosezione = $('#decNewSottosezione').val();
                        }
                        let nota = $('#decNota').val().replaceAll("[^a-zA-Z0-9]+","");
                        let descrizione_articolo = $('#decDescrizioneArticolo').val().replaceAll("[^a-zA-Z0-9]+","");
                        let link = $('input:radio[name=typeDec]:checked').val();
                        let fondo = $('#inputFondo').val();
                        let anno = $('#inputAnno').val();
                        let descrizione_fondo = $('#inputDescrizioneFondo').val();
                        let template_name = $('#inputNomeTemplate').val();
                        let row_type = 'decurtazione';
                        console.log($('#typeDec:checked').val())
                        if (articoli.find(art => art.id_articolo === id_articolo) === undefined && sezione !== 'Seleziona Sezione' && sottosezione!=='Seleziona Sottosezione') {
                            const payload = {
                                fondo,
                                anno,
                                descrizione_fondo,
                                id_articolo,
                                nome_articolo : '',
                                sottotitolo_articolo: '',
                                sezione,
                                sottosezione,
                                descrizione_articolo,
                                nota,
                                link,
                                row_type,
                                template_name,
                                ordinamento:-1
                            }
                            console.log(payload)
                            $.ajax({
                                url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/newdec',
                                data: payload,
                                type: "POST",
                                success: function (response) {

                                    if (response["id"]) {
                                        articoli.push({...payload, id: response['id']});
                                        renderDataTable(sezione);
                                    }
                                    console.log(response);
                                    $(".alert-new-dec-success").show();
                                    $(".alert-add-dec-success").fadeTo(2000, 500).slideUp(500, function () {
                                        $(".alert-add-dec-success").slideUp(500);
                                    });
                                    clearInputDec();
                                },
                                error: function (response) {
                                    $("#addRowDecModal").modal('hide');
                                    console.error(response);
                                    $(".alert-add-dec-wrong").show();
                                    $(".alert-add-dec-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                        $(".alert-add-dec-wrong").slideUp(500);
                                    });
                                }
                            });
                        }
                        else if (articoli.find(art => art.id_articolo === id_articolo) !== undefined) {
                            $("#errorIDArticoloDec").attr('style', 'display:block');
                        } else if (sezione !== 'Seleziona Sezione') {
                            $("#errorSectionDec").attr('style', 'display:block');
                        }
                        else if(sottosezione !== 'Seleziona Sottosezione'){
                            $("#errorSubsectionDec").attr('style', 'display:block');

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
            $results_articoli = $data->visualize_template($_GET['fondo'], $_GET['anno'], $_GET['descrizione'], $_GET['version'], $_GET['template_name']);

        } else {
            $results_articoli = $data->getArticoli($_GET['template_name']);
        }
        $res = false;
        foreach ($results_articoli as $articolo){
            if($articolo['editable'] == 1){
                $res = true;
                break;
            }
        }
        if ($res) {
            ?>
            <button class="btn btn-outline-primary" id="btnDecurtazione" data-toggle="modal"
                    data-target="#addRowDecModal">Aggiungi decurtazione
            </button>
            <?php
        } else {
            ?>
            <button class="btn btn-outline-primary" id="btnDecurtazione" data-toggle="modal"
                    data-target="#addRowDecModal" disabled>Aggiungi decurtazione
            </button>
            <?php
        }
        ?>
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
                    <small id="errorSectionDec" class="form-text text-danger" style="display: none">Campo
                        Obbligatorio</small>
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
                        <small id="errorSubsectionDec" class="form-text text-danger" style="display: none">Campo
                            Obbligatorio</small>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="decNewSottosezione" style="display:none">
                    <small id="errorSubsectionDec" class="form-text text-danger" style="display: none">Campo
                        Obbligatorio</small>
                </div>
                <div class="form-group">
                    <label for="idArticolo"><b>Id Articolo: </b></label>
                    <input type="text" class="form-control" id="decIdArticolo">
                    <small id="errorIDArticoloDec" class="form-text text-danger" style="display: none">Id Articolo già
                        presente o non inserito</small>
                </div>
                <label for="inputNota"><b>Tipologia decurtazione:</b> </label>
                <div class="form-check dec-checked">
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

        <div class="alert alert-success alert-new-dec-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Nuova riga di decurtazione aggiunta!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-new-dec-wrong" role="alert"
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