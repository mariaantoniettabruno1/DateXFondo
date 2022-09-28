<?php

namespace dateXFondoPlugin;

use FormulaTable;

header('Content-Type: text/javascript');

class ShortCodeFormulaTable
{
    public static function visualize_formula_template()
    {
        $data = new FormulaTable();
        $results_sections = $data->getAllSections();
        arsort($results_sections);


        ?>


        <!DOCTYPE html>

        <html lang="en">

        <head>
            <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
                  integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
                  crossorigin="anonymous" referrerpolicy="no-referrer"/>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
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

                #closeConditionButtonId {
                    color: #0a0a0a;
                }

                #infoPointId {
                    color: grey;
                }

            </style>
        </head>

        <body>
        <div>
            <form method='POST'>
                <div class="section-submit pb-3" style="width: 30%">

                    <select id='section' name='select_section' onchange='this.form.submit()'>
                        <option disabled selected> Seleziona Sezione</option>

                        <?php foreach ($results_sections as $res_section): ?>

                            <option <?= isset($_POST['select_section']) && $_POST['select_section'] === $res_section[0] ? 'selected' : '' ?>

                                    value='<?= $res_section[0] ?>'><?= $res_section[0] ?></option>

                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="subsection-submit" style="width: 30%">

                    <select id='subsection' name='select_subsection' onchange='this.form.submit()'>
                        <option disabled selected> Seleziona Sottosezione</option>

                        <?php $results_subsections = $data->getAllSubsections($_POST['select_section']);
                        arsort($results_subsections);
                        foreach ($results_subsections as $res_subsection): ?>

                            <option <?= isset($_POST['select_subsection']) && $_POST['select_subsection'] === $res_subsection[0] ? 'selected' : '' ?>

                                    value='<?= $res_subsection[0] ?>'><?= $res_subsection[0] ?></option>

                        <?php endforeach; ?>
                    </select>
                </div>

            </form>
        </div>

        <div class=" card border-secondary mb-3">
            <div class="card-header">Creazione della formula</div>
            <div class="card-body text-secondar">
                <div class="container">
                    <div class="container">
                        <div style="width: 30%" class="row pt-4">
                            <div class="col-sm">
                                <input type="text" name="name_formula" id="name_formula" placeholder="Nome Formula">
                            </div>
                            <div class="col-sm">
                                <input type="text" name="descrizione_formula" id="descrizione_formula"
                                       placeholder="Descrizione formula">
                            </div>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button type="button" class="btn btn-link" id="addConditionButton"><i
                                    class="fa-solid fa-circle-plus"></i> Aggiungi
                            condizione
                        </button>
                    </div>
                    <div class="card pb-5" id="conditionCard" hidden>
                        <div class="card-body">
                            <div class="pb-4">
                                Condizione
                                <button type="button" class="btn btn-link" id="closeConditionButtonId"
                                        style="float:right"><i class="fa-solid fa-xmark text-black"></i></button>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <select class="form-select" id="firstValueCondition"
                                                name='select_first_value'
                                                onchange="addNumberToCondition()">
                                            <option disabled selected>Aggiungi valore</option>

                                            <?php
                                            $results_id = $data->getAllIdsCampo($_POST['select_section'], $_POST['select_subsection']);
                                            arsort($results_id);
                                            foreach ($results_id as $res_id): ?>

                                                <option <?= isset($_POST['select_first_value']) && $_POST['select_first_value'] === $res_id[0] ? 'selected' : '' ?>

                                                        value='<?= $res_id[0] ?>'><?= $res_id[0] ?></option>

                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-1 pl-1">
                                        <button type="button" class="btn btn-link" data-toggle="tooltip"
                                                data-placement="top"
                                                title="per maggiori dettagli consulta la 'Tabella dati' "
                                                id="infoPointId">
                                            <i class="fa-solid fa-circle-info"></i>
                                        </button>
                                    </div>
                                    <div class="col-sm-1">
                                        <select class="form-select" id="selectConditionOperator">
                                            <option value=">">></option>
                                            <option value="<"><</option>
                                            <option value=">=">≥</option>
                                            <option value="<=">≤</option>
                                            <option value="=">=</option>
                                            <option value="!=">!=</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-2">
                                        <select class="form-select" id="parenthesisConditionOperator">
                                            <option value="0">0</option>
                                            <option value="(">(</option>
                                            <option value=")">)</option>
                                            <option value="&&">E</option>
                                            <option value="||">Oppure</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3 pl-4">
                                        <button type="button" class="btn btn-link" style="float: right"
                                                onclick="deleteLastConditionCharacter()"><i
                                                    class="fa fa-trash text-blue"></i> Elimina ultimo valore
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div id="divValCondition" class="pt-4 pl-5"></div>
                        <div class="row">
                            <div class="col-sm pt-3 pl-5">
                                <button class="btn btn-outline-primary" onclick="deleteConditionExpression()">Elimina
                                    Condizione
                                </button>
                            </div>
                            <div class="col-sm pt-3 pr-5">
                                <button type="button" class="btn btn-primary" style="float:right">Salva condizione
                                </button>
                            </div>
                        </div>


                    </div>
                    <div class="row pt-5">
                        <div class="col-sm-3">
                            <select class="form-select" id="valueOperator" name='select_id_campo'
                                    onchange="addNumberToFormula()">
                                <option disabled selected>Aggiungi valore</option>

                                <?php foreach ($results_id as $res_id): ?>

                                    <option <?= isset($_POST['select_id_campo']) && $_POST['select_id_campo'] === $res_id[0] ? 'selected' : '' ?>

                                            value='<?= $res_id[0] ?>'><?= $res_id[0] ?></option>

                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-sm-3">

                            <select class="form-select" id="selectOperator">
                                <option disabled selected>Aggiungi operazione</option>
                                <option value="+">+</option>
                                <option value="-">-</option>
                                <option value="*">*</option>
                                <option value="\">\</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-select" id="parenthesisOperator">
                                <option disabled selected>Aggiungi parentesi</option>
                                <option value="(">(</option>
                                <option value=")">)</option>
                            </select>
                        </div>
                        <!--                        <div class="col-sm-3">-->
                        <!--                            <div class="input-group mb-3">-->
                        <!--                                <input type="text" class="form-control" placeholder="Percentuale"-->
                        <!--                                       aria-label="Percentuale" aria-describedby="basic-addon1" id="percentageInput">-->
                        <!--                                <div class="input-group-prepend">-->
                        <!--                                    <span class="input-group-text pr-3" id="basic-addon1">%</span>-->
                        <!--                                </div>-->
                        <!--                                <button type="button" class="btn btn-outline-info pl-3" style="float: right"-->
                        <!--                                        onclick="calculatePercentage()">Calcola-->
                        <!--                                </button>-->
                        <!--                            </div>-->
                        <!--                        </div>-->
                        <div class="col-sm-3">
                            <button type="button" class="btn btn-link" style="float: right"
                                    onclick="deleteLastFormulaCharacter()"><i
                                        class="fa fa-trash text-blue"></i> Elimina ultimo valore
                            </button>
                        </div>
                    </div>
                </div>
                <div id="divValFormula" class="pt-4"></div>
                <form method='POST'>
                    <input type="hidden" name="formula" value="formula" id="formula">
                    <input type="hidden" name="formula_label" id="formula_label" value="formula_label">
                    <input type="hidden" name="formula_name" id="formula_name" value="formula_name">
                    <input type="hidden" name="condition" id="condition" value="condition">
                    <div class="row">
                        <div class="col-sm pt-3">
                            <button class="btn btn-outline-primary" onclick="deleteFormulaExpression()">Elimina Formula
                            </button>
                        </div>
                        <div class="col-sm pt-3"><input type="submit" class="btn btn-primary" style="float: right"
                                                        onclick="calculateFormula()"
                                                        value="Salva formula"></div>
                    </div>
                </form>

            </div>
        </div>
        <br>

        <br>
        <div>
            <h2>TABELLA DATI</h2>
            <div class="table-responsive table-hover">

                <table id="dataTable">
                    <thead>
                    <tr>
                        <th style="width:70%">Fondo</th>
                        <th>Anno</th>
                        <th>ID Campo</th>
                        <th>Sezione</th>
                        <th>Sottosezione</th>
                        <th>Label campo</th>
                        <th>Descrizione campo</th>
                        <th>Sottotitolo campo</th>
                        <th>Valore</th>
                        <th>Valore anno precedente</th>
                        <th>Nota</th>
                        <th>Attivo</th>
                    </tr>
                    </thead>
                    <tbody id="tbl_posts_body">
                    <?php


                    if (isset($_POST['select_section'])) {

                        $old_template = new DuplicateOldTemplate();
                        $data = new FormulaTable();

                        $limit = 5;
                        $page = get_query_var('index', 1);

                        $startRecord = ($page - 1) * $limit;
                        $selected_section = $_POST['select_section'];
                       // $entries = $data->getAllEntriesFromSection($selected_section);
                        $entries = [];
                        $fondo = $entries[0][1];
                        $anno = $entries[0][2];


                        foreach ($entries as $entry) {
                            unset($entry[0]);
                            unset($entry[13]);

                            setcookie("Fondo", $fondo);
                            setcookie("Anno", $anno);
                            setcookie("Sezione", $selected_section);
                            ?>
                            <tr>
                                <td style="display: none"><?php echo $entry[0]; ?></td>
                                <td>  <span class="toggleable-span">
                                <?php echo $fondo; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $fondo; ?>'
                                           style="display: none" data-field="fondo" data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description"
                                <span class="toggleable-span">
                                <?php echo $anno; ?>
                            </span>
                                <input type="text" class="toggleable-input" value='<?php echo $anno; ?>'
                                       style="display: none" data-field="anno" data-id="<?= $entry[0] ?>"
                                /></td>

                                <td class="field_description">
                                    <div data-field="id_campo" data-id="<?= $entry[0] ?>">
                                        <label><input type="text" name="id_campo"
                                                      value='<?php echo $entry[3]; ?>'
                                                      hidden> <?php echo $entry[3]; ?>
                                        </label>
                                    </div>
                                </td>
                                <td class="field_section">
                            <span class="toggleable-span">
                                <?php echo $entry[4]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[4]; ?>'
                                           style="display: none" data-field="sezione" data-id="<?= $entry[0] ?>"
                                    />
                                </td>
                                <td class="field_section">
                            <span class="toggleable-span">
                                <?php echo $entry[5]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[5]; ?>'
                                           style="display: none" data-field="sottosezione"
                                           data-id="<?= $entry[0] ?>"
                                    />
                                </td>
                                <td class="field_description">
                            <span class="toggleable-span">
                                <?php echo $entry[6]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[6]; ?>'
                                           style="display: none" data-field="label_campo"
                                           data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">
                            <span class="toggleable-span">
                                <?php echo $entry[7]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[7]; ?>'
                                           style="display: none" data-field="descrizione_campo"
                                           data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">
                             <span class="toggleable-span">
                                <?php echo $entry[8]; ?>
                            </span>
                                    <input type="text" class="toggleable-input" value='<?php echo $entry[8]; ?>'
                                           style="display: none" data-field="sottotitolo_campo"
                                           data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">   <span class="toggleable-span">
                                <?php echo $entry[9]; ?>
                            </span>
                                    <input type="text" class="toggleable-input"
                                           value='<?php echo $entry[9]; ?>'
                                           style="display: none" data-field="valore"
                                           data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">   <span class="toggleable-span">
                                <?php echo $entry[10]; ?>
                            </span>
                                    <input type="text" class="toggleable-input"
                                           value='<?php echo $entry[10]; ?>'
                                           style="display: none" data-field="valore_anno_precedente"
                                           data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">
                              <span class="toggleable-span">
                                 <?php echo $entry[11]; ?>
                            </span>
                                    <input type="text" class="toggleable-input"
                                           value='<?php echo $entry[11]; ?>'
                                           style="display: none" data-field="nota" data-id="<?= $entry[0] ?>"
                                    /></td>
                                <td class="field_description">
                              <span class="toggleable-span">
                                 <?php echo $entry[12] == 1 ? "Attivo" : "Non attivo" ?>
                            </span>
                                    <input type="text" class="toggleable-input"
                                           value='<?php echo $entry[12]; ?>'
                                           style="display: none" data-field="attivo" data-id="<?= $entry[0] ?>"
                                    /></td>
                            </tr>

                            <?php

                        }
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </body>
        </div>
        </div>
        </div>

        <script>
            let formula = '';
            let condition = '';
            let operator = '';
            let parenthesis = '';
            let ConditionOperator = '';
            let ConditionParenthesis = '';
            let result = 0;
            let number = 0;
            let conditionValue = '';
            let resultPercentage = 0;
            let formulaDescription = '';
            let formulaName = '';

            const myHeaders = new Headers();

            $(document).ready(function () {
                myHeaders.append('Cache-Control', 'no-store');
            });

            $(".btn-link").click(function () {
                $("#conditionCard").removeAttr('hidden');
                document.getElementById('addConditionButton').style.visibility = 'hidden';
            });

            function addNumberToFormula() {
                number = event.target.value;
                formula = formula.concat(number.toString());
                formulaDescription = document.getElementById('descrizione_formula').value;
                document.getElementById("divValFormula").innerHTML = formula;
            }

            function addNumberToCondition() {
                conditionValue = event.target.value;
                condition = condition.concat(conditionValue);
                document.getElementById("divValCondition").innerHTML = condition;
                console.log("Sono nel log della condizione")
                console.log(condition)
            }

            $("#selectOperator").change(function () {
                let select = document.getElementById('selectOperator');
                operator = select.options[select.selectedIndex].value;
                formula = formula.concat(operator.toString());
                formulaDescription = document.getElementById('descrizione_formula').value;
                document.getElementById("divValFormula").innerHTML = formula;
            });
            $("#parenthesisOperator").change(function () {
                let select = document.getElementById('parenthesisOperator');
                let parenthesis = select.options[select.selectedIndex].value;
                formula = formula.concat(parenthesis.toString());
                formulaDescription = document.getElementById('descrizione_formula').value;
                document.getElementById("divValFormula").innerHTML = formula;
            });
            $("#selectConditionOperator").change(function () {
                let selectCondition = document.getElementById('selectConditionOperator');
                conditionOperator = selectCondition.options[selectCondition.selectedIndex].value;
                condition = condition.concat(conditionOperator.toString());
                document.getElementById("divValCondition").innerHTML = condition;
                console.log("Sono nella prima select condizionale")
                console.log(condition)

            });
            $("#parenthesisConditionOperator").change(function () {
                let selectCondition = document.getElementById('parenthesisConditionOperator');
                 ConditionParenthesis = selectCondition.options[selectCondition.selectedIndex].value;
                condition = condition.concat(ConditionParenthesis.toString());
                document.getElementById("divValCondition").innerHTML = condition;
                console.log("Sono nella seconda select condizionale")
                console.log(condition)
            });

            function deleteFormulaExpression() {
                formula = '';
                document.getElementById("divValFormula").innerHTML = formula;
            }

            function deleteConditionExpression() {
                condition = '';
                document.getElementById("divValCondition").innerHTML = condition;
            }

            //migliorare, cancolare la lunghezza dell'ultimo carattere per poi cancellarli tutti
            function deleteLastFormulaCharacter() {
                formula = formula.slice(0, formula.length - 1);
                document.getElementById("divValFormula").innerHTML = formula;
            }

            //migliorare, cancolare la lunghezza dell'ultimo carattere per poi cancellarli tutti
            function deleteLastConditionCharacter() {
                condition = condition.slice(0, condition.length - 1);
                document.getElementById("divValCondition").innerHTML = condition;
            }

            function calculatePercentage() {
                let percentage = document.getElementById('percentageInput');
                resultPercentage = (result * percentage) / 100;
            }

            function calculateFormula() {
                document.getElementById("formula").value = formula;
                document.getElementById("formula_label").value = formulaDescription;
                document.getElementById("formula_name").value = formulaName;
                document.getElementById("condition").value = condition;
                console.log(condition)
                <?php
                $formula = $_POST['formula'];
                $labelDescrittiva = $_POST['formula_label'];
                $nomeFormula = $_POST['formula_name'];
                $formulaCondition = $_POST['condition'];
                $ente = $_COOKIE['Ente'];
                $fondo = $_COOKIE['Fondo'];
                $anno = $_COOKIE['Anno'];
                $sezione = $_COOKIE['Sezione'];
                $sottosezione = '';
                $savedFormula = new FormulaTable();
                $savedFormula->saveFormula($sezione, $sottosezione,$nomeFormula, $labelDescrittiva, $formulaCondition, $formula);
                ?>
            }

        </script>
        </html lang="en">

        <?php


    }
}