<?php

use dateXFondoPlugin\MasterJoinTableRepository;

class MasterJoinTable
{
    public static function render_scripts()
    {
        ?>
        <script>

            let id = 0;
            let filteredArticoli = articoli;

            function renderDataTable(section, subsection) {

                let index = Object.keys(sezioni).indexOf(section);
                $('#dataTemplateTableBody' + index).html('');
                filteredArticoli = articoli;
                filteredFormulas = formulas;
                filteredFormulas = filteredFormulas.filter(art => art.sezione === section)
                filteredArticoli = filteredArticoli.filter(art => art.sezione === section)
                if (subsection) {
                    filteredFormulas = filteredFormulas.filter(art => art.sottosezione === subsection)
                    filteredArticoli = filteredArticoli.filter(art => art.sottosezione === subsection)

                }

                let heredity = '';
                let nota = '';
                let id_articolo = '';
                let descrizione = '';
                let sottotitolo = '';
                let link = '';
                let nome_articolo = '';


                filteredArticoli.forEach(art => {

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
                    // if (art.descrizione_articolo !== null) {
                    //     descrizione = art.descrizione_articolo;
                    // }
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

                    if (art.heredity === "0") {
                        heredity = "Nè nota nè valore ereditati";
                    } else if (art.heredity === "1") {
                        heredity = "Valore ereditato";
                    } else if (art.heredity === "2") {
                        heredity = "Nota e valore ereditati";
                    }


                    $('#dataTemplateTableBody' + index).append(`
                                 <tr>
                                       <td>${art.ordinamento}</td>
                                       <td>${id_articolo}</td>
                                       <td>${nome_articolo}</td>
                                       <td>${sottotitolo}</td>
                                        <td></td>
                                       <td>${nota}</td>
                                       <td>${link}</td>
                                       <td>${heredity}</td>
                                       <td><div class="row pr-3">
                <div class="col-3"><button class="btn btn-link btn-edit-row-dec" data-id='${art.id}'><i class="fa-solid fa-pen"></i></button></div>
                </div></td>
                                 </tr>
                             `);
                });
                filteredFormulas.forEach(form => {


                    $('#dataTemplateTableBody' + index).append(`
                                 <tr>
                                       <td></td>
                                       <td><b>Formula: </b></td>
                                       <td>${form.nome}</td>
                                       <td>${form.descrizione}</td>
                                       <td>${form.formula}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                       <td><div class="row pr-3">
                <div class="col-3"><button class="btn btn-link btn-edit-row-dec" data-id='${form.id}'><i class="fa-solid fa-pen"></i></button></div>
                </div></td>
                                 </tr>
                             `);
                });


            }


            function resetSubsection() {
                let subsection = $('.class-template-sottosezione').val();
                if (subsection !== 'Seleziona Sottosezione') {
                    $('.class-template-sottosezione').val('Seleziona Sottosezione');
                }
            }
            $(document).ready(function () {

                renderDataTable();
                resetSubsection();

                $('.class-accordion-button').click(function () {
                    let section = $(this).attr('data-section');

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

        <?php
        self::render_scripts();
    }

}