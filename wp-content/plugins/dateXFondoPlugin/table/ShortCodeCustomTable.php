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
        $year = date("Y");

        $years = new CustomTable();

        $entries = $years->getAllEntries($year);

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
        <?php } ?>
        </tbody>
    </table>
    </body>

    <script>

        $(document).ready(function () {

            $('#data_table').Tabledit({

                deleteButton: false,
                editButton: false,
                columns: {
                    identifier: [0, 'id'],
                    editable: [[8, 'valore'], [10, 'nota']]
                },
                hideIdentifier: true,
                url: 'https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/edit'

            });
        });



        <?php


    }

}