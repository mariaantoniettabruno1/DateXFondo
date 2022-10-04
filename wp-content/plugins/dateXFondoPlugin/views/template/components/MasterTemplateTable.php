<?php

namespace dateXFondoPlugin;

class MasterTemplateTable
{
    public static function render_scripts()
    {
        ?>
        <script>
            // function renderDataTable(section, subsection) {
            //     $('#dataTableBody').html('');
            //     let filteredArticoli = articoli;
            //     if (section) {
            //         filteredArticoli = filteredArticoli.filter(art => art.sezione === section)
            //     }
            //     if (subsection) {
            //         filteredArticoli = filteredArticoli.filter(art => art.sottosezione === subsection)
            //     }
            //     filteredArticoli.forEach(art => {
            //         $('#dataTableBody').append(`
            //                     <tr>
            //                           <td>${art.id_articolo}</td>
            //                           <td>${art.nome_articolo}</td>
            //                           <td>${art.sottotitolo_articolo}</td>
            //                           <td>${art.descrizione_articolo}</td>
            //                           <td>${art.nota}</td>
            //                           <td>${art.link}</td>
            //                     </tr>
            //                 `);
            //     });
            // }
            //
            //
            //
            // function filterSubsections(section) {
            //     $('#inputSelectSottosezione').html('<option>Seleziona Sottosezione</option>');
            //     sezioni[section].forEach(ssez => {
            //         $('#inputSelectSottosezione').append(`<option>${ssez}</option>`);
            //     });
            // }
            //
            // $(document).ready(function () {
            //     renderDataTable();
            //
            //     $('#inputSelectSottosezione').change(function () {
            //         const subsection = $('#inputSelectSottosezione').val();
            //         const section = $('#inputSelectSezione').val();
            //
            //         if (subsection !== 'Seleziona Sottosezione') {
            //             renderDataTable(section, subsection);
            //             renderFormulaTables(section, subsection);
            //         } else {
            //             renderDataTable(section);
            //         }
            //     });
            // })
        </script>
        <?php
    }

    public static function render()
    {
        self::render_scripts();
    }


}