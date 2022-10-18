<?php

use dateXFondoPlugin\DateXFondoCommon;
use dateXFondoPlugin\MasterJoinTableRepository;

class MasterJoinTable
{
    public static function render_scripts()
    {
        ?>
        <style>
            .btn-edit-ord, .btn-save,.class-accordion-button, .btn-edit-ord:hover, .btn-save:hover,.class-accordion-button:hover {
                color: #26282f;
            }

        </style>
        <script>

            let id = 0;
            let filteredRecord = joined_record;

            function renderDataTable(section, subsection) {
                let index = Object.keys(sezioni).indexOf(section);
                $('#dataTemplateTableBody' + index).html('');
                filteredRecord = joined_record;
                filteredRecord = filteredRecord.filter(art => art.sezione === section)
                if (subsection) {
                    filteredRecord = filteredRecord.filter(art => art.sottosezione === subsection)
                }

                let heredity = '';
                let nota = '';
                let id_articolo = '';
                let descrizione = '';
                let sottotitolo = '';
                let link = '';
                let nome_articolo = '';

                filteredRecord.sort(function (a, b) {
                    // GETTING JOIN INDEX KEYS
                    let aJoinKey
                    if (a.formula) {
                        aJoinKey = "F" + a.id
                    } else {
                        aJoinKey = "T" + a.id
                    }
                    let bJoinKey
                    if (b.formula) {
                        bJoinKey = "F" + b.id
                    } else {
                        bJoinKey = "T" + b.id
                    }
                    // Getting order
                    const ajoinOrder = joinedIndexes[aJoinKey]?.ordinamento ?? -1
                    const bjoinOrder = joinedIndexes[bJoinKey]?.ordinamento ?? -1
                    // Sorting
                    return ajoinOrder - bjoinOrder
                })


                filteredRecord.forEach(art => {

                    if (art.nota !== null) {
                        nota = art.nota;
                    } else {
                        nota = '';
                    }
                    if (art.id_articolo != null) {
                        id_articolo = art.id_articolo;
                    } else {
                        id_articolo = '';
                    }
                    if (art.sottotitolo_articolo !== null) {
                        sottotitolo = art.sottotitolo_articolo;
                    } else {
                        sottotitolo = '';
                    }
                    if (art.link !== null) {
                        link = art.link;
                    } else {
                        link = '';
                    }
                    if (art.nome_articolo !== null) {
                        nome_articolo = art.nome_articolo;
                    } else {
                        nome_articolo = '';
                    }
                    if (art.nome !== undefined) {
                        nome_articolo = art.nome;
                    }
                    if (art.descrizione !== undefined) {
                        sottotitolo = art.descrizione;
                    }
                    if (art.formula !== undefined) {
                        descrizione = art.formula;
                        id_articolo = art.nome
                    }
                    if (art.link === undefined)
                        link = '';
                    if (art.nota === undefined)
                        nota = '';

                    if (art.heredity === "0") {
                        heredity = "Nè nota nè valore ereditati";
                    } else if (art.heredity === "1") {
                        heredity = "Valore ereditato";
                    } else if (art.heredity === "2") {
                        heredity = "Nota e valore ereditati";
                    }

                    let inputId;
                    let joinKey;
                    let type
                    if (art.formula) {
                        inputId = "inputOrdF" + art.id
                        joinKey = "F" + art.id
                        type = 1
                    } else {
                        inputId = "inputOrdT" + art.id
                        joinKey = "T" + art.id
                        type = 0
                    }
                    const joinId = joinedIndexes[joinKey]?.id ?? -1
                    const joinOrder = joinedIndexes[joinKey]?.ordinamento ?? -1


                    $('#dataTemplateTableBody' + index).append(`
                         <tr>
                           <td>
                            <div class="row">
                             <div class="col-5">
                              <input type="text" readonly value="${joinOrder}" style="width: 50px" id="${inputId}" data-join-id="${joinId}" data-join-key="${joinKey}" data-record-id="${art.id}" data-record-type="${type}">
                             </div>
                             <div class="col-1">
                               <button class="btn btn-link btn-edit-ord" data-target='#${inputId}'><i class="fa-solid fa-pen"></i></button>
                               <button class="btn btn-link btn-save" data-target='#${inputId}' style="display: none"><i class="fa-solid fa-floppy-disk"></i></button>
                             </div>
                            </div>
                           </td>
                           <td>${id_articolo}</td>
                           <td>${nome_articolo}</td>
                           <td>${sottotitolo}</td>
                           <td>${descrizione}</td>
                           <td>${nota}</td>
                           <td>${link}</td>
                           <td>${heredity}</td>
                         </tr>
                             `);
                });

                $('.btn-edit-ord').click(function () {
                    const targetId = $(this).attr('data-target');
                    $(this).hide();
                    $(this).next().show();
                    $(targetId).attr('readonly', false);
                });

                $('.btn-save').click(function () {
                    const targetId = $(this).attr('data-target');
                    $(this).hide();
                    $(this).prev().show();
                    const target = $(targetId)
                    target.attr('readonly', true);
                    const joinId = target.attr("data-join-id");
                    const joinKey = target.attr("data-join-key");
                    const type = target.attr("data-record-type");
                    const external_id = target.attr("data-record-id");
                    let ordinamento = target.val();
                    if (isNaN(Number(ordinamento))) {
                        ordinamento = -1
                    }
                    if (joinId > 0) {
                        // Handle update
                        $.ajax({
                            url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/join-table',
                            data: {
                                id: joinId,
                                ordinamento
                            },
                            type: "POST",
                            success: function (response) {
                                console.log(response);
                                joinedIndexes[joinKey].ordinamento = ordinamento;
                                renderDataTable(current_section, current_subsection);
                                $(".alert-ordinamento-success").show();
                                $(".alert-ordinamento-success").fadeTo(2000, 500).slideUp(500, function () {
                                    $(".alert-ordinamento-success").slideUp(500);
                                });
                            },
                            error: function (response) {
                                console.error(response);
                                $(".alert-ordinamento-fail").show();
                                $(".alert-ordinamento-fail").fadeTo(2000, 500).slideUp(500, function () {
                                    $(".alert-ordinamento-fail").slideUp(500);
                                });
                            }
                        })
                    } else {
                        // Handle insert
                        $.ajax({
                            url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/join-table',
                            data: {
                                external_id,
                                type,
                                ordinamento
                            },
                            type: "POST",
                            success: function (response) {
                                console.log(response);
                                joinedIndexes[joinKey] = {id: response["id"], type, ordinamento, external_id};
                                renderDataTable(current_section, current_subsection);
                            },
                            error: function (response) {
                                console.error(response);
                            }
                        })
                    }

                });

            }


            function resetSubsection() {
                let subsection = $('.class-template-sottosezione').val();
                if (subsection !== 'Seleziona Sottosezione') {
                    $('.class-template-sottosezione').val('Seleziona Sottosezione');
                }
            }

            let current_section;
            let current_subsection;

            $(document).ready(function () {

                renderDataTable();
                resetSubsection();

                $('.class-accordion-button').click(function () {
                    let section = $(this).attr('data-section');
                    current_section = section;

                    renderDataTable(section);
                    $('.class-template-sottosezione').change(function () {
                        let subsection = $(this).val();
                        if (subsection !== 'Seleziona Sottosezione') {
                            current_subsection = subsection;
                            renderDataTable(section, subsection);
                        } else {
                            current_subsection = null;
                            renderDataTable(section);
                        }
                    });
                });

            });
        </script>
        <?php
    }

    public static function render()
    {
        $data = new MasterJoinTableRepository();
        $results_articoli = $data->getJoinedArticoli();

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
                                            <th>Ereditarietà</th>
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
        <div class="alert alert-success alert-ordinamento-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica campo andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-ordinamento-fail" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica non andata a buon fine
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php
        self::render_scripts();
    }

}