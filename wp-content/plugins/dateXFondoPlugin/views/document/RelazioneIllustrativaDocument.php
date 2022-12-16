<?php

namespace dateXFondoPlugin;

class RelazioneIllustrativaDocument
{
    public static function getInput($key, $value, $color)
    {

        ?>
        <span class="editable-input" data-active="false">
            <span class="variable-span-text" style="color:<?= $color ?>"><?= $value ?></span>
        <input class="variable-input-text" id="input<?= $key ?>" value="<?= $value ?>" style="display: none"
               data-key="<?= $key ?>">
        </span>

        <?php
    }

    public static function getTextArea($key, $value, $color)
    {
        ?>
        <span class="editable-area" data-active="false">
        <span class="variable-span-area" style="color:<?= $color ?>"><?= $value ?></span>
            <textarea class=" variable-text-area form-control" id="input<?= $key ?>" data-key="<?= $key ?>"
                      style="display: none" value="<?= $value ?>"><?= $value ?></textarea>
             </span>
        <?php
    }

    public static function render()
    {
        $data = new DeliberaDocumentRepository();
        $infos = $data->getAllValues('Schema di relazione illustrativa', 'Emanuele Lesca');
        ?>
        <!DOCTYPE html>

        <html lang="en">

        <head>
            <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
            <script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
                    integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                    crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
                    integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                    crossorigin="anonymous"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css"
                  integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A=="
                  crossorigin="anonymous" referrerpolicy="no-referrer"/>
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
            <link rel="stylesheet" href="<?= DateXFondoCommon::get_base_url() ?>/assets/styles/main.css">

            <script>
                let data = {};

                function exportHTML() {
                    var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
                        "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                        "xmlns='http://www.w3.org/TR/REC-html40'>" +
                        "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title></head><body>";
                    var footer = "</body></html>";
                    var sourceHTML = header + document.getElementById("determinaCostituzioneContent").innerHTML + footer;

                    var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
                    var fileDownload = document.createElement("a");
                    document.body.appendChild(fileDownload);
                    fileDownload.href = source;
                    fileDownload.download = 'document.doc';
                    fileDownload.click();
                    document.body.removeChild(fileDownload);
                }

                $(document).ready(function () {
                    data = JSON.parse((`<?=json_encode($infos);?>`));
                    const editedInputs = {};
                    $('.editable-input >span').click(function () {
                        $(this).next().show();
                        $(this).hide();
                    });
                    $('.editable-input >input').blur(function () {
                        $(this).prev().html($(this).val());
                        $(this).prev().show();
                        $(this).hide();
                        editedInputs[$(this).attr('data-key')] = $(this).val();

                    });
                    $('.editable-area >span').click(function () {
                        $(this).next().show();
                        $(this).hide();
                    });
                    $('.editable-area >textarea').blur(function () {
                        $(this).prev().html($(this).val());
                        $(this).prev().show();
                        $(this).hide();
                        editedInputs[$(this).attr('data-key')] = $(this).val();
                    });
                    $('.editable-select').change(function () {
                        editedInputs[$(this).attr('data-key')] = $(this).val();
                    });

                    $('.btn-save-edit').click(function () {
                        let document_name = $('#inputDocumentName').val();
                        let editor_name = $('#inputEditorName').val();
                        let year = $('#inputYear').val();

                        const payload = {
                            editedInputs,
                            document_name,
                            editor_name,
                            year
                        }
                        console.log(payload)
                        $.ajax({
                            url: '<?= DateXFondoCommon::get_website_url() ?>/wp-json/datexfondoplugin/v1/deliberadocument',
                            data: payload,
                            type: "POST",
                            success: function (response) {
                                $(".alert-edit-success").show();
                                $(".alert-edit-success").fadeTo(2000, 500).slideUp(500, function () {
                                    $(".alert-edit-success").slideUp(500);
                                });
                            },
                            error: function (response) {
                                console.error(response);
                                $(".alert-edit-wrong").show();
                                $(".alert-edit-wrong").fadeTo(2000, 500).slideUp(500, function () {
                                    $(".alert-edit-wrong").slideUp(500);
                                });
                            }
                        });
                    })

                });


            </script>
        </head>
        <body>
        <div class="container-fluid">
            <div class="row">
                <?php
                \DeliberaDocumentHeader::render();
                ?>
            </div>
            <button class="btn btn-outline-secondary btn-save-edit" style="width:10%">Salva modifica</button>
            <h3>Comune di <?php self::getInput('var0', $infos[0]['valore'], 'blue'); ?></h3>
            <br>
            <h6>Relazione illustrativa</h6>
            <br>
            Modulo I - Illustrazione degli aspetti procedurali, sintesi del contenuto del contratto ed autodichiarazione
            relative agli adempimenti della legge
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Data di sottoscrizione</th>
                    <th scope="col"><?php self::getInput('var1', $infos[1]['valore'], 'orange'); ?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Periodo temporale di vigenza</th>
                    <td><?php self::getInput('var2', $infos[2]['valore'], 'black'); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">Composizione della delegazione trattante</th>
                    <td>Parte Pubblica (<?php self::getInput('var3', $infos[3]['valore'], 'orange'); ?>):
                        <?php self::getInput('var4', $infos[4]['valore'], 'orange'); ?> – Presidente
                        <?php self::getInput('var5', $infos[5]['valore'], 'orange'); ?> - Componente
                        <?php self::getInput('var6', $infos[6]['valore'], 'orange'); ?> - Componente
                        <?php self::getInput('var7', $infos[7]['valore'], 'orange'); ?> - Componente

                        Organizzazioni sindacali ammesse alla contrattazione (elenco sigle):
                        SIND. FP CGIL
                        SIND. CISL FP
                        SIND. UIL FPL
                        SIND. CSA REGIONI AUTONOMIE LOCALI
                        R.S.U.:
                        Signor <?php self::getInput('var8', $infos[8]['valore'], 'orange'); ?>
                        Signor <?php self::getInput('var9', $infos[9]['valore'], 'orange'); ?>
                        Signor <?php self::getInput('var10', $infos[10]['valore'], 'orange'); ?>
                        Signor <?php self::getInput('var11', $infos[11]['valore'], 'orange'); ?>
                        Organizzazioni sindacali firmatarie (elenco sigle):
                        SIND. FP CGIL signor <?php self::getInput('var12', $infos[12]['valore'], 'orange'); ?>
                        SIND. CISL FP signor <?php self::getInput('var13', $infos[13]['valore'], 'orange'); ?>
                        SIND. UIL FPL signor <?php self::getInput('var14', $infos[14]['valore'], 'orange'); ?>
                        SIND. CSA REGIONI AUTONOMIE LOCALI
                        signor <?php self::getInput('var15', $infos[15]['valore'], 'orange'); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">Soggetti destinatari</th>
                    <td>Personale non dirigente del Comune
                        di <?php self::getInput('var16', $infos[16]['valore'], 'black'); ?></td>
                    <td></td>
                    <td></td>
                    <td></td>


                </tr>
                <tr>
                    <th scope="row">Materie trattate dal contratto integrativo (descrizione sintetica)</th>
                    <td>Si rinvia per un dettaglio esaustivo al Modulo 2 Illustrazione dell’articolato del contratto
                    </td>
                    <td></td>
                    <td></td>
                    <td></td>

                </tr>
                <tr>
                    <th scope="row">Rispetto dell’iter</th>
                    <th scope="row">Intervento dell’Organo di controllo interno.
                        Allegazione della Certificazione dell’Organo di controllo interno alla Relazione illustrativa.
                    </th>
                    <td>Non è previsto un intervento dell’Organo di controllo interno.</td>
                    <td></td>
                    <td></td>

                </tr>
                <tr>
                    <th scope="row">adempimenti procedurale
                        e degli atti propedeutici e successivi alla contrattazione
                    </th>
                    <th scope="row">controllo interno.
                        Allegazione della Certificazione dell’Organo di controllo interno alla Relazione illustrativa.
                    </th>
                    <td>L’unica certificazione dovuta è quella del Revisore dei Conti a cui è indirizzata tale
                        relazione.
                        <br>
                        In data <?php self::getInput('var17', $infos[17]['valore'], 'orange'); ?> è stata acquisita la
                        certificazione dell’Organo di controllo
                        interno <?php self::getInput('var18', $infos[18]['valore'], 'orange'); ?>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th scope="row">adempimenti procedurale
                        e degli atti propedeutici e successivi alla contrattazione
                    </th>
                    <th scope="row">Attestazione del rispetto degli obblighi di legge che in caso di inadempimento
                        comportano la sanzione del divieto di erogazione della retribuzione accessoria
                    </th>
                    <td>È stato adottato il Piano della performance 2022 previsto dall’art. 10 del d.lgs. 150/2009 con
                        Delibera del Giunta Comunale n.
                        del <?php self::getInput('var19', $infos[19]['valore'], 'black'); ?></td>
                    <td><?php self::getInput('area1', $infos[20]['valore'], 'orange'); ?>
                        <br>
                        <?php self::getInput('area2', $infos[21]['valore'], 'orange'); ?></td>
                    <td>L’organo di valutazione ha validato la relazione sulla performance relativa all’anno precedente
                        ai sensi dell’articolo 14, comma 6. del d.lgs. n. 150/2009 di cui al Verbale
                        n.<?php self::getInput('var22', $infos[22]['valore'], 'orange'); ?>. La
                        Relazione della Performance relativa all’anno corrente verrà validata in fase di
                        consuntivazione.
                    </td>
                </tr>
                <tr>
                    <th>Eventuali osservazioni:</th>
                    <td><?php self::getInput('area3', $infos[23]['valore'], 'black'); ?></td>
                </tr>
                </tbody>
            </table>
            <br>
            <h6>Modulo 2  Illustrazione dell’articolato del contratto
                (Attestazione della compatibilità con i vincoli derivanti da norme di legge e di contratto nazionale
                –modalità di utilizzo delle risorse accessorie ‑ risultati attesi ‑ altre informazioni utili)</h6>
            <br>
            a) illustrazione di quanto disposto dal contratto integrativo, in modo da fornire un quadro esaustivo della
            regolamentazione di ogni ambito/materia e delle norme legislative e contrattuali che legittimano la
            contrattazione integrativa della specifica materia trattata;
            <br>
            Per l’anno 2022 già con la determina di costituzione del Fondo n.
            del <?php self::getInput('var24', $infos[24]['valore'], 'black'); ?>, il ha reso indisponibile
            alla contrattazione ai sensi dell’art. 68 comma 1 del CCNL 21.5.2018 alcuni compensi gravanti sul fondo
            (indennità di comparto, incrementi per progressione economica, ecc) e in particolare è stato sottratto dalle
            risorse ancora contrattabili un importo complessivo pari ad
            € <?php self::getInput('var25', $infos[25]['valore'], 'black'); ?>, destinato a retribuire le indennità
            fisse e ricorrenti già determinate negli anni precedenti.
            <br>
            Per quanto riguarda il contratto decentrato per la ripartizione delle risorse dell’anno 2022 le delegazioni
            hanno confermato la destinazione delle risorse già in essere negli anni precedenti, destinando, inoltre, per
            l’anno:
            <br>
            1.
            <br>
            2. Quota recupero somme (Art. 4 DL 16/2014 Salva Roma Ter) 1.782,05
            <br>
            3. Quota annuale delle risorse decentrate finalizzata a compensare le somme indebitamente erogate negli anni
            precedenti.
            <br>
            RIFERIMENTI NORMATIVI
            <br>
            Art. 4 DL 16/2914 – Decreto Salva Roma ter
            <br>
            Le regioni e gli enti locali che non hanno rispettato i vincoli finanziari posti alla contrattazione
            collettiva integrativa sono obbligati a recuperare integralmente, a valere sulle risorse finanziarie a
            questa destinate, rispettivamente al personale dirigenziale e non dirigenziale, le somme indebitamente
            erogate mediante il graduale riassorbimento delle stesse, con quote annuali e per un numero massimo di
            annualità corrispondente a quelle in cui si e' verificato il superamento di tali vincoli. Nei predetti casi,
            le regioni ((adottano)) misure di contenimento della spesa per il personale, ulteriori rispetto a quelle già
            previste dalla vigente normativa, mediante l'attuazione di piani di riorganizzazione finalizzati alla
            razionalizzazione e allo snellimento delle strutture burocratico-amministrative, anche attraverso
            accorpamenti di uffici con la contestuale riduzione delle dotazioni organiche del personale dirigenziale in
            misura non inferiore al 20 per cento e della spesa complessiva del personale non dirigenziale In misura non
            inferiore al 10 per cento. Gli enti locali adottano le misure di razionalizzazione organizzativa garantendo
            in ogni caso la riduzione delle dotazioni organiche entro i parametri definiti dal decreto di cui
            all'articolo 263, comma 2, del decreto legislativo 18 agosto 2000, n. 267. Al fine di conseguire l'effettivo
            contenimento della spesa, alle unita' di personale eventualmente risultanti in soprannumero all'esito dei
            predetti piani obbligatori di riorganizzazione si applicano le disposizioni previste dall'articolo 2, commi
            11 e 12, del decreto-legge 6 luglio 2012, n. 95, convertito, con modificazioni, dalla legge 7 agosto 2012,
            n. 135, nei limiti temporali della vigenza della predetta norma. Le cessazioni dal servizio conseguenti alle
            misure di cui al precedente periodo non possono essere calcolate come risparmio utile per definire
            l'ammontare delle disponibilità finanziarie da destinare alle assunzioni o il numero delle unita'
            sostituibili in relazione alle limitazioni del turn over. Le Regioni e gli enti locali trasmettono entro il
            31 maggio di ciascun anno alla Presidenza del Consiglio dei Ministri - Dipartimento della funzione pubblica,
            al Ministero dell'economia e delle finanze - Dipartimento della Ragioneria generale dello Stato e al
            Ministero dell'interno - Dipartimento per gli affari interni e territoriali, ai fini del relativo
            monitoraggio,una relazione illustrativa ed una relazione tecnico-finanziaria che,con riferimento al mancato
            rispetto dei vincoli finanziari, dia conto dell'adozione dei piani obbligatori di riorganizzazione e delle
            specifiche misure previste dai medesimi per il contenimento della spesa per il personale ovvero delle misure
            di cui al terzo periodo.
            <br>
            b)Quadro di sintesi delle modalità di utilizzo da parte della contrattazione integrativa delle risorse del
            Fondo unico di amministrazione;
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">UTILIZZO FONDO</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">Totale utilizzo fondo progressioni</th>
                    <td><?php self::getInput('var26', $infos[26]['valore'], 'black'); ?></td>
                </tr>
                <tr>
                    <th scope="row">TOTALE UTILIZZO RISORSE STABILI</th>
                    <td><?php self::getInput('var26', $infos[26]['valore'], 'black'); ?></td>
                </tr>
                <tr>
                    <th scope="row">TOTALE UTILIZZO ALTRE INDENNITA’</th>
                    <td><?php self::getInput('var27', $infos[27]['valore'], 'black'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Quota recupero somme (Art. 4 DL 16/2014 Salva Roma Ter)</th>
                    <td><?php self::getInput('var28', $infos[28]['valore'], 'black'); ?></td>
                </tr>
                <tr>
                    <th scope="row">TOTALE UTILIZZO RISORSE VINCOLATE</th>
                    <td><?php self::getInput('var29', $infos[29]['valore'], 'black'); ?></td>
                </tr>
                <tr>
                    <th scope="row">TOTALE UTILIZZO FONDO</th>
                    <td><?php self::getInput('var30', $infos[30]['valore'], 'black'); ?></td>
                </tr>
                </tbody>
            </table>
            <br>
            c) Gli effetti abrogativi impliciti, in modo da rendere chiara la successione temporale dei contratti
            integrativi e la disciplina vigente delle materie demandate alla contrattazione integrativa;
            <br>
            Risultano attualmente in vigore i seguenti CCDI:
            <br>
            CCDI relativo all’anno <?php self::getInput('var31', $infos[31]['valore'], 'orange'); ?> con il quale sono
            state determinate le modalità di attribuzione dell’indennità
            di <?php self::getInput('var32', $infos[32]['valore'], 'orange'); ?>
            , <?php self::getInput('var33', $infos[33]['valore'], 'orange'); ?>
            , <?php self::getInput('var34', $infos[34]['valore'], 'orange'); ?>
            E <?php self::getInput('var35', $infos[35]['valore'], 'orange'); ?>
            <br>
            CCDI relativo all’anno <?php self::getInput('var36', $infos[36]['valore'], 'orange'); ?> con il quale sono
            state determinate le modalità di attribuzione dell’indennità
            di <?php self::getInput('var37', $infos[27]['valore'], 'orange'); ?>
            , <?php self::getInput('var38', $infos[38]['valore'], 'orange'); ?>
            , <?php self::getInput('var39', $infos[39]['valore'], 'orange'); ?>
            E <?php self::getInput('var40', $infos[40]['valore'], 'orange'); ?>
            <br>
            d) Illustrazione e specifica attestazione della coerenza con le previsioni in materia di meritocrazia e
            premialità (coerenza con il Titolo III del Decreto Legislativo n.150/2009, le norme di contratto nazionale
            e la giurisprudenza contabile) ai fini della corresponsione degli incentivi per la performance individuale
            ed organizzativa;
            <br>
            <?php self::getInput('area4', $infos[41]['valore'], 'orange'); ?>
            <br>
            <?php self::getInput('area5', $infos[42]['valore'], 'orange'); ?>
            <br>
            e) illustrazione e specifica attestazione della coerenza con il principio di selettività delle progressioni
            economiche finanziate con il Fondo per la contrattazione integrativa - progressioni orizzontali – ai sensi
            dell’articolo 23 del Decreto Legislativo n.150/2009 (previsione di valutazioni di merito ed esclusione di
            elementi automatici come l’anzianità di servizio);
            <br>
            Per l’anno 2022 non sono state previste nuove progressioni economiche orizzontali. Non sono stati
            contrattati quindi nuovi criteri anche se è stato condiviso tra le parti che il sistema utilizzato per
            valutare la performance sarà utilizzato qualora si dovessero prevedere nuove progressioni economiche.
            <br>
            1. f) illustrazione dei risultati attesi dalla sottoscrizione del contratto integrativo, in correlazione con
            gli strumenti di programmazione gestionale (Piano della Performance), adottati dall’Amministrazione in
            coerenza con le previsioni del Titolo II del Decreto Legislativo n.150/2009.
            <br>
            E’ stato approvato il Piano della Performance per l’anno 2022. Ai sensi dell’attuale Regolamento degli
            Uffici e dei Servizi ogni anno l’Ente è tenuto ad approvare un Piano della Performance che deve contenere
            gli obiettivi dell’Ente riferiti ai servizi gestiti.
            <br>
            Con la Delibera n.
            del <?php self::getInput('var43', $infos[43]['valore'], 'black'); ?>  <?php self::getInput('var44', $infos[44]['valore'], 'orange'); ?>
            Giunta Comunale ha approvato il Piano della Performance per l’anno
            2022. Tale piano è stato successivamente validato dall’organo di valutazione con il Verbale
            n. <?php self::getInput('var45', $infos[45]['valore'], 'orange'); ?>
            <br>
            Ai sensi dell’attuale Regolamento degli Uffici e dei Servizi ogni anno l’Ente è tenuto ad approvare un Piano
            della Performance che deve contenere le attività di processo dell’Ente riferiti ai servizi gestiti ed
            eventuali obiettivi strategici annuali determinati dalla Giunta Comunale.
            <br>
            Gli obiettivi contenuti nel Piano prevedono il crono programma delle attività, specifici
            indici/indicatori (quantità, qualità, tempo e costo) di prestazione attesa e il personale coinvolto. Si
            rimanda al documento per il dettaglio degli obiettivi di performance.
            <br>
            <?php self::getInput('var46', $infos[46]['valore'], 'orange'); ?> Giunta Comunale in particolare, con
            Delibera n. del <?php self::getInput('var47', $infos[47]['valore'], 'orange'); ?> con oggetto “PERSONALE NON
            DIRIGENTE.
            FONDO RISORSE DECENTRATE PER L’ANN0 2022. INDIRIZZI PER LA COSTITUZIONE. DIRETTIVE PER LA CONTRATTAZIONE
            DECENTRATA INTEGRATIVA” ha stabilito di incrementare le risorse variabili con le seguenti voci:
            <br>
            ai sensi dell’art. 67 comma 4 CCNL 21.5.2018 è stata autorizzata l’iscrizione, fra le risorse variabili,
            della quota fino ad un massimo dell'1,2% del monte salari (esclusa la quota riferita alla dirigenza)
            stabilito per l'anno 1997, nel rispetto del limite dell’anno 2016
            e <?php self::getInput('area5', $infos[48]['valore'], 'red'); ?> finalizzato
            al raggiungimento di specifici obiettivi di produttività e qualità espressamente definiti dall’Ente nel
            Piano esecutivo di Gestione 2022 unitamente al Piano della Performance approvato con Delibera della/del
            Giunta Comunale n. del <?php self::getInput('var49', $infos[49]['valore'], 'black'); ?> in merito
            a <?php self::getInput('area6', $infos[50]['valore'], 'orange'); ?>.
            <br>
            L’importo previsto è pari a € <?php self::getInput('var51', $infos[51]['valore'], 'black'); ?> che verrà
            erogato solo successivamente alla verifica dell’effettivo
            conseguimento dei risultati attesi.
            <br>
            Si precisa che gli importi, qualora non dovessero essere interamente distribuiti, non daranno luogo ad
            economie del fondo ma ritorneranno nella disponibilità del bilancio dell’Ente.
            <br>
            ai sensi dell’art. 67 c.7 e Art.15 c.7 CCNL 2018 è stata autorizzata all'iscrizione fra le risorse variabili
            la quota di incremento del Fondo trattamento accessorio per riduzione delle risorse destinate alla
            retribuzione di posizione e di risultato delle PO rispetto al tetto complessivo del salario accessorio art.
            23 c. 2 D.Lgs 75/2017, per un importo pari a € <?php self::getInput('var52', $infos[52]['valore'], 'black'); ?>;
            <br>
            g) altre informazioni eventualmente ritenute utili per la migliore comprensione degli istituti regolati dal contratto.
            <br>
            Nessun'altra informazione
            <br>
        </div>
        <div class="content-footer">
            <button id="btn-export" onclick="exportHTML();">Export to
                word doc
            </button>
        </body>
        <div class="alert alert-success alert-edit-success" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica andata a buon fine!
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="alert alert-danger alert-edit-wrong" role="alert"
             style="position:fixed; top: <?= is_admin_bar_showing() ? 47 : 15 ?>px; right: 15px; display:none">
            Modifica NON andata a buon fine
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        </html lang="en">

        <?php
    }

}