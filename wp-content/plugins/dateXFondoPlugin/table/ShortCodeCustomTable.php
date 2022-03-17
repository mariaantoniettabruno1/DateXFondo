<?php

namespace dateXFondoPlugin;
class ShortCodeCustomTable
{
    public static function visualize_custom_table()
    {
        ?>


        <!DOCTYPE html>

        <html lang="en">

        <head>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <script type="text/javascript" src="https://unpkg.com/jquery-tabledit@1.0.0/jquery.tabledit.js"></script>
            <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>

        </head>

        <body>


        <div>

            <form method='POST' action=''>

                <h4>Seleziona Anno</h4>

                <?php

                $years = new CustomTable();
                $results_years = $years->getAllYears();
                arsort($results_years);

                ?>

                <select id='year' name='select_year' onchange='this.form.submit()'>
                    <option disabled selected> Seleziona Anno</option>

                    <?php foreach ($results_years as $res_year): ?>

                        <option <?= isset($_POST['select_year']) && $_POST['select_year'] === $res_year[0] ? 'selected' : '' ?>

                                value='<?= $res_year[0] ?>'><?= $res_year[0] ?></option>

                    <?php endforeach; ?>
                </select>
            </form>


        </div>

        <h2>TABELLA</h2>

            <table id="data_table" class="table table-striped">
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
                </tr>
                </thead>
                <tbody>
                <?php
        if (isset($_POST['select_year'])) {
                $years = new CustomTable();
                $selected_year = $_POST['select_year'];

                $entries = $years->getAllEntries($selected_year);

            foreach ($entries as $entry) {
                    ?>
                    <tr>
                        <td><?php echo $entry [0]; ?></td>
                        <td><?php echo $entry [1]; ?></td>
                        <td><?php echo $entry [2]; ?></td>
                        <td><?php echo $entry [3]; ?></td>
                        <td><?php echo $entry [4]; ?></td>
                        <td><?php echo $entry [5]; ?></td>
                        <td><?php echo $entry [6]; ?></td>
                        <td><?php echo $entry [7]; ?></td>
                        <td><?php echo $entry [8]; ?></td>
                        <td><?php echo $entry [9]; ?></td>
                        <td><?php echo $entry [10]; ?></td>
                    </tr>
                <?php } }?>
                </tbody>
            </table>
            </body>

            <script>

                $(document).ready(function(){

                    $('#data_table').Tabledit({

                        deleteButton: false,
                        editButton: false,
                        columns: {
                            identifier : [0, 'id'],
                            editable:[[1, 'fondo'], [2, 'ente'], [3, 'anno'],[4, 'id_campo'],[5, 'label_campo'],
                                [6, 'descrizione_campo'],[7, 'sottotitolo_campo'],[8, 'valore'],[9, 'valore_anno_precedente'],[10, 'nota']]
                        },
                        hideIdentifier: true,
                        url: '/date/wp-content/plugins/dateXFondoPlugin/table/live_edit.php'

                    });
                });


            </script>
        <?php


    }

}