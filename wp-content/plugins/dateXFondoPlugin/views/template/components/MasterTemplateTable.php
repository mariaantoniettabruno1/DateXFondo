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
                $('#selectTemplateSottosezione').html('<option>Seleziona Sottosezione</option>');
                sezioni[section].forEach(ssez => {
                    $('#selectTemplateSottosezione').append(`<option>${ssez}</option>`);
                });
            }

            $(document).ready(function () {

                renderDataTable();
                $.each(sezioni, function(i) {
                    console.log(i)
                    $('#templateCard').addClass('card');
                });
                $('#collapseTemplate').click(function () {
                    const section = $('#collapseTemplate').val();
                    //$('#selectTemplateSottosezione').attr('disabled', false);
                    filterSubsections(section);
                });
                $('#selectTemplateSottosezione').change(function () {
                    const subsection = $('#selectTemplateSottosezione').val();
                    const section = $('#collapseTemplate').val();
                    if (subsection !== 'Seleziona Sottosezione') {
                        renderDataTable(section, subsection);
                    } else {
                       // $('#selectTemplateSottosezione').attr('disabled', true);

                    }
                });
            })
        </script>
        <?php
    }

    public static function render()
    {
        ?>

        <div class="accordionTemplateTable mt-2">
            <div class="card" id="templateCard">
                <div class="card-header" id="headingTemplateTable">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTemplate"
                            aria-expanded="false" aria-controls="collapseTemplate">
                    </button>
                </div>
                <div id="collapseTemplate" class="collapse" aria-labelledby="headingTemplateTable"
                     data-parent="#accordionTemplateTable">
                    <div class="car-body">
                        <div class="row pl-2 pb-2 pt-2">
                            <div class="col-3">
                                <select class="custom-select" id="selectTemplateSottosezione">
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
        </div>


        <?php
        self::render_scripts();
    }


}