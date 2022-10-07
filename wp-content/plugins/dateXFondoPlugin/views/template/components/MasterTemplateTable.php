<?php

namespace dateXFondoPlugin;

class MasterTemplateTable
{
    public static function render_scripts()
    {
        ?>
        <script>

            let id = 0;
            let filteredArticoli = {};

            function renderEditData(id) {
                let articolo = articoli;
                articolo = articolo.filter(art => art.id === id)
                return articolo;
            }

            function renderDataTable(section, subsection) {
                $('#dataTemplateTableBody').html('');
                 filteredArticoli = articoli;
                if (section) {
                    filteredArticoli = filteredArticoli.filter(art => art.sezione === section)
                }
                if (subsection) {
                    filteredArticoli = filteredArticoli.filter(art => art.sottosezione === subsection)
                }

                filteredArticoli.forEach(art => {
                    $('#dataTemplateTableBody').append(`
                                 <tr>
                                       <td>${art.ordinamento}</td>
                                       <td>${art.id_articolo}</td>
                                       <td>${art.nome_articolo}</td>
                                       <td>${art.sottotitolo_articolo}</td>
                                        <td></td>
                                       <td>${art.nota}</td>
                                       <td>${art.link}</td>
                                           <td><div class="row pr-3">
                <div class="col-3"><button class="btn btn-link btn-edit-row" data-id='${art.id}' data-toggle="modal" data-target="#editModal"><i class="fa-solid fa-pen"></i></button></div>
                <div class="col-3"><button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal"><i class="fa-solid fa-trash"></i></button></div>
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

                    $('#id_articolo').val(articolo[0].id_articolo)
                    $('#ordinamento').val(articolo[0].ordinamento)
                    $('#idNomeArticolo').val(articolo[0].nome_articolo)
                    $('#idSottotitoloArticolo').val(articolo[0].sottotitolo_articolo)
                    //togliere commento quando verranno sistemati i caratteri speciali per le descrizioni
                    // $('#idDescrizioneArticolo').val(articolo[0].descrizione_articolo)
                    $('#idNotaArticolo').val(articolo[0].nota)
                    $('#idLinkAssociato').val(articolo[0].link)
                });
            }

            function renderEditDataTable(articolo) {
                let filteredArticoli = articoli;
                filteredArticoli.filter(art => {
                    if (art.id === articolo.id) {
                        art.id_articolo = articolo.id_articolo;
                        art.nome_articolo = articolo.nome_articolo;
                        art.sottotitolo_articolo = articolo.sottotitolo_articolo;
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
                    let id_articolo = $('#id_articolo').val();
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
                    //console.log(payload)

                    $.ajax({
                        url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/editrow',
                        data: payload,
                        type: "POST",
                        success: function (response) {
                            console.log(response);
                            $("#editModal").modal('hide');
                            renderEditDataTable(payload);
                        },
                        error: function (response) {
                            console.error(response);
                            $("#editModal").modal('hide');
                        }
                    });
                })

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
                if ($key === $articolo['sezione'] && !in_array($tot_array[$key], $articolo['sottosezione'])) {
                    array_push($tot_array[$key], $articolo['sottosezione']);
                }
            }
        }


        ?>
        <div class="accordionTemplateTable mt-2">
            <?php

            foreach ($tot_array as $sezione => $sottosezioni) {
                ?>
                <div class="card" id="templateCard">
                    <div class="card-header" id="headingTemplateTable <?= $sezione ?>">
                        <button class="btn btn-link class-accordion-button" data-toggle="collapse"
                                data-target="#collapseTemplate <?= $sezione ?>"
                                aria-expanded="false" aria-controls="collapseTemplate"
                                data-section="<?= $sezione ?>">
                            <?= $sezione ?>
                        </button>
                    </div>
                    <div id="collapseTemplate <?= $sezione ?>" class="collapse"
                         aria-labelledby="headingTemplateTable <?= $sezione ?>"
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
                                        <tbody id="dataTemplateTableBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
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
                        <input type="text" class="form-control" id="id_articolo">

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

        <?php
        self::render_scripts();
    }


}