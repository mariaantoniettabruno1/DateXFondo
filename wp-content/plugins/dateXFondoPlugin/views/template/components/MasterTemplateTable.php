<?php

namespace dateXFondoPlugin;

class MasterTemplateTable
{
    public static function render_scripts()
    {
        ?>
        <script>
            function renderDataTable(section, subsection) {
                $('#dataTemplateTableBody').html('');
                let filteredArticoli = articoli;
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
                <div class="col-3"><button class="btn btn-link btn-edit-row" data-id='${art.id}' data-articolo ='${art}'><i class="fa-solid fa-pen" data-toggle="modal" data-target="#editModal"></i></button></div>
                <div class="col-3"><button class="btn btn-link btn-delete-row" data-id='${art.id}' data-toggle="modal" data-target="#deleteModal"><i class="fa-solid fa-trash"></i></button></div>
                </div></td>
                                 </tr>
                             `);
                });
            }


            function filterSubsections(section) {
                console.log(section)
                $('.classTeplateSottosezione').html('<option>Seleziona Sottosezione</option>');
                sezioni[section].forEach(ssez => {
                    $('.classTeplateSottosezione').append(`<option>${ssez}</option>`);
                });
            }

            $(document).ready(function () {
                renderDataTable();
                $('.classAccordionButton').click(function () {
                    let section = $(this).attr('data-section');
                    // //$('#selectTemplateSottosezione').attr('disabled', false);
                    // sezioni[section].forEach(ssez => {
                    //     $('#select' + section).append(`<option>${ssez}</option>`);
                    // });
                });
                $('.btn-edit-row').click(function () {
                    let articolo = $(this).attr('data-articolo');
                    console.log(articolo)

                    $('#editRowButton').click(function () {
                    const payload = {
                        id
                    }
                    console.log(payload)

                    //$.ajax({
                    //    url: '<?//= DateXFondoCommon::get_website_url() ?>///wp-json/datexfondoplugin/v1/editrow',
                    //    data: payload,
                    //    type: "POST",
                    //    success: function (response) {
                    //        console.log(response);
                    //        $("#editModal").modal('hide');
                    //    },
                    //    error: function (response) {
                    //        console.error(response);
                    //        $("#editModal").modal('hide');
                    //    }
                    //});
                })
                })
                $('.btn-delete-row').click(function () {
                    let id = $(this).attr('data-id');
                    $('#deleteRowButton').click(function () {
                        const payload = {
                            id
                        }
                        console.log(payload)

                        $.ajax({
                            url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/delrow',
                            data: payload,
                            type: "POST",
                            success: function (response) {
                                console.log(response);
                                $("#deleteModal").modal('hide');
                                $(".delete-toast-ok").toast('show');
                            },
                            error: function (response) {
                                console.error(response);
                                $("#deleteModal").modal('hide');
                            }
                        });
                    })
                })


            })
        </script>
        <?php
    }

    public static function render()
    {
        $data = new MasterTemplateRepository();
        $results_articoli = $data->getArticoli();

        $sezioni = [];
        $sottosezioni = [];
        foreach ($results_articoli as $articolo) {
            if (!in_array($articolo['sezione'], $sezioni)) {
                array_push($sezioni, $articolo['sezione']);
            }
        }


        ?>
        <div class="accordionTemplateTable mt-2">
            <?php

            foreach ($sezioni as $key => $sezione) {
                ?>
                <div class="card" id="templateCard">
                    <div class="card-header" id="headingTemplateTable<?= $key ?>">
                        <button class="btn btn-link classAccordionButton" data-toggle="collapse"
                                data-target="#collapseTemplate<?= $key ?>"
                                aria-expanded="false" aria-controls="collapseTemplate<?= $key ?>"
                                data-section="<?= $sezione ?>">
                            <?= $sezione ?>
                        </button>
                    </div>
                    <div id="collapseTemplate<?= $key ?>" class="collapse"
                         aria-labelledby="headingTemplateTable<?= $key ?>"
                         data-parent="#accordionTemplateTable">
                        <div class="car-body">
                            <div class="row pl-2 pb-2 pt-2">
                                <div class="col-3">
                                    <select class="custom-select class-template-sottosezione"
                                            id="select <?= $sezione ?>">
                                        <option>Seleziona Sottosezione</option>
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
                        <input type="text" class="form-control" id="ordinamento" name="ordinamento">
                        <label>Id Articolo</label>
                        <input type="text" class="form-control" id="id_articolo" name="id_articolo">

                        <label>Nome Articolo</label>
                        <input type="text" class="form-control" id="idNomeArticolo"
                               name="idNomeArticolo">

                        <label>Sottotitolo Articolo</label>
                        <textarea class="form-control"
                                  id="idSottotitoloArticolo"
                                  name="idSottotitoloArticolo"></textarea>

                        <label>Descrizione Articolo</label>
                        <textarea class="form-control"
                                  id="idDescrizioneArticolo"
                                  name="idDescrizioneArticolo"></textarea>

                        <label>Nota</label>
                        <textarea class="form-control"
                                  id="idNotaArticolo"
                                  name="idNotaArticolo"></textarea>
                        <label>Link associato</label>
                        <input type="text" class="form-control" id="idLinkAssociato"
                               name="idLinkAssociato">
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