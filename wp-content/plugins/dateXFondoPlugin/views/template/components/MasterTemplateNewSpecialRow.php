<?php

namespace dateXFondoPlugin;

class MasterTemplateNewSpecialRow
{
    public static function render_scripts()
    {
        ?>
        <style>
            #btnSpecialRow {
                border-color: #26282f;
                color: #26282f;
            }

            #btnSpecialRow:hover {
                border-color: #870e12;
                color: #870e12;
                background-color: white;
            }

            #addNewSpecialRowButton {

                border-color: #26282f;
                background-color: #26282f;

            }

            #addNewSpecialRowButton:hover {
                border-color: #870e12;
                background-color: #870e12;
            }

            .subsSpButtonGroup1, .subsSpButtonGroup2 {
                border-color: #26282f;
                color: #26282f;
                background-color: white;

            }

            .subsSpButtonGroup1:active, .subsSpButtonGroup2:active, .subsSpButtonGroup2:hover, .subsSpButtonGroup2:hover {
                border-color: #26282f;
                color: #26282f;
                background-color: white;
            }
        </style>
        <script>
            function renderSectionFilterSpRow() {
                $('#selectSpRowSezione').html('<option>Seleziona Sezione</option>');
                Object.keys(sezioni).forEach(sez => {
                    $('#selectSpRowSezione').append(`<option>${sez}</option>`);
                });
            }

            function filterSubsectionsSpRow(section) {
                $('#selectNewSpRowSottosezione').html('<option>Seleziona Sottosezione</option>');
                sezioni[section].forEach(ssez => {
                    $('#selectNewSpRowSottosezione').append(`<option>${ssez}</option>`);
                });
            }

            function clearInputSpecialRow() {
                $('#selectSpRowSezione').prop('selectedIndex', 0);
                $('#selectNewSpRowSottosezione').prop('selectedIndex', -1);
                $('#spNewSottosezione').val('');
                $('#newRowSpIdArticolo').val('');
                $('#newRowSpNomeArticolo').val('');
                $('#newRowSpDescrizioneArticolo').val('');
                $('#newRowSpSottotitoloArticolo').val('');
                $('#newRowSpNota').val('');
                $('#newRowSpLink').val('');
            }

            $(document).ready(function () {
                clearInputSpecialRow();
                renderSectionFilterSpRow();

                $('#selectSpRowSezione').change(function () {
                    const section = $('#selectSpRowSezione').val();
                    if (section !== 'Seleziona Sezione') {
                        filterSubsectionsSpRow(section);
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
                        let id_articolo = $('#newRowSpIdArticolo').val();
                        let sezione = '';
                        if (sezione !== 'Seleziona Sezione') {
                            sezione = $('#selectSpRowSezione').val();
                        }
                        let sottosezione = '';
                        if ($('#selectNewSpRowSottosezione').val() != null || $('#selectNewSpRowSottosezione').val() !== 'Seleziona Sottosezione') {
                            sottosezione = $('#selectNewSpRowSottosezione').val();
                        } else if ($('#spNewSottosezione').val() != null) {
                            sottosezione = $('#spNewSottosezione').val();
                        }
                        if (articoli.find(art => art.id_articolo === id_articolo) !== undefined) {
                            $("#errorIDArticoloSp").attr('style', 'display:block');
                            return;
                        }
                        if (sezione !== 'Seleziona Sezione') {
                            $("#errorSectionSp").attr('style', 'display:block');
                            return;
                        }
                        if (sottosezione !== 'Seleziona Sottosezione') {
                            $("#errorSubsectionSp").attr('style', 'display:block');
                            return;
                        }
                        let nome_articolo = $('#newRowSpNomeArticolo').val();
                        let sottotitolo_articolo = $('#newRowSpSottotitoloArticolo').val();
                        let descrizione_articolo = $('#newRowSpDescrizioneArticolo').val();
                        let nota = $('#newRowSpNota').val();
                        let link = $('#newRowSpLink').val();
                        let fondo = $('#inputFondo').val();
                        let anno = $('#inputAnno').val();
                        let descrizione_fondo = $('#inputDescrizioneFondo').val();
                        let template_name = $('#inputNomeTemplate').val();
                        let row_type = 'special';


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
                            url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/newrowsp',
                            data: payload,
                            type: "POST",
                            success: function (response) {
                                if (response["id"]) {
                                    articoli.push({...payload, id: response['id']});
                                    $("#addSpecialRowModal").modal('hide');
                                    renderDataTable(sezione);
                                }
                                console.log(response);
                                $(".alert-sp-row-success").show();
                                $(".alert-sp-row-success").fadeTo(2000, 500).slideUp(500, function () {
                                    $(".alert-sp-row-success").slideUp(500);
                                });
                            },
                            error: function (response) {
                                console.error(response);
                                $("#addSpecialRowModal").modal('hide');
                                $(".alert-sp-row-wrong").show();
                                $(".alert-sp-row-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                    $(".alert-sp-row-wrong").slideUp(500);
                                });

                            }
                        });

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
            $results_articoli = $data->visualize_template($_GET['fondo'], $_GET['anno'], $_GET['descrizione'], $_GET['version']);

        } else {
            $results_articoli = $data->getArticoli($_GET['template_name']);
        }
        if ($results_articoli[0]['editable'] == '1') {
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
                            <small id="errorSectionSp" class="form-text text-danger" style="display: none">Campo
                                Obbligatorio</small>
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
                                <small id="errorSubsectionSp" class="form-text text-danger" style="display: none">Campo
                                    Obbligatorio</small>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" id="spNewSottosezione" style="display:none">
                            <small id="errorSubsectionSp" class="form-text text-danger" style="display: none">Campo
                                Obbligatorio</small>
                        </div>
                        <div class="form-group">
                            <label for="inputIdArticolo"><b>Id Articolo:</b></label>
                            <input type="text" class="form-control" id="newRowSpIdArticolo">
                            <small id="errorIDArticoloSp" class="form-text text-danger" style="display: none">Id
                                Articolo già presente o non inserito</small>
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
                            <select name="newRowSpLink" id="newRowSpLink">
                                <?php
                                foreach ($results as $res) {
                                    ?>
                                    <option><?= $res[0] ?></option>

                                <?php }
                                ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" id="addNewSpecialRowButton">Aggiungi Riga</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="alert alert-success alert-sp-row-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Nuova riga aggiunta correttamente!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-sp-row-wrong" role="alert"
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