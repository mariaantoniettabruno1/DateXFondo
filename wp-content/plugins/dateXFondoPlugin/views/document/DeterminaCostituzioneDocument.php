<?php

namespace dateXFondoPlugin;

class DeterminaCostituzioneDocument
{
    private $infos = [];
    private $values = array();


    public function __construct()
    {
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
            <link rel="stylesheet" href="<?= DateXFondoCommon::get_base_url() ?>/assets/styles/main.css">
            <link rel="stylesheet" href="<?= DateXFondoCommon::get_base_url() ?>/assets/styles/templateheader.css">

            <script>
                let data = {};

                function exportHTML() {
                    var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " +
                        "xmlns:w='urn:schemas-microsoft-com:office:word' " +
                        "xmlns='http://www.w3.org/TR/REC-html40'>" +
                        "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title></head><body>";
                    var footer = "</body></html>";
                    const bodyHTML = $("#determinaCostituzioneContent").clone(true);
                    bodyHTML.find('input,textarea').remove();
                    var sourceHTML = header + bodyHTML.html() + footer;
                    var source = 'data:application/vnd.ms-word;charset=utf-8,' + encodeURIComponent(sourceHTML);
                    var fileDownload = document.createElement("a");
                    document.body.appendChild(fileDownload);
                    fileDownload.href = source;
                    var currentdate = new Date();
                    fileDownload.download = 'determinaCostituzione'+ "_" + currentdate.getDate() + "-"
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
                    <small id="warningSaveEdit" class="form-text text-dark" ><i class="fa-solid fa-triangle-exclamation text-warning"></i> Ricordati di salvare prima di uscire</small>
                </div>

            </div>
        </div>
         <h3>Determinazione del</h3>
            <h6>OGGETTO: COSTITUZIONE FONDO DELLE RISORSE DECENTRATE PER L'ANNO 2022</h6>
            <div id="determinaCostituzioneContent">
                Viste:
                <br>
                la deliberazione del <?php self::getInput('var0','var0', 'orange'); ?> n
                del <?php self::getInput('var1', 'var1', 'red'); ?>, esecutiva, relativa a: “Bilancio di
                previsione 2022,<?php self::getInput('var2', 'var2', 'red'); ?>
                e <?php self::getInput('var3','var3', 'red'); ?>, piano di investimenti – approvazione”;
                la deliberazione <?php self::getInput('var4', 'var4', 'orange'); ?> Giunta Comunale n. del
                xx.xx.xxxx, esecutiva, relativa all’approvazione del
                Piano esecutivo di Gestione 2022 unitamente al Piano della Performance;
                i successivi atti di variazione del bilancio e del P.E.G.;
                il vigente Regolamento di Organizzazione degli Uffici e dei Servizi;
                il vigente regolamento di contabilità;
                il T.U. sull’ordinamento degli Enti locali, approvato con D.Lgs. n. 267/2000;
                il C.C.D.I. per la distribuzione del fondo delle risorse decentrate 2021;
                il nuovo CCNL siglato in data 21.5.2018;
                la
                delibera <?php self::getInput('var5', 'var5', 'orange'); ?> <?php self::getInput('var6', 'var6', 'orange'); ?>
                n. del<?php self::getInput('var7', 'var7', 'black'); ?>, esecutiva ai sensi di legge,
                avente per
                oggetto: PERSONALE NON DIRIGENTE, FONDO RISORSE DECENTRATE PER L’ANNO 2022, INDIRIZZI PER LA
                COSTITUZIONE, DIRETTIVE PER LA CONTRATTAZIONE DECENTRATA INTEGRATIVA, con la quale Giunta Comunale ha
                fornito gli indirizzi per la costituzione delle risorse variabili, che si intende interamente
                richiamata;
                <br>
                Premesso che:
                <br>
                <?php self::getInput('var8','var8', 'orange'); ?><?php self::getInput('var9', 'var9', 'black'); ?>
                ha rispettato i vincoli previsti dalle regole del cosiddetto “Equilibrio di
                Bilancio” e il principio di riduzione della spesa del personale sostenuta rispetto all'anno 2008;
                <br>
                Considerato che:
                <br>
                ai sensi dell’art. 67 del CCNL 21.5.2018, devono essere annualmente destinate risorse per le politiche
                di sviluppo delle risorse umane e per la produttività collettiva e individuale;
                la costituzione di tale fondo risulta di competenza del ;
                <br>
                Ritenuto, pertanto, di procedere nella costituzione del Fondo per l’anno 2022 in adeguamento all’art. 67
                del CCNL 21.5.2018;
                <br>

                Richiamato l'art. 33 comma 2, del D.L. 34/2019, convertito in Legge 58/2019 (c.d. Decreto “Crescita”) e
                in particolare la previsione contenuta nell'ultimo periodo di tale comma, che modifica il tetto al
                salario accessorio così come introdotto dall'articolo 23, comma 2, del D.Lgs. 75/2017, modalità
                illustrata nel DM attuativo del 17.3.2020 concordato in sede di Conferenza Unificata Stato Regioni del
                11.12.2019, e che prevede che, a partire dall’anno 2020, il limite del salario accessorio debba essere
                adeguato in aumento rispetto al valore medio pro-capite del 2018 in caso di incremento del numero di
                dipendenti presenti nel 2022 rispetto ai presenti al 31.12.2018
                <br>
                Considerato che l’incremento di cui all’art. 33 D.L. 34/2019 può essere applicato sia al fondo risorse
                decentrate sia ad incremento del Fondo delle Posizioni Organizzative;

                <br>
                Considerato che il D.L. 6 marzo 2014, n. 16, convertito con modificazioni dalla legge n. 68/2014,
                all'art. 4 ha previsto “Misure conseguenti al mancato rispetto di vincoli finanziari posti alla
                contrattazione integrativa e all'utilizzo dei relativi fondi” e considerate la Circolare del Ministro
                per la semplificazione e la Pubblica Amministrazione del 12 maggio 2014 e il susseguente Documento della
                Conferenza delle Regioni e delle Province Autonome del 12 settembre 2014, nei quali viene precisato che
                ”Le regioni e gli enti locali che non hanno rispettato i vincoli finanziari posti alla contrattazione
                collettiva integrativa sono obbligati a recuperare integralmente, a valere sulle risorse finanziarie a
                questa destinate, rispettivamente al personale dirigenziale e non dirigenziale, le somme indebitamente
                erogate mediante il graduale riassorbimento delle stesse, con quote annuali e per un numero massimo di
                annualità corrispondente a quelle in cui si e' verificato il superamento di tali vincoli”.
                <br>
                Preso atto che tali verifiche e eventuali azioni correttive sono applicabili unilateralmente dagli enti,
                anche in sede di autotutela, al riscontro delle condizioni previste nell’articolo 4 del D.L. 16/2014,
                convertito nella Legge di conversione n. 68/2014, nel rispetto del diritto di informazione dovuto alle
                organizzazioni sindacali;
                <br>
                Premesso che in autotutela l’Amministrazione ha deciso di far effettuare un lavoro di verifica
                straordinaria dei Fondi delle risorse decentrate per gli anni precedenti, nel rispetto di quanto
                previsto dall'art. 4 del D.L. 6 marzo 2014, n. 16, convertito con modificazioni dalla legge n. 68/2014
                <br>
                Ritenuto, pertanto, di procedere ad una verifica straordinaria sulla correttezza dei fondi pregressi ai
                sensi dell'art. 4 del D.L. 6 marzo 2014, n. 16, convertito con modificazioni dalla legge n. 68/2014 e
                alla costituzione del Fondo per l’anno;
                <br>
                Dato atto che dalla verifica effettuata sulla correttezza della costituzione e l'utilizzo dei fondi
                pregressi ai sensi dell'art. 4 del D.L. 6 marzo 2014, n. 16, convertito con modificazioni dalla legge n.
                68/2014, <?php self::getTextArea('area2', 'area2', 'red'); ?>
                <br>
                <?php self::getTextArea('area3', 'area3', 'red'); ?>
                <br>
                Considerato che:
                <br>
                l’art. 67 comma 1 del CCNL 21.5.2018 ha definito che le risorse aventi carattere di certezza, stabilità
                e continuità determinate nell’anno 2017 secondo la previgente disciplina contrattuale, vengono definite
                in un unico importo che resta confermato, con le stesse caratteristiche, anche per gli anni successivi
                per un importo pari ad € <?php self::getInput('var12','var12', 'black'); ?>;
                <br>
                ai sensi dell’art. 67 comma 2 lettera b) del CCNL 22.5.2018 si inseriscono le somme di un importo pari
                alle differenze tra gli incrementi a regime di cui all’art. 64 CCNL 2018 riconosciuti alle posizioni
                economiche di ciascuna categoria e gli stessi incrementi riconosciuti alle posizioni iniziali; tali
                differenze sono calcolate con riferimento al personale in servizio alla data in cui decorrono gli
                incrementi e confluiscono nel fondo a decorrere dalla medesima data, per
                € <?php self::getInput('var13', 'var13', 'red'); ?>. Tali somme, ai sensi
                della dichiarazione congiunta n. 5 del CCNL 2018, non sono assoggettate ai limiti di crescita dei Fondi
                previsti dalle norme vigenti ed in particolare all’art. 23 del D.Lgs. 75/2017, così come confermato
                definitivamente dalla Delibera della Corte dei Conti Sezione delle Autonomie n. 19/2018;
                <br>
                ai sensi dell’art. 67 comma 2 lettera a) del CCNL 22.5.2018 si inseriscono le somme di un importo su
                base annua, pari a Euro 83,20 per le unità di personale destinatarie del presente CCNL in servizio alla
                data del 31.12.2015, a decorrere dal 31.12.2018 e a valere dall’anno 2019, per
                € <?php self::getInput('var14', 'var14', 'red'); ?>. Tali somme, ai
                sensi della dichiarazione congiunta n. 5 del CCNL 2018, non sono assoggettate ai limiti di crescita dei
                Fondi previsti dalle norme vigenti ed in particolare all’art. 23 del D.Lgs. 75/2017, così come
                confermato definitivamente dalla Delibera della Corte dei Conti Sezione delle Autonomie n. 19/2018;
                <br>
                Tenuto conto che:
                <br>
                il numero di dipendenti in servizio nel 2022, calcolato in base alle modalità fornite dalla Ragioneria
                dello Stato da ultimo con nota Prot. 12454 del 15.1.2021, pari a 4,00 è inferiore o uguale al numero dei
                dipendenti in servizio al 31.12.2018 pari a 3,00, pertanto, in attuazione dell’art. 33 c. 2 D.L. 34/2019
                convertito nella L. 58/2019, il fondo e il limite di cui all’art. 23 c.2 D.Lgs. 75/2017 non devono
                essere adeguati in aumento al fine di garantire il valore medio pro-capite riferito al 2018;
                <?php self::getTextArea('area4', 'area4', 'red'); ?>;
                <br>
                Le risorse aventi carattere di certezza, stabilità e continuità determinate nell’anno 2022 ai sensi
                dell’art. 67 commi 1 e 2 del CCNL 21.5.2018, e adeguate alle disposizioni del D.L. 34/2019, risultano
                pertanto essere pari ad €<?php self::getInput('var16','var16', 'red'); ?>, di cui
                € <?php self::getInput('var17', 'var17', 'red'); ?> soggette ai vincoli;
                <br>
                Preso atto che:
                <br>
                è stato autorizzato l'inserimento delle voci variabili di cui all’art. 67 comma 3 CCNL 21.5.2018
                sottoposte al limite dell’anno 2016, di cui all’art. 23 del D.Lgs. 75/2017 e pertanto vengono stanziate:
                ai sensi dell’art. 67 comma 4 CCNL 21.5.2018, le risorse economiche derivanti dal calcolo fino ad un
                massimo dell'1,2% del monte salari anno 1997 (esclusa la quota riferita alla dirigenza), per un importo
                pari ad € <?php self::getInput('var18', 'var18', 'red'); ?>.
                <br>
                L’utilizzo è conseguente alla verifica dell’effettivo conseguimento dei risultati attesi.
                ai sensi dell’art. 67 c. 7 e Art.15 c. 7 CCNL 2018 le somme pari alla quota di incremento del Fondo
                trattamento accessorio per riduzione delle risorse destinate alla retribuzione di posizione e di
                risultato delle PO rispetto al tetto complessivo del salario accessorio art. 23 c. 2 D.Lgs 75/2017, per
                un importo pari a € <?php self::getInput('var19', 'var19', 'red'); ?>
                <br>
                Ritenuto:
                <br>
                di integrare le risorse variabili di cui all’art. 67 comma 3 CCNL 21.5.2018, in base alla normativa
                vigente, degli importi NON soggetti al limite del 2016, di cui all’art. 23 del D.Lgs. 75/2017 mediante:

                <br>
                Considerato che:
                <br>
                l'importo totale del fondo delle risorse variabili per l’anno 2022 risulta pari ad
                € <?php self::getInput('var20', 'var20', 'red'); ?>, di cui €
                <?php self::getInput('var21', 'var21', 'red'); ?> soggette ai vincoli;
                <br>
                Vista la Legge n. 147/2013 nota Legge di Stabilità 2014, che all'art. 1, comma 456, secondo periodo,
                inserisce all'art. 9 comma 2 bis del DL 78/2010 un nuovo periodo in cui: «A decorrere dal 1º gennaio
                2015, le risorse destinate annualmente al trattamento economico accessorio sono decurtate di un importo
                pari alle riduzioni operate per effetto del precedente periodo», stabilendo così che le decurtazioni
                operate per gli anni 2011/2014 siano confermate e storicizzate nei fondi per gli anni successivi a
                partire dall'anno 2015.
                <br>
                Considerato che il D.L. 78/2010, convertito con modificazioni nella legge n. 122/2010 e ssmmii, ha
                previsto per le annualità 2011/2014 limitazioni in materia di spesa per il personale e in particolare
                l'art. 9 comma 2 bis disponeva:
                <br>
                che l'ammontare complessivo delle risorse destinate annualmente al trattamento accessorio del personale,
                anche a livello dirigenziale, non può superare il corrispondente importo dell’anno 2010;
                che l'ammontare complessivo delle risorse destinate annualmente al trattamento accessorio del personale
                è, automaticamente ridotto in misura proporzionale alla riduzione del personale in servizio
                <br>
                Vista la circolare n. 20 del 2015 della Ragioneria Generale dello Stato sulle modalità di calcolo delle
                decurtazioni per l'anno 2015;
                <br>
                Tenuto conto che nel periodo 2011-2014 risultano decurtazioni rispetto ai vincoli sul fondo 2010 e
                pertanto deve essere applicata la riduzione del fondo del 2022, pari a
                € <?php self::getInput('var22', 'var22', 'red'); ?>;
                <br>
                Richiamato l'art. 1 c. 236 della L. 208/2015 che aveva proposto dei nuovi limiti sui fondi delle risorse
                decentrate stabilendo che a decorrere dal 1° gennaio 2016 l'ammontare complessivo delle risorse
                destinate annualmente al trattamento accessorio del personale:
                <br>
                non poteva superare il corrispondente importo dell’anno 2015;
                <br>
                doveva essere automaticamente ridotto in misura proporzionale alla riduzione del personale in servizio,
                tenendo conto del personale assumibile ai sensi della normativa vigente.
                <br>
                Visto l'art. 23 del D.Lgs. 75/2017 il quale stabilisce che “a decorrere dal 1° gennaio 2017, l'ammontare
                complessivo delle risorse destinate annualmente al trattamento accessorio del personale, anche di
                livello dirigenziale, di ciascuna delle amministrazioni pubbliche di cui all'articolo 1, comma 2, del
                decreto legislativo 30 marzo 2001, n. 165, non può superare il corrispondente importo determinato per
                l'anno 2016. A decorrere dalla predetta data l'articolo 1, comma 236, della legge 28 dicembre 2015, n.
                208 e' abrogato.”
                <br>

                Tenuto conto che nell'anno 2016 non risultano decurtazioni rispetto ai vincoli sul fondo 2015 e pertanto
                non deve essere applicata la riduzione del fondo di
                € <?php self::getInput('var23', 'var23', 'red'); ?>;
                <br>

                Pertanto:
                <br>
                l'importo del fondo complessivo 2022 da confrontare con il 2016 e da sottoporre alle decurtazioni di cui
                all'art. 23 del D.Lgs. 75/2017, risulta pari a
                € <?php self::getInput('var24','var24', 'red'); ?>;, di cui
                € <?php self::getInput('var25', 'var25', 'red'); ?>; soggette al limite 2016;
                <br>
                Vista la costituzione del fondo per l’anno 2016, che per le risorse soggette al limite, risultava (con
                esclusione di: avvocatura, ISTAT, di cui art. 67 comma 3 lett. c CCNL 21.5.2018, importi di cui all’art.
                67 comma 3 lett. c CCNL 21.5.2018, importi di cui all’67 comma 3 lett. a, ove tale attività non risulti
                ordinariamente resa dall’Amministrazione precedentemente l’entrata in vigore del D.Lgs. 75/2017,
                economie del fondo dell’anno 2015 e economie del fondo straordinario anno 2015), pari a
                €<?php self::getInput('var26', 'var26', 'red'); ?>;
                <br>
                e che lo stesso non deve essere adeguato in riferimento alle disposizioni del D.L. 34/2019 e di quanto
                definito DM attuativo del 17.3.2020 concordato in sede di Conferenza Unificata Stato Regioni del
                11.12.2019, per garantire l'invarianza del valore medio pro-capite riferito all'anno 2018 e pertanto il
                totale del limite di cui all'art. 23 del D.Lgs. 75/2017 è confermato pari ad
                € <?php self::getInput('var27','var27', 'red'); ?>;
                <br>

                Vista la costituzione del fondo per l’anno 2022, che per le risorse soggetto al limite (con esclusione
                di: avvocatura, ISTAT, di cui art. 67 comma 3 lett. c CCNL 21.5.2018, importi di cui all’art. 67 comma 3
                lett. c CCNL 21.5.2018, importi di cui all’art. 67 comma 3 lett. a, ove tale attività non risulti
                ordinariamente resa dall’Amministrazione precedentemente l’entrata in vigore del D.Lgs 75/2017, importi
                di cui all’art. 67 comma 2 lett.b, economie del fondo dell’anno precedente e economie del fondo
                straordinario anno precedente), risulta pari a
                € <?php self::getInput('var28', 'var28', 'red'); ?>;
                <br>

                Dato atto che ai sensi dell’art. 33 del DL 34/2019 il salario accessorio complessivo è stato
                incrementato di un importo pari a <?php self::getInput('var29','var29', 'red'); ?>; di
                cui:
                Fondo risorse decentrate, come indicato nei paragrafi precedenti per €
                Fondo Posizioni organizzative pari a € <?php self::getInput('var30','var30', 'red'); ?>;
                <br>

                Considerato che
                <br>
                il limite di cui all’art. 23 c. 2 del D.Lgs. 75/2017 deve essere rispettato per l’amministrazione nel
                suo complesso, in luogo che distintamente per le diverse categorie di personale (es. dirigente e non
                dirigente) che operano nell’amministrazione, così come chiarito da diverse ma costanti indicazioni di
                sezioni regionali della Corte dei Conti e dal MEF e RGS;
                <br>
                Preso atto che il fondo 2022 (per le voci soggette al blocco del D.Lgs. 75/2017) deve essere decurtato
                per il superamento del limite del fondo 2016 per un valore pari
                ad <?php self::getInput('var31', 'var31', 'red'); ?>;
                Preso atto che il fondo 2022 (per le voci soggette al blocco del D.Lgs. 75/2017) non deve essere
                decurtato poiché non supera il limite del fondo 2016;
                <br>
                Considerato che:
                il totale del fondo (incluse le sole voci soggette al blocco dell’art. 23 del D.Lgs. 75/2017) per l’anno
                2022 al netto delle decurtazioni per il superamento del valore del 2016 è pari ad
                € <?php self::getInput('var32', 'var32', 'red'); ?>;;
                Il totale del fondo complessivo (incluse le voci non soggette al blocco dell’art. 23 del D.Lgs. 75/2017)
                per l’anno 2022 tolte le decurtazioni per il superamento del valore del 2016 è pari ad
                € <?php self::getInput('var33', 'var33', 'red'); ?>;
                il tetto del salario accessorio di cui all’art. 23 c. 2 del D.Lgs. 75/2017 nel suo complesso (indennità
                di Posizione e Risultato, Fondo risorse decentrate e Fondo straordinario) per l’anno 2022 risulta
                <?php self::getInput('var34', 'var34', 'red'); ?>; al 2016 come illustrato nella tabella
                sotto:
                <br>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">TOTALE SALARIO ACCESSORIO per rispetto tetto art. 23 c. 2 del D.Lgs 75/2017</th>
                    </tr>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">ANNO 20106</th>
                        <th scope="col">ANNO 2022</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>

                        <td>Fondo complessivo risorse decentrate soggette al limite</td>
                        <td>  <?php self::getInput('var35', 'var35', 'red'); ?></td>
                        <td>@<?php self::getInput('var36', 'var36', 'red'); ?></td
                        </td>
                    </tr>
                    <tr>
                        <td>Indennità di Posizione e risultato PO</td>
                        <td>  <?php self::getInput('var37', 'var37', 'red'); ?></td>
                        <td> <?php self::getInput('var38', 'var38', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>Fondo Straordinario</td>
                        <td> <?php self::getInput('var39', 'var39', 'red'); ?></td>
                        <td> <?php self::getInput('var40', 'var40', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>Quota di incremento valore medio procapite del trattamento accessorio rispetto al 2018 -
                            Art. 33 c. 2 DL 34/2019- aumento virtuale limite 2016
                        </td>
                        <td> <?php self::getInput('var41', 'var41', 'red'); ?></td>
                        <td> <?php self::getInput('var42', 'var42', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>TOTALE TRATTAMENTO ACCESSORIO SOGGETTO AL LIMITE ART. 23 C. 2 D.LGS 75/2017</td>
                        <td> <?php self::getInput('var43','var43', 'red'); ?></td>
                        <td> <?php self::getInput('var44','var44', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>RISPETTO DEL LIMITE TRATTAMENTO ACCESSORIO</td>
                        <td> <?php self::getInput('var45','var45', 'red'); ?></td>
                        <td> <?php self::getInput('var46','var46', 'red'); ?></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">TOTALE FONDO RISORSE DECENTRATE</th>
                    </tr>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">ANNO 20106</th>
                        <th scope="col">ANNO 2022</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>

                        <td>Fondo stabile soggetto al limite</td>
                        <td><?php self::getInput('var47','var47', 'red'); ?></td>
                        <td><?php self::getInput('var48','var48', 'red'); ?></td
                        </td>
                    </tr>
                    <tr>
                        <td>Fondo variabile soggetta al limite</td>
                        <td> <?php self::getInput('var49', 'var49', 'red'); ?></td>
                        <td> <?php self::getInput('var50', 'var50', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>Risorse fondo prima delle decurtazioni</td>
                        <td> <?php self::getInput('var51','var51', 'red'); ?></td>
                        <td> <?php self::getInput('var52','var52', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>Decurtazioni 2011/2014
                        </td>
                        <td> <?php self::getInput('var53', 'var53', 'red'); ?></td>
                        <td> <?php self::getInput('var54', 'var54', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>Decurtazioni operate nel 2016 per cessazioni e rispetto limite 2015</td>
                        <td> <?php self::getInput('var55', 'var55', 'red'); ?></td>
                        <td> <?php self::getInput('var56', 'var56', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>Decurtazioni per rispetto 2016</td>
                        <td> <?php self::getInput('var57', 'var57', 'red'); ?></td>
                        <td> <?php self::getInput('var58', 'var58', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>RISORSE FONDO DOPO LE DECURTAZIONI</td>
                        <td> <?php self::getInput('var59','var59', 'red'); ?></td>
                        <td> <?php self::getInput('var60','var60', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>Risorse stabili NON sottoposte al limite</td>
                        <td> <?php self::getInput('var61','var61', 'red'); ?></td>
                        <td> <?php self::getInput('var62','var62', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>Risorse variabili NON sottoposte al limite</td>
                        <td> <?php self::getInput('var63','var63', 'red'); ?></td>
                        <td> <?php self::getInput('var64','var64', 'red'); ?></td>
                    </tr>
                    <tr>
                        <td>TOTALE FONDO DECURTATO, INCLUSE LE SOMME NON SOTTOPOSTE AL LIMITE</td>
                        <td> <?php self::getInput('var65', 'var65', 'red'); ?></td>
                        <td> <?php self::getInput('var66', 'var66', 'red'); ?></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                Preso atto che risulta indisponibile alla contrattazione una quota di
                € <?php self::getInput('var67', 'var67', 'red'); ?> in quanto relativa alla
                remunerazione di istituti erogabili in forma automatica e già precedentemente contrattati e assegnati
                (es. indennità di comparto e progressione orizzontale);
                <br>

                Visto l’allegato prospetto di costituzione del fondo anno 2022;
                <br>

                DETERMINA
                <br>
                per quanto in premessa indicato e che qui si intende integralmente richiamato:
                <br>
                1. di costituire il fondo risorse decentrate anno 2022, approvando l’allegato schema di costituzione;
                <br>
                2. di applicare l'art. 23 del D.Lgs. 75/2017 che prevede il “blocco” rispetto al fondo dell'anno 2016
                del trattamento accessorio, con l’automatica riduzione delle risorse in caso di superamento rispetto
                all’anno 2016;
                <br>
                3. di applicare l’art. 33 comma 2, del D.L.34/2019, convertito in Legge 58/2019 (c.d. Decreto
                “Crescita”) che modifica la modalità di calcolo del tetto al salario accessorio introdotto dall'articolo
                23, comma 2, del D.Lgs. 75/2017, come definito DM attuativo del 17.3.2020 concordato in sede di
                Conferenza Unificata Stato Regioni del 11.12.2019, e che prevede che, a partire dall’anno 2020, il
                limite del salario accessorio debba essere adeguato in aumento rispetto al valore medio pro-capite del
                2018, nel caso risulti un incremento del numero di dipendenti presenti al 31.12.2022 rispetto ai
                presenti al 31.12.2018;
                <br>
                4. di costituire il fondo complessivo a seguito della decurtazione di cui all'art. 23 del D.Lgs 75/2017
                per un importo pari ad € <?php self::getInput('var68','var68', 'red'); ?>;
                <bt
                        5. di prendere atto che la somma totale risulta stanziata così come segue:
                <br>
                per€ <?php self::getInput('var69','var69', 'red'); ?> al
                Cap. <?php self::getInput('var70','var70', 'red'); ?>;
                <br>
                per € <?php self::getInput('var71', 'var71', 'red'); ?> al
                Cap. <?php self::getInput('var72', 'var72', 'red'); ?>“Fondo miglioramento efficienza”
                competenza <?php self::getInput('var73','var73', 'red'); ?>-
                impegno <?php self::getInput('var74', 'var74', 'red'); ?>
                etc….
                <br>
                6. di sottrarre dalle risorse contrattabili i compensi gravanti sul fondo (indennità di comparto,
                incrementi per la progressione economica, ecc) che, ai sensi delle vigenti disposizioni contrattuali,
                sono già stati erogati in corso d’anno per un importo pari ad
                € <?php self::getInput('var75', 'var75', 'red'); ?>;
                <br>
                7. che il grado di raggiungimento del Piano delle Performance assegnato nel 2022 al Dirigente/Posizioni
                Organizzative, verrà certificato dall’Organismo di Valutazione, che accerterà il raggiungimento degli
                obiettivi ed il grado di accrescimento dei servizi a favore della cittadinanza;
                <br>
                8. che il presente provvedimento diventerà esecutivo solo a seguito dell’apposizione del visto di
                regolarità contabile attestante la copertura finanziaria ai sensi del comma 4 dell'art. 151 del TUEL,
                D.Lgs. n. 267/2000, da parte del servizio finanziario cui si trasmette di competenza.
                <br>
                9. di trasmettere la presente al Revisore dei Conti per la certificazione di competenza.
                <br>
                10. di trasmettere la presente alle Organizzazioni Sindacali Territoriali e alle RSU per opportuna
                conoscenza e informazione.
                <br>


                Il
                <br>
                <?php self::getInput('var76', 'var76', 'red'); ?>

                <br>

                VISTO DI REGOLARITA’ CONTABILE
                <br>
                Si attesta la regolarità contabile e la copertura finanziaria della spesa ai sensi del comma 4 dell'art.
                151 del TUEL, approvato con D.lgs. n. 267/2000.
                <br>

                Il Responsabile
                <br>
                <?php self::getInput('var77', 'var77', 'red'); ?>

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
                    <small id="warningSaveEdit" class="form-text text-dark" ><i class="fa-solid fa-triangle-exclamation text-warning"></i> Ricordati di salvare prima di uscire</small>
                </div>

            </div>
        </div>
        </html lang="en">

        <?php
    }

}