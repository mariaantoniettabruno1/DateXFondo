<?php

namespace dateXFondoPlugin;

class MasterAllTemplateTable
{
    public static function render_scripts()
    {
        ?>
 
        <script>
            let template_name = '';

            function renderDataTable() {
                $('#dataAllTemplateTableBody').html('');

                articoli.forEach(art => {
                    $('#dataAllTemplateTableBody').append(`
                                 <tr>
                                       <td >${art.fondo}</td>
                                       <td >${art.anno}</td>
                                       <td >${art.descrizione_fondo}</td>
                                       <td >${art.template_name}</td>
                                           <td>
                <button class="btn btn-link btn-visualize-template" data-name='${art.template_name}'><i class="fa-solid fa-eye"></i></button>
                <button class="btn btn-link btn-visualize-complete-template" data-name='${art.template_name}'>Fondo Completo <i class="fa-solid fa-arrow-right"></i></button>
                </td>
                </tr>
                             `);

                });

                $('.btn-visualize-template').click(function () {
                    template_name = $(this).attr('data-name');
                }); $('.btn-visualize-complete-template').click(function () {
                    template_name = $(this).attr('data-name');
                });
            }

            $(document).ready(function () {

                renderDataTable();

                $('.btn-visualize-template').click(function () {
                    location.href = '<?= DateXFondoCommon::get_website_url()?>/visualizza-template-fondo/?template_name=' + template_name;
                });
                $('.btn-visualize-complete-template').click(function () {
                    location.href = '<?= DateXFondoCommon::get_website_url()?>/tabella-join-template-formula/?template_name=' + template_name;
                });
            });
        </script>
    <?php }

    public static function render()
    {
        ?>
        <table class="table" style="table-layout: fixed">
            <thead>
            <tr>
                <th style="width: 12.5rem">Fondo</th>
                <th style="width: 6.25rem">Anno</th>
                <th>Descrizione fondo</th>
                <th style="width: 6.25rem">Nome Template</th>
                <th style="width:15rem">Azioni</th>
            </tr>

            </thead>
            <tbody id="dataAllTemplateTableBody">
            </tbody>
        </table>
        <?php
        self::render_scripts();
    }
}