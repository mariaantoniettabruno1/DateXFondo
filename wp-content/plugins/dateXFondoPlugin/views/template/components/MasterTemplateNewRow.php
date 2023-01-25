<?php

namespace dateXFondoPlugin;

class MasterTemplateNewRow
{
    public static function render_scripts()
    {
        ?>

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
                $('#newRowNomeArticolo').val('');
                $('#newRowDescrizioneArticolo').val('');
                $('#newRowSottotitoloArticolo').val('');
                $('#newRowNota').val('');
                $('#newRowLink').val('');
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
                        let id_articolo = $('#newRowIdArticolo').val();
                        let nome_articolo = $('#newRowNomeArticolo').val().replaceAll("[^a-zA-Z0-9]+","");
                        let sottotitolo_articolo = $('#newRowSottotitoloArticolo').val().replaceAll("[^a-zA-Z0-9]+","");
                        let descrizione_articolo = $('#newRowDescrizioneArticolo').val().replaceAll("[^a-zA-Z0-9]+","");
                        let sezione = '';
                        if (sezione !== 'Seleziona Sezione') {
                            sezione = $('#selectNewRowSezione').val();
                        }
                        let sottosezione = '';
                        if ($('#selectNewRowSottosezione').val() != null && $('#selectNewRowSottosezione').val() !== 'Seleziona Sottosezione') {
                            sottosezione = $('#selectNewRowSottosezione').val();
                        } else if ($('#newRowSottosezione').val() != null) {
                            sottosezione = $('#newRowSottosezione').val();
                        }
                        let nota = $('#newRowNota').val().replaceAll("[^a-zA-Z0-9]+","");
                        let link = $('#newRowLink').val();
                        let fondo = $('#inputFondo').val();
                        let anno = $('#inputAnno').val();
                        let descrizione_fondo = $('#inputDescrizioneFondo').val();
                        let template_name = $('#inputNomeTemplate').val();
                        let row_type = 'basic';
                        if (articoli.find(art => art.id_articolo === id_articolo) === undefined && sezione !== 'Seleziona Sezione' && sottosezione!=='Seleziona Sottosezione') {
                            const payload = {
                                fondo,
                                anno,
                                descrizione_fondo,
                                id_articolo,
                                nome_articolo,
                                sottotitolo_articolo,
                                descrizione_articolo,
                                sezione,
                                sottosezione,
                                nota,
                                link,
                                row_type,
                                template_name,
                                ordinamento: -1
                            }
                            console.log(payload)
                            $.ajax({
                                url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/newrow',
                                data: payload,
                                type: "POST",
                                success: function (response) {
                                    $("#addRowModal").modal('hide');
                                    articoli.push({...payload, id: response['id']});
                                    renderDataTable(sezione);
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
                        } else if (articoli.find(art => art.id_articolo === id_articolo) !== undefined) {
                            $("#errorIDArticolo").attr('style', 'display:block');
                        } else if (sezione !== 'Seleziona Sezione') {
                            $("#errorSection").attr('style', 'display:block');
                        }
                        else if(sottosezione !== 'Seleziona Sottosezione'){
                            $("#errorSubsection").attr('style', 'display:block');

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
        $results = $data->getAllArticles();
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
                            <small id="errorSection" class="form-text text-danger" style="display: none">Campo
                                Obbligatorio</small>
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
                                <small id="errorSubsection" class="form-text text-danger" style="display: none">Campo
                                    Obbligatorio</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="newRowSottosezione" style="display:none">
                            <small id="errorSubsection" class="form-text text-danger" style="display: none">Campo
                                Obbligatorio</small>
                        </div>
                        <div class="form-group">
                            <label for="inputIdArticolo"><b>Id Articolo:</b></label>
                            <input type="text" class="form-control" id="newRowIdArticolo">
                            <small id="errorIDArticolo" class="form-text text-danger" style="display: none">Id Articolo
                                già presente o non iserito</small>
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

                            <select name="newRowLink" id="newRowLink">
                                <?php
                                foreach ($results as $res) {
                                    ?>
                                    <option><?= $res[0] ?></option>

                                <?php }
                                ?>
                            </select>

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