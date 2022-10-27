<?php

namespace dateXFondoPlugin;

class MasterTemplateTable
{
    public static function render_scripts()
    {
        ?>

        <script>

            let id = 0;
            let filteredArticoli = articoli;
            let heredity = null;


            function renderDataTable(section, subsection) {
                let index = Object.keys(sezioni).indexOf(section);
                $('#dataTemplateTableBody' + index).html('');
                filteredArticoli = articoli;
                filteredArticoli = filteredArticoli.filter(art => art.sezione === section)
                if (subsection)
                    filteredArticoli = filteredArticoli.filter(art => art.sottosezione === subsection)
                let button = '';
                let delete_button = '';
                let heredity = '';
                let nota = '';
                let id_articolo = '';
                let descrizione = '';
                let sottotitolo = '';
                let link = '';
                let nome_articolo = '';


                filteredArticoli.forEach(art => {

                    nota = art.nota ?? '';
                    id_articolo = art.id_articolo ?? '';
                    descrizione = art.descrizione_articolo ?? '';
                    sottotitolo = art.sottotitolo_articolo ?? '';
                    link = art.link ?? '';
                    nome_articolo = art.nome_articolo ?? '';

                    if (art.row_type === 'decurtazione') {
                        if (Number(art.editable) === 0) {
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal" disabled><i class="fa-solid fa-trash"></i></button>`;
                            button = ` <button class="btn btn-link btn-edit-row-dec" data-id='${art.id}' data-toggle="modal" data-target="#editDecModal" disabled><i class="fa-solid fa-pen"></i></button>`;

                        } else {
                            button = ` <button class="btn btn-link btn-edit-row-dec" data-id='${art.id}' data-toggle="modal" data-target="#editDecModal"><i class="fa-solid fa-pen"></i></button>`;
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>`;
                        }
                    } else {
                        if (Number(art.editable) === 0) {
                            button = ` <button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editModal" disabled><i class="fa-solid fa-pen"></i></button>`;
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal" disabled><i class="fa-solid fa-trash"></i></button>`;
                        } else {
                            button = ` <button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editModal"><i class="fa-solid fa-pen"></i></button>`;
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>`;
                        }
                    }
                    if (Number(art.heredity) === 0) {
                        heredity = "Nè nota nè valore ereditati";
                    } else if (Number(art.heredity) === 1) {
                        heredity = "Valore ereditato";
                    } else if (Number(art.heredity) === 2) {
                        heredity = "Nota e valore ereditati";
                    }

                    $('#dataTemplateTableBody' + index).append(`
                                 <tr>
                                       <td>${art.ordinamento}</td>
                                       <td>${id_articolo}</td>
                                       <td>${nome_articolo}</td>
                                       <td>
                                           <span style='display:none' class="sottotitoloFull">${sottotitolo}</span>
                                           <span style="display:block" class='sottotitoloCut'>${sottotitolo.substr(0, 50).concat('...')}</span>
                                           </td>
                                        <td>
                                           <span style='display:none' class="descrizioneFull">${descrizione}</span>
                                           <span style="display:block" class='descrizioneCut'>${descrizione.substr(0, 50).concat('...')}</span>
                                        </td>
                                       <td>${nota}</td>
                                       <td>${link}</td>
                                       <td>${heredity}</td>
                                       <td><div class="row pr-3">
                <div class="col-3">${button}</div>
                <div class="col-3">${delete_button}</div>
                </div></td>
                                 </tr>
                             `);
                });
                $('.sottotitoloCut').click(function () {
                    $(this).attr("style", "display:none");
                    $(this).prev().attr("style", "display:block");
                });
                $('.sottotitoloFull').click(function () {
                    $(this).attr("style", "display:none");
                    $(this).next().attr("style", "display:block");
                });
                $('.descrizioneCut').click(function () {
                    $(this).attr("style", "display:none");
                    $(this).prev().attr("style", "display:block");
                });
                $('.descrizioneFull').click(function () {
                    $(this).attr("style", "display:none");
                    $(this).next().attr("style", "display:block");
                });
                $('.btn-delete-row').click(function () {
                    id = $(this).attr('data-id');
                    console.log(id)

                });
                $('.btn-edit-row').click(function () {
                    id = $(this).attr('data-id');
                    const articolo = articoli.find(art => Number(art.id) === Number(id))
                    if (!articolo) return;
                    $('#idArticolo').val(articolo.id_articolo)
                    $('#idNomeArticolo').val(articolo.nome_articolo)
                    $('#idSottotitoloArticolo').val(articolo.sottotitolo_articolo)
                    $('#idDescrizioneArticolo').val(articolo.descrizione_articolo)
                    $('#idNotaArticolo').val(articolo.nota)
                    $('#idLinkAssociato').val(articolo.link)

                    if (articolo.heredity === 2) {
                        $(".btn-value-note").prop('checked', true);
                    } else if (articolo.heredity === 1) {
                        $(".btn-value").prop('checked', true);
                    } else if (articolo.heredity === 0) {
                        $(".btn-none").prop('checked', true);
                    }
                });
                $('.btn-edit-row-dec').click(function () {
                    id = $(this).attr('data-id');
                    const articolo = articoli.find(art => Number(art.id) === Number(id))
                    $('#idDecArticolo').val(articolo.id_articolo)
                    $('#decRowDescrizioneArticolo').val(articolo.descrizione_articolo)
                    $('#decRowNotaArticolo').val(articolo.nota)
                    if (articolo.link === '%') {
                        $('#percentualeSelected').prop('checked', true);
                    } else if (articolo.link === 'ValoreAssoluto') {
                        $('#valAbsSelected').prop('checked', true);
                    }

                });
                $('.btn-value').click(function () {
                    heredity = $('input[name="heredityButton"]:checked').val();
                });
                $('.btn-note-value').click(function () {
                    heredity = $('input[name="heredityButton"]:checked').val();
                });
                $('.btn-none').click(function () {
                    heredity = $('input[name="heredityButton"]:checked').val();
                });


            }


            function resetSubsection() {
                let subsection = $('.class-template-sottosezione').val();
                if (subsection !== 'Seleziona Sottosezione') {
                    $('.class-template-sottosezione').val('Seleziona Sottosezione');
                }
            }

            function renderEditArticle() {

                const updateArticolo = articoli.find(art => art.id === Number(id));
                updateArticolo.id_articolo = $('#idArticolo').val();
                updateArticolo.nome_articolo = $('#idNomeArticolo').val();
                updateArticolo.sottotitolo_articolo = $('#idSottotitoloArticolo').val();
                updateArticolo.descrizione_articolo = $('#idDescrizioneArticolo').val();
                updateArticolo.nota = $('#idNotaArticolo').val();
                updateArticolo.link = $('#idLinkAssociato').val();
                updateArticolo.heredity = $("input:radio[name=heredityRadioButton]:checked").val();
            }

            $(document).ready(function () {

                renderDataTable();
                resetSubsection();
                let section = '';
                $('.class-accordion-button').click(function () {
                    section = $(this).attr('data-section');

                    renderDataTable(section);
                    $('.class-template-sottosezione').change(function () {
                        let subsection = $(this).val();
                        if (subsection !== 'Seleziona Sottosezione') {
                            renderDataTable(section, subsection);
                        } else {
                            renderDataTable(section);
                        }
                    });
                });

                $('#editRowButton').click(function () {
                    let id_articolo = $('#idArticolo').val();
                    let nome_articolo = $('#idNomeArticolo').val();
                    let descrizione_articolo = $('#idDescrizioneArticolo').val();
                    let sottotitolo_articolo = $('#idSottotitoloArticolo').val();
                    let nota = $('#idNotaArticolo').val();
                    let link = $('#idLinkAssociato').val();
                    let heredity = $("input:radio[name=heredityRadioButton]:checked").val();

                    const payload = {
                        id,
                        id_articolo,
                        nome_articolo,
                        descrizione_articolo,
                        sottotitolo_articolo,
                        nota,
                        link,
                        heredity
                    }
                    console.log(payload)

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/editrow',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#editModal").modal('hide');
                            $("#editDecModal").modal('hide');
                            renderEditArticle();
                            renderDataTable(section);
                            console.log(section);
                            $(".alert-edit-success").show();
                            $(".alert-edit-success").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-edit-success").slideUp(500);
                            });
                        },
                        error: function (response) {
                            console.error(response);
                            $("#editModal").modal('hide');
                            $("#editDecModal").modal('hide');
                            $(".alert-edit-wrong").show();
                            $(".alert-edit-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-edit-wrong").slideUp(500);
                            });
                        }
                    });
                });

                $('#deleteRowButton').click(function () {
                    const payload = {
                        id
                    }

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/delrow',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#deleteModal").modal('hide');
                            articoli = articoli.filter(art => Number(art.id) !== Number(id));
                            renderDataTable(section);
                            $(".alert-delete-success").show();
                            $(".alert-delete-success").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-delete-success").slideUp(500);
                            });

                        },
                        error: function (response) {
                            console.error(response);
                            $("#deleteModal").modal('hide');
                            $(".alert-delete-wrong").show();
                            $(".alert-delete-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                $(".alert-delete-wrong").slideUp(500);
                            });
                        }
                    });
                });


            });
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
        $sezioni = [];
        $tot_array = [];
        foreach ($results_articoli as $articolo) {
            if (!in_array($articolo['sezione'], $sezioni)) {
                array_push($sezioni, $articolo['sezione']);
                $tot_array = array_fill_keys($sezioni, []);
            }
        }

        foreach ($tot_array as $key => $value) {
            foreach ($results_articoli as $articolo) {
                if ($key === $articolo['sezione'] && array_search($articolo['sottosezione'], $tot_array[$key]) === false) {
                    array_push($tot_array[$key], $articolo['sottosezione']);
                }
            }
        }
        $results = $data->getAllArticles();


        ?>
        <div class="accordion mt-2 col" id="accordionTemplateTable">
            <?php
            $section_index = 0;
            foreach ($tot_array as $sezione => $sottosezioni) {
                ?>
                <div class="card" id="templateCard">
                    <div class="card-header" id="headingTemplateTable<?= $section_index ?>">
                        <button class="btn btn-link class-accordion-button" data-toggle="collapse"
                                data-target="#collapseTemplate<?= $section_index ?>"
                                aria-expanded="false" aria-controls="collapseTemplate<?= $section_index ?>"
                                data-section="<?= $sezione ?>">
                            <?= $sezione ?>
                        </button>
                    </div>
                    <div id="collapseTemplate<?= $section_index ?>" class="collapse"
                         aria-labelledby="headingTemplateTable<?= $section_index ?>"
                         data-parent="#accordionTemplateTable">
                        <div class="card-body">
                            <div class="row pb-2 pt-2">
                                <div class="col-3">
                                    <select class="custom-select class-template-sottosezione"
                                            id="select <?= $sezione ?>">
                                        <option selected>Seleziona Sottosezione</option>
                                        <?php
                                        foreach ($sottosezioni as $sottosezione) {
                                            ?>
                                            <option><?= $sottosezione ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <table class="table datetable">
                                <thead>
                                <tr>
                                    <th>Ordinamento</th>
                                    <th>Id Articolo</th>
                                    <th style="width: 140px">Nome Articolo</th>
                                    <th style="width: 170px">Sottotitolo Articolo</th>
                                    <th style="width: 175px">Descrizione Articolo</th>
                                    <th>Nota</th>
                                    <th>Link</th>
                                    <th>Ereditarietà</th>
                                    <th>Azioni</th>
                                </tr>

                                </thead>
                                <tbody id="dataTemplateTableBody<?= $section_index ?>">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <?php
                $section_index++;
            }
            ?>
        </div>

        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Cancella riga </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Vuoi veramente eliminare questa riga?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
                        <button type="button" class="btn btn-primary" id="deleteRowButton">Cancella</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifica riga del fondo:</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <label>Id Articolo</label>
                        <input type="text" class="form-control" id="idArticolo">

                        <label>Nome Articolo</label>
                        <input type="text" class="form-control" id="idNomeArticolo">

                        <label>Sottotitolo Articolo</label>
                        <textarea class="form-control"
                                  id="idSottotitoloArticolo"></textarea>

                        <label>Descrizione Articolo</label>
                        <textarea class="form-control"
                                  id="idDescrizioneArticolo"></textarea>

                        <label>Nota</label>
                        <textarea class="form-control"
                                  id="idNotaArticolo"></textarea>
                        <label>Link associato</label>

                        <select name="linkAssociato" id="idLinkAssociato">
                            <?php
                            foreach ($results as $res) {
                                ?>
                                <option><?= strlen($res[0]) < 40 ? $res[0] : substr($res[0], 0, 37) . "..." ?></option>
                            <?php }
                            ?>
                        </select>
                        <label>Ereditarietà</label>
                        <div class="container">
                            <div class="form-check">
                                <input class="form-check-input btn-value" type="radio" name="heredityRadioButton"
                                       value="1">
                                <label class="form-check-label">
                                    Valore
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input btn-value-note" name="heredityRadioButton" type="radio"
                                       value="2">
                                <label class="form-check-label">
                                    Nota e Valore
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input btn-none" name="heredityRadioButton" value="0"
                                       type="radio">
                                <label class="form-check-label">
                                    Nessuno
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="editRowButton">Salva Modifica</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editDecModal" tabindex="-1"
             role="dialog"
             aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifica riga del fondo:</h5>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <label>Id Decurtazione</label>
                        <input type="text" class="form-control" id="idDecArticolo">

                        <label>Descrizione</label>
                        <textarea class="form-control"
                                  id="decRowDescrizioneArticolo"></textarea>

                        <label>Nota</label>
                        <textarea class="form-control"
                                  id="decRowNotaArticolo"></textarea>

                        <label>Tipologia decurtazione:</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typeDecEdit"
                                   id="percentualeSelected"
                                   value="%">
                            <label class="form-check-label" for="percentualeSelected">
                                %
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="typeDecEdit"
                                   id="valAbsSelected"
                                   value="ValoreAssoluto">
                            <label class="form-check-label" for="valAbsSelected">
                                Valore Assoluto
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" id="editRowButton">Salva Modifica</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="alert alert-success alert-edit-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-edit-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica riga non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-success alert-delete-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Cancellazione riga andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-delete-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Cancellazione riga non riuscita
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        self::render_scripts();
    }


}