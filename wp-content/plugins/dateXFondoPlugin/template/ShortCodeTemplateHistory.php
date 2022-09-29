<?php

namespace dateXFondoPlugin;

use dateXFondoPlugin\TemplateHistory;
use FormulaTable;

class ShortCodeTemplateHistory
{
    public static function visualize_history_template()
    {
        $data = new TemplateHistory();
        $years_results = $data->getAllYears();
        arsort($years_results);
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
    <body>
        <h2>Storico template (master)</h2>
        <div>
            <form method='POST'>
                <div class="year-submit pb-3" style="width: 30%">

                    <select id='year' name='select_year' onchange='this.form.submit()'>
                        <option disabled selected> Seleziona anno</option>

                        <?php foreach ($years_results as $res_year): ?>

                            <option <?= isset($_POST['select_year']) && $_POST['select_year'] === $res_year[0] ? 'selected' : '' ?>

                                    value='<?= $res_year[0] ?>'><?= $res_year[0] ?></option>

                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>
    <div class="table-responsive">

        <table id="dataTable">
        <thead>
        <tr>
            <th>Sezione</th>
            <th>Sottosezione</th>
            <th>ID Articolo</th>
            <th>Nome Articolo</th>
            <th>Descrizione Articolo</th>
            <th>Sottotitolo Articolo</th>
            <th>Valore</th>
            <th>Valore anno precedente</th>
            <th>Nota</th>
            <th>Link associato</th>
        </tr>
        </thead>
        <tbody id="tbl_posts_body">
        <?php

        $data_by_year = $data->getCurrentDataByYear($_POST['select_year']);
        foreach ($data_by_year as $entry) {
            ?>
            <div>
                <tr>
                    <td style="display: none"><?php echo $entry[0]; ?></td>

                    <td class="field_description sezione">
                            <span data-id="<?= $entry[0] ?>">
                                <?php echo $entry[5]; ?>
                            </span>
                    </td>
                    <td class="field_description sottosezione">
                            <span data-id="<?= $entry[0] ?>">
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
                                <?php echo $entry[8]; ?>
                            </span>
                    </td>
                    <td class="field_description descrizione_articolo">
                             <span>
                                <?php echo $entry[9]; ?>
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
                    <td class="field_description">
                              <span class="toggleable-span">
                                 <?php echo $entry[13]; ?>
                            </span>
                        <input type="text" class="toggleable-input"
                               value='<?php echo $entry[13]; ?>'
                               style="display: none" data-field="link" data-id="<?= $entry[0] ?>"
                        /></td>
                </tr>
            </div>
            <?php
        }
        ?>
            </tbody>
            </table>
            </div>
            </body>
            </head>
            </html>

            <?php
        }


}