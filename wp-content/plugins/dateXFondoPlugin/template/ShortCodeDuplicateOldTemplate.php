<?php

namespace dateXFondoPlugin;

use GFAPI;

class ShortCodeDuplicateOldTemplate
{
    public static function visualize_old_template()
    {
        ?>


        <!DOCTYPE html>

        <html lang="en">

        <head>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <script type="text/javascript" src="https://unpkg.com/jquery-tabledit@1.0.0/jquery.tabledit.js"></script>
            <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
                  integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
                  crossorigin="anonymous">

        </head>
        <body>

        <h2>TABELLA NUOVO FONDO TEMPLATE DUPLICATO</h2>
        <div class="well clearfix">
            <a class="btn btn-primary pull-right add-record"><i class="glyphicon glyphicon-plus"></i>Aggiungi nuova Riga</a>
        </div>
        <div class="table-responsive">

            <table id="data_table" class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th>ID</th>

                    <th>Fondo</th>

                    <th>Ente</th>

                    <th>Anno</th>

                    <th>ID Campo</th>

                    <th>Label Campo</th>

                    <th>Descrizione Campo</th>

                    <th>Sottotitolo Campo</th>

                    <th>Valore</th>

                    <th>Valore Anno Precedente</th>

                    <th>Nota</th>

                    <th>Attivo</th>
                </tr>
                </thead>
                <tbody id="tbl_posts_body">
                <?php
                $entry_gforms = GFAPI::get_entries(7);
                if (!empty($entry_gforms)) {
                    $fondo = $entry_gforms[0][1];
                    $ente = $entry_gforms[0][26];
                    $anno = $entry_gforms[0][25];
                }
                $old_template = new DuplicateOldTemplate();
                $old_data = $old_template->getOldData("Comune di Chivasso");

                foreach ($old_data as $entry) {
                    ?>
                    <tr class="id_della_row">
                        <td></td>
                        <td><?php echo $fondo; ?></td>
                        <td><?php echo $ente; ?></td>
                        <td><?php echo $anno; ?></td>
                        <td><?php echo $entry[4]; ?></td>
                        <td><?php echo $entry[5]; ?></td>
                        <td><?php echo $entry[6]; ?></td>
                        <td><?php echo $entry[7]; ?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <div class="radio">
                                <label><input type="radio"  value="" checked> Si</label>
                                <label><input type="radio" > No</label>
                            </div>
                        </td>
                    </tr>

                    <?php

                }
                ?>


                </tbody>
            </table>
            <div style="display:none;">
                <table id="sample_table">
                    <tr id="">
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><div class="radio">
                                <label><input type="radio" value="" checked> Si</label>
                                <label><input type="radio"> No</label>
                            </div></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="well clearfix">
            <a class="btn btn-primary pull-right add-record"><i class="glyphicon glyphicon-plus"></i>Aggiungi nuova Riga</a>
        </div>
        </body>
        <script>
            $(document).ready(function () {

                $('#data_table').Tabledit({
                    hideIdentifier: true,
                    editButton: false,
                    deleteButton: false,
                    columns: {
                        identifier: [0, 'id'],
                        editable: [[8, 'valore'], [9, 'valore anno precedente'], [10, 'nota']]
                    },

                    url: 'https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/edit',
                });
                $('#sample_table').Tabledit({
                    hideIdentifier: true,
                    editButton: false,
                    deleteButton: false,
                    columns: {
                        identifier: [0, 'id'],
                        editable: [[1,'fondo'],[2,'ente'],[3,'anno'],[4,'id campo'],[5,'label campo'],[6,'descrizione campo'],[7,'sottotitolo campo'],
                            [8, 'valore'], [9, 'valore anno precedente'], [10, 'nota']]
                    },

                    url: 'https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/edit',
                });
            });
            jQuery(document).delegate('a.add-record', 'click', function (e) {
                e.preventDefault();
                var content = jQuery('#sample_table  tr'),
                    size = jQuery('#data_table >tbody >tr').length + 1,
                    element = null,
                    element = content.clone();
                element.attr('id', 'rec-' + size);
                element.find('.delete-record').attr('data-id', size);
                element.appendTo('#tbl_posts_body');
                element.find('.sn').html(size);
            });

        </script>
        </html>

        <?php


    }
}