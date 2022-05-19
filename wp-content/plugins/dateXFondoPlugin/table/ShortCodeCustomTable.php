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
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"
              integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU"
              crossorigin="anonymous">

    </head>
    <body>

    <h2>TABELLA DEI FONDI DELL'ANNO CORRENTE</h2>

    <h8>In questa tabella è possibile modificare i campi: valore, valore anno precedente e nota.<br>
        La modifica può essere bloccata tramite un pulsante situato al fondo della pagina. Bloccando la modifica, è possibile duplicare la tabella.</h8>
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
            </tr>
            </thead>
            <tbody>
            <?php
            $year = date("Y");

            $years = new CustomTable();
            $readOnly = $years->isReadOnly($year);

            $entries = $years->getAllEntries($year);

            foreach ($entries as $entry) {
                ?>
                <tr class="id_della_row">
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
    </div>
    </body>
    <form method="post">
        <input type="submit" name="button1"
               class="button" value="Blocca la Modifica"/>
        <input type="submit" name="button2"
               class="button" value="Duplica la Tabella" />
    </form>
    <script>

        $(document).ready(function () {

            $('#data_table').Tabledit({
                hideIdentifier: true,
                editButton: false,
                deleteButton: false,
                columns: {
                    identifier: [0, 'id'],
                    <?php if(!$readOnly):if(array_key_exists('button1', $_POST)) $years->getTableNotEditable($year);
                   ?>
                    editable: [[8, 'valore'], [10, 'nota']]
                    <?php endif;?>
                },

                url: 'https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/edit',
            });
        });

    </script>
        <?php
        if(array_key_exists('button2', $_POST)){
            $years->duplicateTable($year);
        }

    }

}