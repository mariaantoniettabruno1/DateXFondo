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
                                           <td><div class="row">
                <div class="col-3"><button class="btn btn-link"><i class="fa-solid fa-pen"></i></button></div>
                <div class="col-3"><button class="btn btn-link"><i class="fa-solid fa-trash"></i></button></div>
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
                    //$('#selectTemplateSottosezione').attr('disabled', false);
                    sezioni[section].forEach(ssez => {
                        $('#select' + section).append(`<option>${ssez}</option>`);
                    });
                });
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


        <?php
        self::render_scripts();
    }


}