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

            function renderEditData(id) {
                let articolo = articoli;
                articolo = articolo.filter(art => art.id === id)
                return articolo;
            }

            function renderDataTable(section, subsection) {
                let index = Object.keys(sezioni).indexOf(section);
                $('#dataTemplateTableBody' + index).html('');
                filteredArticoli = articoli;
                filteredArticoli = filteredArticoli.filter(art => art.sezione === section)
                filteredArticoli = filteredArticoli.filter(art => art.sottosezione === subsection)

                let button = '';
                let delete_button = '';

                filteredArticoli.forEach(art => {

                    if (art.row_type === 'decurtazione') {
                        button = ` <button class="btn btn-link btn-edit-row-dec" data-id='${art.id}' data-toggle="modal" data-target="#editDecModal"><i class="fa-solid fa-pen"></i></button>`;
                        if (art.editable === '0') {
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal" disabled><i class="fa-solid fa-trash"></i></button>`;
                            button = ` <button class="btn btn-link btn-edit-row-dec" data-id='${art.id}' data-toggle="modal" data-target="#editDecModal" disabled><i class="fa-solid fa-pen"></i></button>`;
                        } else {
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>`;

                        }
                    } else {
                        button = ` <button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editModal"><i class="fa-solid fa-pen"></i></button>`;
                        if (art.editable === '0') {
                            button = ` <button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editModal" disabled><i class="fa-solid fa-pen"></i></button>`;
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal" disabled><i class="fa-solid fa-trash"></i></button>`;
                        }
                        else {
                            delete_button = ` <button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal"><i class="fa-solid fa-trash"></i></button>`;
                        }
                    }

                    $('#dataTemplateTableBody' + index).append(`
                                 <tr>
                                       <td>${art.ordinamento}</td>
                                       <td>${art.id_articolo}</td>
                                       <td>${art.nome_articolo}</td>
                                       <td>${art.sottotitolo_articolo}</td>
                                        <td></td>
                                       <td>${art.nota}</td>
                                       <td>${art.link}</td>
                                       <td><div class="row pr-3">
                <div class="col-3">${button}</div>
                <div class="col-3">${delete_button}</div>
                </div></td>
                                 </tr>
                             `);
                });

                $('.btn-delete-row').click(function () {
                    id = $(this).attr('data-id');
                });
                $('.btn-edit-row').click(function () {
                    id = $(this).attr('data-id');
                    let articolo = renderEditData(id);
                    $('#idArticolo').val(articolo[0].id_articolo)
                    $('#ordinamento').val(articolo[0].ordinamento)
                    $('#idNomeArticolo').val(articolo[0].nome_articolo)
                    $('#idSottotitoloArticolo').val(articolo[0].sottotitolo_articolo)
                    //togliere commento quando verranno sistemati i caratteri speciali per le descrizioni
                    // $('#idDescrizioneArticolo').val(articolo[0].descrizione_articolo)
                    $('#idNotaArticolo').val(articolo[0].nota)
                    $('#idLinkAssociato').val(articolo[0].link)
                });
                $('.btn-edit-row-dec').click(function () {
                    id = $(this).attr('data-id');
                    let articolo = renderEditData(id);
                    $('#idDecArticolo').val(articolo[0].id_articolo)
                    $('#decRowOrdinamento').val(articolo[0].ordinamento)
                    //togliere commento quando verranno sistemati i caratteri speciali per le descrizioni
                    // $('#decRowDescrizioneArticolo').val(articolo[0].descrizione_articolo)
                    $('#decRowNotaArticolo').val(articolo[0].nota)
                    if (articolo[0].link === '%') {
                        $('#percentualeSelected').prop('checked', true);
                    } else if (articolo[0].link === 'ValoreAssoluto') {
                        $('#valAbsSelected').prop('checked', true);
                    }

                });


            }

            function renderEditDataTable(articolo) {
                filteredArticoli.filter(art => {
                    if (art.id === articolo.id) {
                        art.id_articolo = articolo.id_articolo;
                        art.nome_articolo = articolo.nome;
                        art.sottotitolo_articolo = articolo.sottotitolo;
                        art.ordinamento = articolo.ordinamento;
                        art.nota = articolo.nota;
                        art.link = articolo.link;
                    }
                });
                renderDataTable();
            }

            $(document).ready(function () {

                renderDataTable();

                $('.class-accordion-button').click(function () {
                    let section = $(this).attr('data-section');

                    renderDataTable(section);
                    $('.class-template-sottosezione').change(function () {
                        let subsection = $(this).val();
                        if (subsection !== 'Seleziona Sottosezione')
                            renderDataTable(section, subsection);
                    });
                });

                $('#editRowButton').click(function () {
                    let id_articolo = $('#idArticolo').val();
                    let nome = $('#idNomeArticolo').val();
                    // let descrizione = $('#idDescrizioneArticolo').val();
                    let descrizione = '';
                    let sottotitolo = $('#idSottotitoloArticolo').val();
                    let ordinamento = $('#ordinamento').val();
                    let nota = $('#idNotaArticolo').val();
                    let link = $('#idLinkAssociato').val();


                    const payload = {
                        id,
                        id_articolo,
                        ordinamento,
                        nome,
                        sottotitolo,
                        descrizione,
                        nota,
                        link
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
                            renderEditDataTable(payload);
                        },
                        error: function (response) {
                            console.error(response);
                            $("#editModal").modal('hide');
                            $("#editDecModal").modal('hide');
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
                        },
                        error: function (response) {
                            console.error(response);
                            $("#deleteModal").modal('hide');
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
        $results_articoli = $data->getArticoli();

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
                if ($key === $articolo['sezione'] && !array_search($articolo['sottosezione'], $tot_array[$key])) {
                    array_push($tot_array[$key], $articolo['sottosezione']);
                }
            }
        }

        ?>
        <div class="accordion mt-2" id="accordionTemplateTable">
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
                        <div class="car-body">
                            <div class="row pl-2 pb-2 pt-2">
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
                            <div class="row pl-5">
                                <div class="col-11">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>Ordinamento</th>
                                            <th>Id Articolo</th>
                                            <th>Nome Articolo</th>
                                            <th>Sottotitolo Articolo</th>
                                            <th>Descrizione Articolo</th>
                                            <th>Nota</th>
                                            <th>Link</th>
                                            <th>Azioni</th>
                                        </tr>

                                        </thead>
                                        <tbody id="dataTemplateTableBody<?= $section_index ?>">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
                        <!--  Inserire check per cambiare la view del modale a seconda del type row -->
                        <label>Ordinamento</label>
                        <input type="text" class="form-control" id="ordinamento">
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
                        <input type="text" class="form-control" id="idLinkAssociato">
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
                        <label>Ordinamento</label>
                        <input type="text" class="form-control" id="decRowOrdinamento">

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


        <?php
        self::render_scripts();
    }


}