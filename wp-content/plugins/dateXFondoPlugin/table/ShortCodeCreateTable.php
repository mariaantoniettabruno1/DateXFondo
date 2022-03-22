<?php

namespace dateXFondoPlugin;

use GuzzleHttp\Promise\Create;

class ShortCodeCreateTable
{
    public static function create_table()
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

    <h2>TABELLA</h2>
    <div class="table-responsive">

        <table id="data_table" class="table table-striped table-bordered">
            <thead>
            <tr>
                <th>Titolo Fondo</th>

                <th>Descrizione</th>

                <th>Fondo di Riferimento</th>

                <th>Nome del soggetto Deliberante</th>

                <th>Data della libera approvazione di bilancio</th>

                <th>Numero della delibera di approvazione del P.E.G.</th>

                <th>Data della delibera di approvazione P.E.G. e piano performance</th>

                <th>Numero della delibera di approvazione Piano di Razionalizzazione</th>

                <th>Numero della determina di costituzione del fondo</th>

                <th>Numero della delibera di indirizzo per la costituzione e per la contrattazione dell'anno corrente
                </th>

                <th>Scegliere il principio di riduzione di spesa del personale (Ente soggetto al Patto di Stabilita' o
                    non soggetto a Patto)
                </th>
                <th>Modello di riferimento</th>

                <th>Numero della delibera di approvazione del bilancio</th>

                <th>Responsabile</th>

                <th>Data della delibera di approvazione</th>

                <th>Data della delibera di nomina</th>

                <th>Data della delibera</th>

                <th>Data della determina di costituzione</th>

                <th>Data della delibera di indirizzo per la costituzione dell'anno corrente</th>

                <th>Ufficiale</th>
            </tr>
            </thead>
            <tbody>


                <tr class="id_della_row">
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
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            </tbody>
        </table>
    </div>
    </body>
    <form method="post">
        <input type="submit" name="button1"
               class="button" value="Crea la tabella"/>

    </form>
    <script>

        $(document).ready(function () {

            $('#data_table').Tabledit({
                hideIdentifier: true,
                editButton: false,
                deleteButton: false,
                columns: {
                    identifier: [0, 'id'],
                    editable: [[8, 'valore'], [10, 'nota']]

                },

                url: 'https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/edit',
            });
        });

    </script>
        <?php

    }

}