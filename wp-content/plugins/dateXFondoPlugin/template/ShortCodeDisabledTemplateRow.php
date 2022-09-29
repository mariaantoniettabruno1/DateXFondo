<?php

namespace dateXFondoPlugin;

class ShortCodeDisabledTemplateRow
{
    public static function visualize_disabled_template_row(){

        $data = new DisabledTemplateRow();
        $data_entries = $data->getDataByCurrentYear();
?>


<!DOCTYPE html>

<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
          integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>

    <style type="text/css">

        .field_description > span {
            overflow: hidden;
            max-height: 200px;
            min-width: 40px;
            min-height: 40px;
            display: inline-block;
        }

        .field_section > select {
            width: 150px;
        }


    </style>
</head>
<body>


<h2>DISABLED TABLE</h2>
<div class="table-responsive">

    <table id="dataTable" style="width:50%">
        <thead>
        <tr>
            <th style="width:70%">Fondo</th>
            <th>Anno</th>
            <th>Sezione</th>
            <th>Sottosezione</th>
            <th>Id Articolo</th>
            <th>Nome Articolo</th>
            <th>Sottotitolo articolo</th>
            <th>Descrizione articolo</th>
            <th>Valore</th>
            <th>Valore anno precedente</th>
            <th>Nota</th>
            <th>Azioni</th>
        </tr>
        </thead>
        <tbody id="tbl_posts_body">
        <?php


        foreach ($data_entries as $entry) {
            ?>
            <div>
                <tr>
                    <td style="display: none" id="id_riga"><?php echo $entry[0]; ?></td>
                    <td class="field_description fondo">
                            <span>
                                <?php echo $entry[1]; ?>
                            </span>
                    </td>
                    <td class="field_description anno">
                            <span>
                                <?php echo $entry[2]; ?>
                            </span>
                    </td>
                    <td class="field_description sezione">
                            <span>
                                <?php echo $entry[5]; ?>
                            </span>
                    </td>
                    <td class="field_description sottosezione">
                            <span>
                                <?php echo $entry[6]; ?>
                            </span>
                    </td>
                    <td class="field_description id_articolo">
                            <span data-id="<?= $entry[0] ?>">
                                <?php echo $entry[4]; ?>
                            </span>
                    </td>
                    <td class="field_description nome_articolo">
                            <span>
                                <?php echo $entry[7]; ?>
                            </span>
                    </td>
                    <td class="field_description sottotitolo_articolo">
                            <span>
                                <?php echo $entry[9]; ?>
                            </span>
                    </td>
                    <td class="field_description descrizione_articolo">
                             <span>
                                <?php echo $entry[8]; ?>
                            </span>
                    </td>
                    <td class="field_description valore">
                            <span>
                                <?php echo $entry[10]; ?>
                            </span>
                    </td>
                    <td class="field_description valore_anno_precedente">
                            <span>
                                <?php echo $entry[11]; ?>
                            </span>
                    </td>
                    <td class="field_description">
                              <span class="toggleable-span">
                                 <?php echo $entry[12]; ?>
                            </span>
                        <input type="text" class="toggleable-input"
                               value='<?php echo $entry[12]; ?>'
                               style="display: none" data-field="nota" data-id="<?= $entry[0] ?>"
                        /></td>
                    <td>
                        <div class="container">
                            <button id="deleteRow" type="button" class="btn btn-outline-primary"
                                    data-id="<?= $entry[0] ?>" onclick="enableRow('<?= $entry[0] ?>')"> Abilita
                            </button>
                        </div>

                    </td>
                </tr>
            </div>

            <?php

        }
        ?>
        </tbody>
    </table>
    <br>
</div>
</body>
        <script>

            function enableRow(id) {
                $.ajax({
                    type: "POST",
                    url: "https://demo.mg3.srl/date/wp-json/datexfondoplugin/v1/table/enablerow",
                    data: {'id_riga': id},
                    success: function () {
                        successmessage = 'Riga abilitata con successo';
                        alert(successmessage);
                        location.href = "https://demo.mg3.srl/date/riabilitazione-delle-righe-cancellate-master/"
                    },
                    error: function () {
                        successmessage = 'Errore: riga non abilitata';
                        alert(successmessage);
                    }
                })
                ;
            }
        </script>
</html>
<?php
    }

}