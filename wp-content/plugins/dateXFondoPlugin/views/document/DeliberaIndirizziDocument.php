<?php

namespace dateXFondoPlugin;

use DocumentRepository;

class DeliberaIndirizziDocument
{
    private $infos = [];
    private $formule = [];
    private $values = array();

    public function __construct()
    {
        $data = new DocumentRepository();
        $this->formule = $data->getFormulas($_GET['editor_name']) + $data->getIdsArticoli($_GET['editor_name']);
        $delibera_data = new DeliberaDocumentRepository();
        $this->infos = $delibera_data->getAllValues($_GET['document_name'], $_GET['editor_name']);
        foreach ($this->infos as $row) {
            $this->values[$row['chiave']] = $row['valore'];
        }
    }

    private function getInput($key, $default, $color)
    {

        $value = isset($this->values[$key]) ? $this->values[$key] : $default;
        ?>
        <span class="editable-input" data-active="false">
            <span class="variable-span-text" style="color:<?= $color ?>"><?= $value ?></span>
        <input class="variable-input-text" id="input<?= $key ?>" value="<?= $value ?>" style="display: none"
               data-key="<?= $key ?>">
        </span>

        <?php
    }

    private function getTextArea($key, $default, $color)
    {
        $value = isset($this->values[$key]) ? $this->values[$key] : $default;

        ?>
        <span class="editable-area" data-active="false">
        <span class="variable-span-area" style="color:<?= $color ?>"><?= $value ?></span>
            <textarea class=" variable-text-area form-control" id="input<?= $key ?>" data-key="<?= $key ?>"
                      style="display: none" value="<?= $value ?>"><?= $value ?></textarea>
             </span>
        <?php
    }


    private function getSelect($key, $default = '')
    {
        $value = isset($this->values[$key]) ? $this->values[$key] : $default;


        ?>
        <select class="editable-select form-control form-control-sm" data-key="<?= $key ?>">
            <option><?= $default ?></option>
            <?php
            foreach ($this->formule as $val) {
                ?>
                <option value="<?= $val[0] ?>" <?= $val[0] == $value ? 'selected' : '' ?> ><?= $val[0] ?></option>
                <?php
            }
            ?>
        </select>
        <?php
    }


    public  function render()
    {

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
            <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
            <link rel="stylesheet" href="<?= DateXFondoCommon::get_base_url() ?>/assets/styles/main.css">
            <link rel="stylesheet" href="<?= DateXFondoCommon::get_base_url() ?>/assets/styles/templateheader.css">
            <script>
                let data = {};
                //Todo sistemare il remove per la select
                function exportHTML() {
                    var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
                        "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                        "xmlns='http://www.w3.org/TR/REC-html40'>" +
                        "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title></head><body>";
                    var footer = "</body></html>";
                    const bodyHTML = $("#relazioneIllustrativaDocument").clone(true);
                    bodyHTML.remove('input');

                    var sourceHTML = header + bodyHTML.html() + footer;

                    var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
                    var fileDownload = document.createElement("a");
                    document.body.appendChild(fileDownload);
                    fileDownload.href = source;
                    var currentdate = new Date();
                    fileDownload.download = 'deliberaIndirizzi'+ "_" + currentdate.getDate() + "-"
                        + (currentdate.getMonth()+1)  + "-"
                        + currentdate.getFullYear() + '-' + 'h'+
                        + currentdate.getHours() + '-'
                        + currentdate.getMinutes() + '-'
                        + currentdate.getSeconds() + '.doc' ;
                    fileDownload.click();
                    document.body.removeChild(fileDownload);
                }
                $(document).ready(function () {
                    data = JSON.parse((`<?=json_encode($this->infos);?>`));
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
                window.onbeforeunload = confirmExit;
                function confirmExit() {
                    return "You have attempted to leave this page. Are you sure?";
                }

            </script>
        </head>

        <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                    <?php
                    \DeliberaDocumentHeader::render();
                    ?>
                </div>
                <div class="col">
                    <button class="btn btn-outline-secondary btn-export" onclick="exportHTML();">Esporta in word
                    </button>
                    <button class="btn btn-secondary btn-save-edit "> Salva modifica</button>
                </div>

            </div>
        </div>


        <div id="DeliberaIndirizziDocument">
            <h2><?php self::getInput('var1', 'var1', 'red'); ?><?php self::getInput('var2','var2', 'orange'); ?> </h2>
            <h3>OGGETTO: PERSONALE NON DIRIGENTE. FONDO RISORSE DECENTRATE PER
                L’ANNO <?php self::getInput('var3', 'var3', 'orange'); ?>. INDIRIZZI PER LA COSTITUZIONE PARTE
                VARIABILE.
                DIRETTIVE PER LA CONTRATTAZIONE DECENTRATA INTEGRATIVA.</h3>
            Visti:
            <br>
            - la deliberazione
            di <?php self::getInput('var4','var4', 'red'); ?>   <?php self::getInput('var5', 'var5', 'red'); ?>
            n. <?php self::getInput('var6', 'var6', 'orange'); ?> del
            <?php self::getInput('var7', 'var7', 'orange'); ?>, esecutiva, relativa a:
            “Bilancio di previsione <?php self::getInput('var8', 'var8', 'orange'); ?>, bilancio
            pluriennale
            e <?php self::getInput('var9', 'var9', 'red'); ?><?php self::getInput('var10', 'var10', 'red'); ?>
            ,
            piano di investimenti – approvazione”;
            <br>
            -la
            deliberazione <?php self::getInput('var11', 'var11', 'red'); ?>  <?php self::getInput('var12', 'var12', 'blue'); ?>
            n.<?php self::getInput('var13','var13', 'orange'); ?> del
            , esecutiva, relativa all’approvazione del Piano esecutivo di
            Gestione <?php self::getInput('var14', 'var14', 'orange'); ?>
            unitamente al Piano della Performance;
            <br>
            -i successivi atti di variazione del bilancio del comune e del P.E.G./Piano Performance;
            <br>
            -il vigente Regolamento di Organizzazione degli Uffici e dei Servizi;
            <br>
            -la
            deliberazione <?php self::getInput('var15', 'var15', 'red'); ?> <?php self::getInput('var16', 'var16', 'blue'); ?>
            n.<?php self::getInput('var17','var17', 'orange'); ?>
            del <?php self::getInput('var18','var18', 'orange'); ?> di
            nomina della delegazione trattante di parte pubblica abilitata alla contrattazione collettiva decentrata
            integrativa per il personale dipendente;
            <br>
            <br>
            Richiamati:
            <br>
            l’art. 48, comma 2 del D.Lgs. n. 267/2000;
            <br>
            l’art. 59, comma 1, lettera p del D.Lgs. n. 446/1997;
            <br>
            gli artt. 40, comma 3 e 40-bis del D. Lgs. n. 165/2001;
            <br>
            gli artt. 18, 19 e 31 del D.Lgs. 150/2009;
            <br>
            il CCNL siglato in data 21.5.2018, in particolare gli artt. 67, 68, 70, 56 quinquies e 56 sexies del
            C.C.N.L. 21.5.2018 e successive modifiche ed integrazioni;
            <br>
            i CCNL 31.3.1999, 1.4.1999, 14.9.2000, 5.10.2001, 22.1.2004, 9.5.2006, 11.4.2008 e 31.07.2009;
            <br>


            Premesso che in data 21.5.2018 è stato sottoscritto il Contratto Collettivo Nazionale di Lavoro del
            personale del comparto Regioni-Autonomie Locali per il triennio 2016-2018 e che il suddetto CCNL stabilisce
            all'art. 67, che le risorse finanziarie destinate alla incentivazione delle politiche di sviluppo delle
            risorse umane e della produttività vengano determinate annualmente dagli Enti, secondo le modalità definite
            da tale articolo e individua le risorse aventi carattere di certezza, stabilità e continuità nonché le
            risorse aventi caratteristiche di eventualità e di variabilità, individuando le disposizioni contrattuali
            previgenti dalla cui applicazione deriva la corretta costituzione del fondo per il salario accessorio;
            <br>
            Visto l’art. 67 comma 8 e seguenti della legge n. 133/2008 per il quale gli Enti Locali sono tenuti a
            inviare entro il 31 maggio di ogni anno alla Corte dei Conti le informazioni relative alla contrattazione
            decentrata integrativa, certificati dagli organi di controllo interno;
            <br>
            Dato atto che:
            <br>
            la dichiarazione congiunta n. 2 del C.C.N.L. del 22.1.2004 prevede che tutti gli adempimenti attuativi della
            disciplina dei contratti di lavoro sono riconducibili alla più ampia nozione di attività di gestione delle
            risorse umane, affidate alla competenza dei dirigenti e dei responsabili dei servizi che vi provvedono
            mediante l’adozione di atti di diritto comune, con la capacità ed i poteri del privato datore di lavoro e
            individua il responsabile del settore personale quale soggetto competente a costituire con propria
            determinazione il fondo di alimentazione del salario accessorio secondo i principi indicati dal contratto di
            lavoro;
            <br>

            Vista la Legge n. 15/2009 e il D.Lgs. n. 150/2009 “Attuazione della legge n. 15/2009, in materia di
            ottimizzazione della produttività del lavoro pubblico e di efficienza e trasparenza delle pubbliche
            amministrazioni”;
            <br>
            Visto il D.Lgs. n. 165/2001 “Norme generali sull’ordinamento del lavoro alle dipendenze delle
            Amministrazioni pubbliche”, con particolare riferimento alle modifiche apportate dal sopracitato D.Lgs. n.
            150/2009, e art. 40 “Contratti collettivi nazionali ed integrativi” e art. 40bis “Controlli in materia di
            contrattazione integrativa”;

            <br>
            Considerato che il D.L. 78/2010, convertito con modificazioni nella legge n. 122/2010 e ssmmii, ha previsto
            per le annualità 2011/2014 limitazioni in materia di spesa per il personale e in particolare l'art. 9 comma
            2 bis disponeva:
            <br>
            che l'ammontare complessivo delle risorse destinate annualmente al trattamento accessorio del personale,
            anche a livello dirigenziale, non può superare il corrispondente importo dell’anno 2010;
            <br>
            che l'ammontare complessivo delle risorse destinate annualmente al trattamento accessorio del personale è,
            automaticamente ridotto in misura proporzionale alla riduzione del personale in servizio;
            <br>
            Vista la Legge n. 147/2013 nota Legge di Stabilità 2014, che all'art. 1, comma 456, secondo periodo,
            inserisce all'art. 9 comma 2 bis del DL 78/2010 un nuovo periodo in cui: «A decorrere dal 1º gennaio 2015,
            le risorse destinate annualmente al trattamento economico accessorio sono decurtate di un importo pari alle
            riduzioni operate per effetto del precedente periodo», stabilendo così che le decurtazioni operate per gli
            anni 2011/2014 siano confermate e storicizzate nei fondi per gli anni successivi a partire dall'anno 2015.
            <br>
            Visto l'art. 1 c. 236 della L. 208/2015 (Legge di stabilità 2016) che stabiliva “Nelle more dell’adozione
            dei decreti legislativi attuativi degli articoli 11 e 17 della legge 7 agosto 2015, n. 124, con particolare
            riferimento all’omogeneizzazione del trattamento economico fondamentale e accessorio della dirigenza, tenuto
            conto delle esigenze di finanza pubblica, a decorrere dal 1° gennaio 2016 l’ammontare complessivo delle
            risorse destinate annualmente al trattamento accessorio del personale, anche di livello dirigenziale, [...],
            non può superare il corrispondente importo determinato per l’anno 2015 ed è, comunque, automaticamente
            ridotto in misura proporzionale alla riduzione del personale in servizio, tenendo conto del personale
            assumibile ai sensi della normativa vigente.
            <br>
            Visto l'art. 23 del D.Lgs. 75/2017 il quale stabilisce che “a decorrere dal 1° gennaio 2017, l'ammontare
            complessivo delle risorse destinate annualmente al trattamento accessorio del personale, anche di livello
            dirigenziale, di ciascuna delle amministrazioni pubbliche di cui all'articolo 1,comma 2, del decreto
            legislativo 30 marzo 2001, n. 165, non può superare il corrispondente importo determinato per l'anno 2016. A
            decorrere dalla predetta data l'articolo 1, comma 236, della legge 28 dicembre 2015, n. 208 e' abrogato.”
            <br>
            Richiamato l'art. 33 comma 2, del D.L. n. 34/2019, convertito in Legge 58/2019 (c.d. Decreto “Crescita”) e
            in particolare la previsione contenuta nell'ultimo periodo di tale comma, che modifica la modalità di
            calcolo del tetto al salario accessorio introdotto dall'articolo 23, comma 2, del D.Lgs 75/2017, modalità
            illustrata nel DM attuativo del 17.3.2020 concordato in sede di Conferenza Unificata Stato Regioni del
            11.12.2019, e che prevede che a partire dall’anno 2020 il limite del salario accessorio debba essere
            adeguato in aumento rispetto al valore medio pro-capite del 2018,
            <br>
            Vista la Determinazione dell’Area <?php self::getInput('var19', 'var19', 'red'); ?> di
            costituzione della
            parte stabile del Fondo risorse decentrate per
            l'anno <?php self::getInput('var20', 'var20', 'red'); ?>
            <br>
            <?php self::getInput('var21', 'var21', 'red'); ?>
            <br>
            Tenuto conto che nel periodo 2011-2014<?php self::getSelect('formula1', 'formula1'); ?> risultano
            decurtazioni
            rispetto ai vincoli sul fondo 2010 e
            pertanto <?php self::getSelect('formula2', 'formula2'); ?> deve essere applicata la riduzione del fondo pari
            a
            €<?php self::getSelect('formula3', 'formula3'); ?>;
            <br>
            Richiamato l’importo totale del fondo anno 2016, per le risorse soggette al limite (con esclusione dei
            compensi destinati all'avvocatura, ISTAT, art. 15 comma 1 lett. k CCNL 1.4.1999, gli importi di cui alla
            lettera d) dell’art. 15 ove tale attività non risulti ordinariamente resa dall’Amministrazione
            precedentemente l’entrata in vigore del D. Lgs. 75/2017, le economie del fondo dell’anno 2015 e delle
            economie del fondo straordinari anno 2015), pari ad € <?php self::getSelect('formula4', 'formula4'); ?>.
            <br>
            Dato atto che le ultime disposizioni individuano controlli più puntuali e stringenti sulla contrattazione
            integrativa;
            <br>
            Considerato che il D.L. 6 marzo 2014, n. 16, convertito con modificazioni dalla legge n. 68/2014, all'art. 4
            ha previsto “Misure conseguenti al mancato rispetto di vincoli finanziari posti alla contrattazione
            integrativa e all'utilizzo dei relativi fondi” e considerate la Circolare del Ministro per la
            semplificazione e la Pubblica Amministrazione del 12 maggio 2014 e il susseguente Documento della Conferenza
            delle Regioni e delle Province Autonome del 12 settembre 2014, nei quali viene precisato che ”Le regioni e
            gli enti locali che non hanno rispettato i vincoli finanziari posti alla contrattazione collettiva
            integrativa sono obbligati a recuperare integralmente, a valere sulle risorse finanziarie a questa
            destinate, rispettivamente al personale dirigenziale e non dirigenziale, le somme indebitamente erogate
            mediante il graduale riassorbimento delle stesse, con quote annuali e per un numero massimo di annualita'
            corrispondente a quelle in cui si e' verificato il superamento di tali vincoli”.
            <br>
            <?php self::getTextArea('area1', 'area1', 'red'); ?>

            <br>
            <?php self::getTextArea('area2', 'area2', 'red'); ?>
            <br>
            Premesso che:
            <br>
            il/la <?php self::getInput('var22', 'var22', 'orange'); ?> ha rispettato i vincoli previsti
            dalle
            regole del cosiddetto “Equilibrio di Bilancio” e il
            principio del tetto della spesa del personale sostenuta rispetto alla media del triennio 2011-2013;
            <br>
            il/la <?php self::getInput('var23','var23', 'orange'); ?> ha rispettato i vincoli previsti
            dalle
            regole del cosiddetto “Equilibrio di Bilancio” e il
            principio del tetto della spesa del personale sostenuta rispetto all'anno 2008;
            <br>
            il/la <?php self::getInput('var24', $infos[29]['valore'], 'orange'); ?> ha rispettato i vincoli previsti
            dalle
            regole del cosiddetto “Equilibrio di Bilancio” e il
            principio del tetto della spesa del personale sostenuta rispetto criterio riduzione spesa mancante;
            <br>
            il numero di dipendenti in servizio nel <?php self::getInput('var25','var25', 'blue'); ?>,
            calcolato in
            base alle modalità fornite dalla Ragioneria dello
            Stato da ultimo con nota Prot. 12454 del 15.1.2021, pari a <?php self::getSelect('formula5', 'formula5'); ?>
            è
            superiore al numero dei dipendenti in
            servizio al 31.12.2018 pari a <?php self::getSelect('formula6', 'formula6'); ?>, pertanto, in attuazione
            dell’art. 33
            c. 2 D.L. 34/2019 convertito nella
            L. 58/2019, il fondo e il limite di cui all’art. 23 c. 2 bis D.Lgs. 75/2017 devono essere adeguati in
            aumento al fine di garantire il valore medio pro-capite riferito al 2018;
            <br>
            il numero di dipendenti in servizio nel <?php self::getInput('var26', 'var26', 'blue'); ?>,
            calcolato in
            base alle modalità fornite dalla Ragioneria dello
            Stato da ultimo con nota Prot. 12454 del 15.1.2021, pari a<?php self::getSelect('formula7', 'formula7'); ?>
            è
            inferiore o uguale al numero dei
            dipendenti in servizio al 31.12.2018 pari a<?php self::getSelect('formula8', 'formula8'); ?>, pertanto, in
            attuazione
            dell’art. 33 c. 2 D.L. 34/2019
            convertito nella L. 58/2019, il fondo e il limite di cui all’art. 23 c. 2 bis D.Lgs. 75/2017 non devono
            essere adeguati in aumento al fine di garantire il valore medio pro-capite riferito al 2018;
            <br>
            ai sensi delle vigenti disposizioni contrattuali sono già stati erogati in corso d’anno alcuni compensi
            gravanti sul fondo (indennità di comparto, incrementi economici, ecc), frutto di precedenti accordi
            decentrati;
            <br>
            il grado di raggiungimento del Piano delle Performance assegnato nell’anno verrà certificato dall’Organismo
            di Valutazione, che accerterà il raggiungimento degli stessi ed il grado di accrescimento dei servizi a
            favore della cittadinanza
            <br>

            Considerato che:
            <br>
            è quindi necessario fornire gli indirizzi per la costituzione, del suddetto fondo relativamente all’anno
            corrente;
            <br>
            è inoltre urgente, una volta costituito il fondo suddetto, sulla base degli indirizzi di cui al presente
            atto, provvedere alla conseguente contrattazione decentrata per la distribuzione del fondo stesso;
            a tal fine è necessario esprimere fin d’ora le direttive a cui dovrà attenersi la Delegazione di Parte
            Pubblica durante la trattativa per il suddetto contratto decentrato;
            <br>
            Ritenuto di:
            <br>
            a) esprimere i seguenti indirizzi per la costituzione del fondo delle risorse decentrate di parte variabile
            del Comparto Regioni ed Autonomie Locali relativo all’anno corrente:
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 67 c. 4 CCNL 2018, delle risorse
            economiche complessive derivanti dal calcolo fino ad un massimo dell'1,2% del monte salari (esclusa la quota
            riferita alla dirigenza) stabilito per l'anno 1997, sempre rispettando il limite dell’anno 2016,
            destinandoli
            a <?php self::getTextArea('area2', 'area2', 'orange'); ?>.
            L’importo previsto è pari ad €<?php self::getSelect('formula9', 'formula9'); ?>.
            <br>
            Si precisa che gli importi, qualora non interamente distribuiti, non daranno luogo ad economie di fondo ma
            ritorneranno nella disponibilità del bilancio dell’Ente.
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 67, comma 5 lett. b) del CCNL
            21.5.2018, delle somme necessarie per il conseguimento di obiettivi dell’ente, anche di mantenimento, nonché
            obiettivi di potenziamento dei servizi di controllo finalizzati alla sicurezza urbana e stradale Art. 56
            quater CCNL 2018, definiti nel piano della performance o in altri analoghi strumenti di programmazione della
            gestione, al fine di sostenere i correlati oneri dei trattamenti accessori del personale, per un importo
            pari a € <?php self::getSelect('formula10', 'formula10'); ?>;
            <br>
            In particolare tali obiettivi sono contenuti nel Piano esecutivo di
            Gestione <?php self::getInput('var27','var27', 'orange'); ?> unitamente al Piano della
            Performance approvata con Delibera
            della/del <?php self::getInput('var28', 'var28', 'blue'); ?>
            n. <?php self::getInput('var29','var29', 'orange'); ?>
            del <?php self::getInput('var30', 'var30', 'orange'); ?> e ne vengono qui di
            seguito elencati i titoli:
            – <?php self::getInput('var31', 'var31', 'red'); ?>
            <br>
            <?php self::getTextArea('area3','area3', 'red'); ?>.
            <br>
            Si precisa che i suddetti importi, qualora non interamente distribuiti, non daranno luogo ad economie di
            fondo ma ritorneranno nella disponibilità del bilancio dell’Ente;
            <br>
            autorizzazione all’iscrizione fra le risorse variabili, ai sensi dell’art. 67 comma 3 lett. a) del CCNL
            21.5.2018 delle somme derivanti da contratti di sponsorizzazione, accordi di collaborazione, convenzioni con
            soggetti pubblici o privati e contributi dell'utenza per servizi pubblici non essenziali, secondo la
            disciplina dettata dall'art. 43 della Legge 449/1997, e soggette al limite 2015, per
            €<?php self::getSelect('formula11', 'formula11'); ?>, rispettivamente
            per <?php self::getTextArea('area4', 'area4', 'red'); ?>
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 67 comma 3 lett. c) del CCNL
            21.5.2018 delle somme destinate alle attività di recupero ICI da distribuire ai sensi del regolamento
            vigente in materia e nel rispetto della normativa vigente in materia per
            €<?php self::getSelect('formula12', 'formula12'); ?>;
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 67 comma 3 lett. c) del CCNL
            21.5.2018 delle somme destinate all’attuazione della specifica Legge
            Regionale <?php self::getTextArea('area5', 'area5', 'red'); ?> da distribuire ai sensi del
            regolamento vigente in
            materia e nel rispetto della normativa vigente in materia per
            €<?php self::getSelect('formula13', 'formula13'); ?>;
            <br>
            autorizzazione all'iscrizione, fra le risorse variabili, ai sensi dell’art. 67 comma 3 lett. f) CCNL
            21.5.2018 della quota parte del rimborso spese per ogni notificazione di atti per
            € <?php self::getSelect('formula14', 'formula14'); ?>;
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 67 comma 3 lett. e) CCNL
            21.5.2018, delle somme derivanti dai risparmi del Fondo lavoro straordinario anno precedente, pari ad
            € <?php self::getSelect('formula15', 'formula15'); ?>;
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 68 comma 1 CCNL 21.5.2018, delle
            risorse derivanti dai risparmi di parte stabile del Fondo risorse decentrate degli anni precedenti, pari ad
            €<?php self::getSelect('formula16', 'formula16'); ?>;
            <br>
            autorizzazione all’iscrizione fra le risorse variabili, ai sensi dell’art. 67 comma 3 lett. a) del CCNL
            21.5.2018 delle somme derivanti da contratti di sponsorizzazione, accordi di collaborazione, convenzioni con
            soggetti pubblici o privati e contributi dell'utenza per servizi pubblici non essenziali, secondo la
            disciplina dettata dall'art. 43 della Legge 449/1997 per € <?php self::getSelect('formula17', 'formula17'); ?>,
            rispettivamente
            per<?php self::getTextArea('area6', 'area6', 'red'); ?>;
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 67 comma 3 let. c) del CCNL
            21.5.2018 delle somme destinate agli incentivi per funzioni tecniche art. 113 comma 2 e 3 D.Lgs. n. 50/2016
            e ss.mm.ii da distribuire ai sensi del regolamento vigente in materia e nel rispetto della normativa vigente
            in materia per € <?php self::getSelect('formula18', 'formula18'); ?>;
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 67 comma 3 let. c) del CCNL
            21.5.2018 delle somme destinate alle attività svolte per conto dell’ISTAT da distribuire ai sensi dei
            regolamenti vigenti in materia e nel rispetto della normativa vigente in materia per
            € <?php self::getSelect('formula19', 'formula19'); ?>;
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’ 67 comma 3 let. c) del CCNL
            21.5.2018 delle somme destinate alla “avvocatura” da distribuire ai sensi del regolamento vigente in materia
            e nel rispetto della normativa vigente in materia per € <?php self::getSelect('formula20', 'formula20'); ?>;
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 67 comma 3 let. c) del CCNL
            21.5.2018 delle somme finanziate da fondi di derivazione dell'Unione Europea da distribuire ai sensi dei
            regolamenti vigenti in materia e nel rispetto della normativa vigente in materia per
            €<?php self::getSelect('formula21', 'formula21'); ?>;
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 67 comma 3 let. c) del CCNL
            21.5.2018 delle somme destinate alle attività di recupero IMU e TARI in riferimento all'art. 1 comma 1091
            della L. 145 del 31.12.2018 (Legge di Bilancio 2019) da distribuire ai sensi del regolamento vigente in
            materia e nel rispetto della normativa vigente in materia per
            €<?php self::getSelect('formula22', 'formula22'); ?>;
            <br>
            autorizzazione all’iscrizione, fra le risorse variabili, ai sensi dell’art. 67 comma 3 let. c) del CCNL
            21.5.2018 delle somme destinate alle
            attività <?php self::getTextArea('area7', 'area7', 'red'); ?> da distribuire
            ai sensi del regolamento vigente in materia e nel rispetto della normativa vigente in materia per
            € <?php self::getSelect('formula23', 'formula23'); ?>;
            <br>
            vista la Delibera della/del <?php self::getInput('var32','var32', 'blue'); ?>
            n.<?php self::getInput('var33', 'var33', 'orange'); ?>
            del
            <?php self::getInput('var34', 'var34', 'orange'); ?>di approvazione del Piano di
            razionalizzazione anno ai sensi dell’art. 16
            comma 5 della Legge 111/2011 e dell’art. 67 comma 3 lett. B del CCNL 21.5.2018, autorizzazione
            all’iscrizione tra le risorse variabili di €<?php self::getSelect('formula24', 'formula24'); ?>, che dovranno
            essere
            distribuite nel rigoroso rispetto dei
            principi introdotti dalla norma vigente e solo se a consuntivo verrà espresso parere favorevole da parte
            dell'Organo di Revisione;
            <br>
            autorizzazione all’iscrizione, ai sensi dell’art. 67 comma 5 lett. b) del CCNL 21.5.2018 della sola quota di
            maggior incasso rispetto all’anno precedente a seguito di obiettivi di potenziamento dei servizi di
            controllo finalizzati alla sicurezza urbana e stradale Art. 56 quater CCNL 2018, come risorsa NON soggetta
            al limite secondo dalla Corte dei Conti Sezione delle Autonomie con delibera n. 5 del 2019, per un importo
            pari a € <?php self::getSelect('formula25', 'formula25'); ?>;
            <br>
            autorizzazione all’iscrizione, ai sensi dell’art. 67 c.7 e Art.15 c.7 CCNL 2018 della quota di incremento
            del Fondo trattamento accessorio per riduzione delle risorse destinate alla retribuzione di posizione e di
            risultato delle PO rispetto al tetto complessivo del salario accessorio art. 23 c. 2 D.Lgs 75/2017, per un
            importo pari a € <?php self::getSelect('formula26', 'formula26'); ?>.
            <br>
            b) In merito all’utilizzo del fondo, fornisce i seguenti indirizzi alla delegazione trattante di parte
            pubblica
            <br>
            Dare attuazione al contratto decentrato normativo vigente nell’Ente per il
            triennio <?php self::getInput('var35','var35', 'red'); ?> siglato in
            data <?php self::getInput('var36','var36', 'red'); ?> per la ripartizione economica dell’anno
            e
            riconoscere le indennità previste, nel rispetto
            delle condizioni previste dai CCNL e
            CDIA <?php self::getTextArea('area8', 'area8', 'red'); ?>
            <br>
            Gli importi destinati alla performance dovranno essere distribuiti in relazione agli obiettivi coerenti col
            DUP e contenuti all’interno del Piano della Performance anno. Tali obiettivi dovranno avere i requisiti di
            misurabilità ed essere incrementali rispetto all’ordinaria attività lavorativa. Inoltre, le risorse
            destinate a finanziare le performance dovranno essere distribuite sulla base della valutazione da effettuare
            a consuntivo ai sensi del sistema di valutazione vigente nell’Ente e adeguato al D.Lgs. 150/2009;
            <br>
            sono fatte salve, in ogni caso, tutte le piccole modifiche non sostanziali che la delegazione ritenga
            opportune;
            <br>
            Appurato che:
            <br>
            le spese di cui al presente provvedimento non alterano il rispetto del limite delle spese di personale
            rispetto alla media del triennio 2011-2013; e ribadito che le risorse variabili verranno distribuite solo se
            sarà rispettato l’“Equilibrio di Bilancio” dell’anno corrente e solo se non saranno superati i limiti in
            materia di spesa di personale;
            <br>
            le spese di cui al presente provvedimento non alterano il rispetto del limite delle spese di personale
            rispetto all'anno 2008 e ribadito che le risorse variabili verranno distribuite solo se sarà rispettato l’
            “Equilibrio di Bilancio” dell’anno corrente e solo se non saranno superati i limiti in materia di spesa di
            personale;
            <br>
            le spese di cui al presente provvedimento non alterano il rispetto del limite delle spese di personale
            rispetto <?php self::getInput('var37','var37', 'orange'); ?> e ribadito che le
            risorse variabili verranno distribuite solo se
            sarà rispettato l’“Equilibrio di Bilancio” dell’anno corrente e solo se non saranno superati i limiti in
            materia di spesa di personale;
            <br>
            Acquisiti sulla proposta di deliberazione:
            <br>
            i pareri favorevoli, espressi sulla presente deliberazione ai sensi e per gli effetti di cui all’articolo
            49, comma 1 del D.Lgs. n. 267/2000, allegati quale parte integrante e sostanziale del presente atto;
            <br>

            a voti unanimi resi nei modi di legge
            <br>

            DELIBERA
            <br>
            1. di esprimere gli indirizzi per la costituzione variabile del fondo delle risorse decentrate di cui
            all’art. 67 del CCNL 21.5.2018 del Comparto Regioni ed Autonomie Locali relativi
            all’anno <?php self::getInput('var38','var38', 'orange'); ?> e di
            autorizzare l'inserimento delle risorse variabili nei modi e nei termini riportati in premessa;
            <br>

            2. di esprimere le direttive alle quali dovrà attenersi la Delegazione Trattante di Parte Pubblica, nel
            contrattare con la Delegazione Sindacale un’ipotesi di contratto collettivo decentrato integrativo per il
            personale non dirigente, che dovrà essere sottoposta a
            questa <?php self::getInput('var39', 'var39', 'blue'); ?> e all’organo di
            revisione contabile per l’autorizzazione e la definitiva stipula, unitamente alla relazione illustrativa e
            tecnico-finanziaria prevista ai sensi del D.Lgs. 150/2009 nei termini riportati in premessa;

            <br>
            3. di inviare il presente provvedimento al <?php self::getInput('var40', 'var40', 'orange'); ?>
            per
            l’adozione degli atti di competenza e per
            l’assunzione dei conseguenti impegni di spesa, dando atto che gli stanziamenti della spesa del personale
            attualmente previsti nel bilancio <?php self::getInput('var41', 'var41', 'orange'); ?>
            presentano la
            necessaria disponibilità.
            <br>
            4. Di inviare il presente provvedimento al Revisore dei Conti per la certificazione di competenza
            <br>

            Successivamente,
            <br>
            <?php self::getInput('var42','var42', 'orange'); ?>

            <br>
            Stante l’urgenza di provvedere
            <br>
            Visto l’art. 134 – IV comma – del D. Lgs. 267/2000;
            <br>
            Con voti favorevoli unanimi resi in forma palese
            <br>
            D E L I B E R A
            <br>
            Di rendere il presente atto immediatamente eseguibile.

        </div>
       
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
        <div class="container-fluid">
            <div class="row">
                <div class="col-8">
                </div>
                <div class="col">
                    <button class="btn btn-outline-secondary btn-export" onclick="exportHTML();">Esporta in word
                    </button>
                    <button class="btn btn-secondary btn-save-edit "> Salva modifica</button>
                </div>

            </div>
        </div>
        </html lang="en">

        <?php
    }

}