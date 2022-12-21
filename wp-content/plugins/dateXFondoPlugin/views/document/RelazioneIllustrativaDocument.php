<?php

namespace dateXFondoPlugin;

use DocumentRepository;

class RelazioneIllustrativaDocument
{
    private $formule = [];

    /**
     * @param array $formule
     */
    public function __construct()
    {
        $data = new DocumentRepository();
        $this->formule = $data->getFormulas('Emanuele Lesca') + $data->getIdsArticoli('Emanuele Lesca');
    }


    private static function getInput($key, $value, $color)
    {

        ?>
        <span class="editable-input" data-active="false">
            <span class="variable-span-text" style="color:<?= $color ?>"><?= $value ?></span>
        <input class="variable-input-text" id="input<?= $key ?>" value="<?= $value ?>" style="display: none"
               data-key="<?= $key ?>">
        </span>

        <?php
    }

    private static function getTextArea($key, $value, $color)
    {
        ?>
        <span class="editable-area" data-active="false">
        <span class="variable-span-area" style="color:<?= $color ?>"><?= $value ?></span>
            <textarea class=" variable-text-area form-control" id="input<?= $key ?>" data-key="<?= $key ?>"
                      style="display: none" value="<?= $value ?>"><?= $value ?></textarea>
             </span>
        <?php
    }

    private function getSelect($key, $value='')
    {

        ?>
        <select class="editable-select form-control form-control-sm" data-key="<?= $key ?>">
            <?php
            foreach ($this->formule as $val) {
                ?>
                <option value="<?= $val[0] ?>" <?= $val[0] == $value ? 'selected' : ''?> ><?= $val[0] ?></option>
                <?php
            }
            ?>
        </select>
        <?php
    }

    public function render()
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
                    const bodyHTML = $("#determinaCostituzioneContent").clone(true);
                    bodyHTML.remove('input');

                    var sourceHTML = header + bodyHTML.html() + footer;

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
                        <br>
                        <?php self::getInput('var4', $infos[4]['valore'], 'orange'); ?> – Presidente
                        <br>
                        <?php self::getInput('var5', $infos[5]['valore'], 'orange'); ?> - Componente
                        <br>
                        <?php self::getInput('var6', $infos[6]['valore'], 'orange'); ?> - Componente
                        <br>
                        <?php self::getInput('var7', $infos[7]['valore'], 'orange'); ?> - Componente
                        <br>

                        Organizzazioni sindacali ammesse alla contrattazione (elenco sigle):
                        <br>
                        SIND. FP CGIL
                        <br>
                        SIND. CISL FP
                        <br>
                        SIND. UIL FPL
                        <br>
                        SIND. CSA REGIONI AUTONOMIE LOCALI
                        <br>
                        R.S.U.:
                        <br>
                        Signor <?php self::getInput('var8', $infos[8]['valore'], 'orange'); ?>
                        <br>
                        Signor <?php self::getInput('var9', $infos[9]['valore'], 'orange'); ?>
                        <br>
                        Signor <?php self::getInput('var10', $infos[10]['valore'], 'orange'); ?>
                        <br>
                        Signor <?php self::getInput('var11', $infos[11]['valore'], 'orange'); ?>
                        <br>
                        Organizzazioni sindacali firmatarie (elenco sigle):
                        <br>
                        SIND. FP CGIL signor <?php self::getInput('var12', $infos[12]['valore'], 'orange'); ?>
                        <br>
                        SIND. CISL FP signor <?php self::getInput('var13', $infos[13]['valore'], 'orange'); ?>
                        <br>
                        SIND. UIL FPL signor <?php self::getInput('var14', $infos[14]['valore'], 'orange'); ?>
                        <br>
                        SIND. CSA REGIONI AUTONOMIE LOCALI
                        <br>
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
                    <th scope="row">e degli atti propedeutici e successivi alla contrattazione adempimenti procedurali
                        rispetto dell'iter
                    </th>
                    <th scope="row">Intervento dell’Organo di controllo interno.
                        Allegazione della Certificazione dell’Organo di controllo interno alla Relazione illustrativa.
                    </th>
                    <td>Non è previsto un intervento dell’Organo di controllo interno.</td>
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
                    <th scope="row">e degli atti propedeutici e successivi alla contrattazione adempimenti procedurali
                        rispetto dell'iter
                    </th>
                    <th scope="row">Attestazione del rispetto degli obblighi di legge che in caso di inadempimento
                        comportano la sanzione del divieto di erogazione della retribuzione accessoria
                    </th>
                    <td>È stato adottato il Piano della performance 2022 previsto dall’art. 10 del d.lgs. 150/2009 con
                        Delibera del Giunta Comunale n.
                        del <?php self::getInput('var19', $infos[19]['valore'], 'black'); ?></td>
                    <td><?php self::getTextArea('area1', $infos[20]['valore'], 'orange'); ?>
                        <br>
                        <?php self::getTextArea('area2', $infos[21]['valore'], 'orange'); ?></td>
                    <td>L’organo di valutazione ha validato la relazione sulla performance relativa all’anno precedente
                        ai sensi dell’articolo 14, comma 6. del d.lgs. n. 150/2009 di cui al Verbale
                        n.<?php self::getInput('var22', $infos[22]['valore'], 'orange'); ?>. La
                        Relazione della Performance relativa all’anno corrente verrà validata in fase di
                        consuntivazione.
                    </td>
                </tr>
                <tr>
                    <th>Eventuali osservazioni:</th>
                    <td><?php self::getTextArea('area3', $infos[23]['valore'], 'black'); ?></td>
                </tr>
                </tbody>
            </table>
            <br>
            <h6>Modulo 2 Illustrazione dell’articolato del contratto
                (Attestazione della compatibilità con i vincoli derivanti da norme di legge e di contratto nazionale
                –modalità di utilizzo delle risorse accessorie ‑ risultati attesi ‑ altre informazioni utili)</h6>
            <br>
            a) illustrazione di quanto disposto dal contratto integrativo, in modo da fornire un quadro esaustivo della
            regolamentazione di ogni ambito/materia e delle norme legislative e contrattuali che legittimano la
            contrattazione integrativa della specifica materia trattata;
            <br>
            Per l’anno ><?php self::getInput('var24', $infos[24]['valore'], 'blue'); ?> già con la determina di
            costituzione del Fondo n.><?php self::getInput('var25', $infos[25]['valore'], 'blue'); ?> del
            ><?php self::getInput('var26', $infos[26]['valore'], 'blue'); ?>, il
            ><?php self::getInput('var27', $infos[27]['valore'], 'blue'); ?> ha reso indisponibile alla contrattazione
            ai sensi dell’art. 68
            comma 1 del CCNL 21.5.2018 alcuni compensi gravanti sul fondo (indennità di comparto, incrementi per
            progressione economica, ecc) e in particolare è stato sottratto dalle risorse ancora contrattabili un
            importo complessivo pari ad € <?php self::getSelect('formula1'); ?>, destinato a retribuire le indennità
            fisse e ricorrenti già determinate
            negli anni precedenti.
            <br>
            Per quanto riguarda il contratto decentrato per la ripartizione delle risorse
            dell’anno <?php self::getInput('var28', $infos[28]['valore'], 'blue'); ?> le delegazioni
            hanno confermato la destinazione delle risorse già in essere negli anni precedenti, destinando, inoltre, per
            l’anno:
            <br>
            1. Progressioni economiche orizzontali specificatamente contrattate nel CCDI dell'anno (art. 68 comma 1 CCNL
            21.5.2018) € <?php self::getSelect('formula2'); ?>.
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var29', $infos[29]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            l’attribuzione delle progressioni:
            <br>
            <?php self::getTextArea('area4', $infos[30]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 1 CCNL 21.5.2018
            <br>
            Gli enti rendono annualmente disponibili tutte le risorse confluite nel Fondo risorse decentrate, al netto
            delle risorse necessarie per corrispondere i differenziali di progressione economica, al personale
            beneficiario delle stesse in anni precedenti.
            <br>
            Art. 16 CCNL 21.5.2018
            <br>
            1. All’interno di ciascuna categoria è prevista una progressione economica che si realizza mediante
            l’acquisizione, in sequenza, dopo il trattamento tabellare iniziale, di successivi incrementi retributivi,
            corrispondenti ai valori delle diverse posizioni economiche a tal fine espressamente previste.
            <br>
            2. La progressione economica di cui al comma 1, nel limite delle risorse effettivamente disponibili, è
            riconosciuta, in modo selettivo, ad una quota limitata di dipendenti, determinata tenendo conto anche degli
            effetti applicativi della disciplina del comma 6.
            <br>
            3. Le progressioni economiche sono attribuite in relazione alle risultanze della valutazione della
            performance individuale del triennio che precede l’anno in cui è adottata la decisione di attivazione
            dell’istituto, tenendo conto eventualmente a tal fine anche dell’esperienza maturata negli ambiti
            professionali di riferimento, nonché delle competenze acquisite e certificate a seguito di processi
            formativi.
            <br>
            4. Gli oneri relativi al pagamento dei maggiori compensi spettanti al personale che ha beneficiato della
            disciplina sulle progressioni economiche orizzontali sono interamente a carico della componente stabile del
            Fondo risorse decentrate di cui all’art. 67.
            <br>
            5. Gli oneri di cui al comma 4 sono comprensivi anche della quota della tredicesima mensilità.
            <br>
            6. Ai fini della progressione economica orizzontale, il lavoratore deve essere in possesso del requisito di
            un periodo minimo di permanenza nella posizione economica in godimento pari a ventiquattro mesi.
            <br>
            7. L’attribuzione della progressione economica orizzontale non può avere decorrenza anteriore al 1° gennaio
            dell’anno nel quale viene sottoscritto il contratto integrativo che prevede l’attivazione dell’istituto, con
            la previsione delle necessarie risorse finanziarie.
            <br>
            8. L’esito della procedura selettiva ha una vigenza limitata al solo anno per il quale è stata prevista
            l’attribuzione della progressione economica.
            <br>
            9. Il personale comandato o distaccato presso enti, amministrazioni, aziende ha diritto di partecipare alle
            selezioni per le progressioni orizzontali previste per il restante personale dell’ente di effettiva
            appartenenza. A tal fine l’ente di appartenenza concorda le modalità per acquisire dall’ente di
            utilizzazione le informazioni e le eventuali valutazioni richieste secondo la propria disciplina.
            Art. 23 D.lgs 150/2009 Progressioni economiche
            <br>
            1. Le amministrazioni pubbliche riconoscono selettivamente le progressioni economiche di cui all'articolo
            52, comma 1-bis, del decreto legislativo 30 marzo 2001, n.165, come introdotto dall'articolo 62 del presente
            decreto, sulla base di quanto previsto dai contratti collettivi nazionali e integrativi di lavoro e nei
            limiti delle risorse disponibili.
            <br>
            2. Le progressioni economiche sono attribuite in modo selettivo, ad una quota limitata di dipendenti, in
            relazione allo sviluppo delle competenze professionali ed ai risultati individuali e collettivi rilevati dal
            sistema di valutazione.
            <br>
            Articolo 52 Disciplina delle mansioni D.lgs 165/2001
            <br>
            1 bis. Le progressioni all'interno della stessa area avvengono secondo principi di selettività, in funzione
            delle qualità culturali e professionali, dell'attività svolta e dei risultati conseguiti, attraverso
            l'attribuzione di fasce di merito. La valutazione positiva conseguita dal dipendente per almeno tre anni
            costituisce titolo rilevante ai fini della progressione economica
            <br>
            2. Indennità di turno (art. 68 comma 2 lett. d CCNL 21.5.2018) € <?php self::getSelect('formula3'); ?>
            <br>
            <?php self::getTextArea('area5', $infos[31]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. d CCNL 21.5.2018
            <br>
            D) il pagamento delle indennità di turno;
            <br>
            Art. 23 CCNL 22.5.2018
            <br>
            1. Gli enti, in relazione alle proprie esigenze organizzative o di servizio funzionali, possono istituire
            turni giornalieri di lavoro. Il turno consiste in un’effettiva rotazione del personale in prestabilite
            articolazioni giornaliere.
            <br>
            2. Le prestazioni lavorative svolte in turnazione, ai fini della corresponsione della relativa indennità,
            devono essere distribuite nell’arco di un mese, sulla base della programmazione adottata, in modo da attuare
            una distribuzione equilibrata ed avvicendata dei turni effettuati in orario antimeridiano, pomeridiano e, se
            previsto,notturno, in relazione all’articolazione adottata dall’ente.
            <br>
            3. Per l'adozione dell'orario di lavoro su turni devono essere osservati i seguenti criteri:
            a) la ripartizione del personale nei vari turni deve avvenire sulla base delle professionalità necessarie in
            ciascun turno;
            <br>
            b) l'adozione dei turni può anche prevedere una parziale e limitata sovrapposizione tra il personale
            subentrante e quello del turno precedente, con durata limitata alle esigenze dello scambio delle consegne;
            c) all'interno di ogni periodo di 24 ore deve essere garantito un periodo di riposo dialmeno 11 ore
            consecutive;
            <br>
            d) i turni diurni, antimeridiani e pomeridiani, possono essere attuati in strutture operative che prevedano
            un orario di servizio giornaliero di almeno 10 ore;
            <br>
            e) per turno notturno si intende il periodo lavorativo ricompreso dalle ore 22 alle ore 6 del giorno
            successivo; per turno notturno-festivo si intende quello che cade nel periodo compreso tra le ore 22 del
            giorno prefestivo e le ore 6 del giorno festivo e dalle ore 22 del giorno festivo alle ore 6 del giorno
            successivo.
            <br>
            4. Fatte salve eventuali esigenze eccezionali o quelle dovute a eventi o calamità naturali, il numero dei
            turni notturni effettuabili nell'arco del mese da ciascun dipendente non può essere superiore a 10.
            <br>
            5. Al fine di compensare interamente il disagio derivante dalla particolare articolazione dell’orario di
            lavoro, al personale turnista è corrisposta una indennità, i cui valori sono stabiliti come segue:
            <br>
            a) turno diurno, antimeridiano e pomeridiano (tra le 6,00 e le 22,00): maggiorazione oraria del 10% della
            retribuzione di cui all’art. 10, comma 2, lett. c) del CCNL del 9.5.2006;
            <br>
            b) turno notturno o festivo: maggiorazione oraria del 30% della retribuzione di cui all’art. 10, comma 2,
            lett. c) del CCNL del 9.5.2006;
            <br>
            c) turno festivo-notturno: maggiorazione oraria del 50% della retribuzione di cui all’art. 10, comma 2,
            lett. c) del CCNL del 9.5.2006.
            <br>
            6. L’indennità di cui al comma 5, è corrisposta per i soli periodi di effettiva prestazione in turno.
            <br>
            7. Agli oneri derivanti dal presente articolo si fa fronte, in ogni caso, con le risorse previste dall’art.
            67.
            <br>
            a) 8. Il personale che si trovi in particolari situazioni personali e familiari, di cui all’art.27, comma 4
            può, a richiesta, essere escluso dalla effettuazione di turni notturni,anche in relazione a quanto previsto
            dall’art. 53, comma 2, del D.Lgs. n. 151/2001. Sono comunque escluse le donne dall'inizio dello stato di
            gravidanza e nel periodo di allattamento fino ad un anno di vita del bambino.
            <br>
            3. Indennità condizioni di lavoro (Art. 68 comma 2 lett. c CCNL 2018) (Maneggio valori, attività disagiate e
            esposte a rischi) <?php self::getSelect('formula4'); ?>
            <br>
            Viene ripreso il testo del contratto siglato per l’anno 202x con il quale sono stati definiti i criteri di
            attribuzione delle seguenti indennità:
            <?php self::getTextArea('area6', $infos[32]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 70 bis CCNL 21.5.2018
            <br>
            1. Gli enti corrispondono una unica “indennità condizioni di lavoro” destinata a remunerare lo svolgimento
            di attività: a) disagiate; b) esposte a rischi e, pertanto, pericolose o dannose per la salute; c)
            implicanti il maneggio di valori.
            <br>
            2. L’indennità di cui al presente articolo è commisurata ai giorni di effettivo svolgimento delle attività
            di cui al comma 1, entro i seguenti valori minimi e massimi giornalieri: Euro 1,00 – Euro 10,00.
            <br>
            3. La misura di cui al comma 1 è definita in sede di contrattazione integrativa di cui all’art. 7, comma 4,
            sulla base dei seguenti criteri: a) valutazione dell’effettiva incidenza di ciascuna delle causali di cui al
            comma 1 nelle attività svolte dal dipendente; b) caratteristiche istituzionali, dimensionali, sociali e
            ambientali degli enti interessati e degli specifici settori di attività.
            <br>
            4. Gli oneri per la corresponsione dell’indennità di cui al presente articolo sono a carico del Fondo
            risorse decentrate di cui all’art. 67.
            <br>
            5. La presente disciplina trova applicazione a far data dal primo contratto integrativo successivo alla
            stipulazione del presente CCNL.
            <br>
            4. Indennità di reperibilità (art. 68 comma 2 lett. d CCNL 21.5.2018)
            € <?php self::getSelect('formula5'); ?>
            <br>
            <?php self::getTextArea('area7', $infos[33]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. d CCNL 21.5.2018
            <br>
            D) il pagamento delle indennità di reperibilità;
            <br>
            Art. 24 CCNL 21.5.2018
            <br>
            1. Per le aree di pronto intervento individuate dagli enti, può essere istituito il servizio di pronta
            reperibilità. Esso è remunerato con la somma di € 10,33 per 12 ore al giorno. Ai relativi oneri si fa fronte
            in ogni caso con le risorse previste dall’art. 67. Tale importo è raddoppiato in caso di reperibilità
            cadente in giornata festiva, anche infrasettimanale o di riposo settimanale secondo il turno assegnato.
            <br>
            2. In caso di chiamata l’interessato dovrà raggiungere il posto di lavoro assegnato nell’arco di trenta
            minuti.
            <br>
            3. Ciascun dipendente non può essere messo in reperibilità per più di 6 volte in un mese; gli enti
            assicurano la rotazione tra più soggetti anche volontari.
            <br>
            4. In sede di contrattazione integrativa, secondo quanto previsto dall’art. 7, comma 4, è possibile elevare
            il limite di cui al comma 3 nonché la misura dell’indennità di cui al comma 1, fino ad un massimo di €
            13,00.
            <br>
            5. L’indennità di reperibilità di cui ai commi 1 e 4 non compete durante l’orario di servizio a qualsiasi
            titolo prestato. Detta indennità è frazionabile in misura non inferiore a quattro ore ed è corrisposta in
            proporzione alla sua durata oraria maggiorata, in tal caso, del 10%. Qualora la pronta reperibilità cada di
            domenica o comunque di riposo settimanale secondo il turno assegnato, il dipendente ha diritto ad un giorno
            di riposo compensativo anche se non è chiamato a rendere alcuna prestazione lavorativa. Nella settimana in
            cui fruisce del riposo compensativo, il lavoratore è tenuto a rendere completamente l'orario ordinario di
            lavoro previsto. La fruizione del riposo compensativo non comporta, comunque, alcuna riduzione dell’orario
            di lavoro settimanale.
            <br>
            6. In caso di chiamata, le ore di lavoro prestate vengono retribuite come lavoro straordinario o compensate,
            a richiesta, ai sensi dell’art.38, comma 7, e dell’art.38bis, del CCNL del 14.9.2000 o con equivalente
            recupero orario; per le stesse ore è esclusa la percezione del compenso di cui ai commi 1 e 4.
            <br>
            7. La disciplina del comma 6 non trova applicazione nell’ipotesi di chiamata del lavoratore in reperibilità
            cadente nella giornata del riposo settimanale, secondo il turno assegnato; per tale ipotesi trova
            applicazione, invece, la disciplina di cui all’art.24, comma 1, del CCNL del 14.9.2000.
            <br>
            5. Indennità Specifiche Responsabilità (art. 68, c. 2, lett e CCNL 21.5.2018 ex art. 17, c. 2, lett f. CCNL
            01/04/99) € <?php self::getSelect('formula6'); ?>
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var34', $infos[34]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri di
            attribuzione dell’indennità di Specifiche responsabilità :
            <br>
            <?php self::getTextArea('area8', $infos[35]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 70-quinquies CCNL 21.5.2018
            <br>
            1. Per compensare l’eventuale esercizio di compiti che comportano specifiche responsabilità, al personale
            delle categorie B, C e D, che non risulti incaricato di posizione organizzativa ai sensi dell’art.13 e
            seguenti , può essere riconosciuta una indennità di importo non superiore a € 3.000 annui lordi.
            <br>
            6. Indennità di funzione (Art. 68 comma 2 lett. f CCNL 21.5.2018 e art. 56 sexies CCNL 21.5.2018)
            (Vigilanza) € <?php self::getSelect('formula7'); ?>;
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. f CCNL 21.5.2018
            <br>
            f) indennità di funzione di cui all’art. 56-sexies
            <br>
            Art. 56 sexies CCNL 21.5.2018
            <br>
            1. Gli enti possono erogare al personale inquadrato nelle categorie C e D, che non risulti incaricato di
            posizione organizzativa, una indennità di funzione per compensare l’esercizio di compiti di responsabilità
            connessi al grado rivestito.
            <br>
            2. L’ammontare dell’indennità di cui al comma 1 è determinato, tenendo conto specificamente del grado
            rivestito e delle connesse responsabilità, nonché delle peculiarità dimensionali, istituzionali, sociali e
            ambientali degli enti, fino a un massimo di € 3.000 annui lordi, da corrispondere per dodici mensilità.
            <br>
            3. Il valore dell’indennità di cui al presente articolo, nonché i criteri per la sua erogazione, nel
            rispetto di quanto previsto al comma 2, sono determinati in sede di contrattazione integrativa di cui
            all’art. 7.
            <br>
            4. L’indennità di cui al comma 1 sostituisce per il personale di cui al presente titolo l’indennità di
            specifiche responsabilità, di cui all’art. 70 quinquies, comma 1.
            <br>
            5. L’indennità di cui al presente articolo: a) è cumulabile con l’indennità di turno, di cui all’art. 23,
            comma 5; b) è cumulabile con l’indennità di cui all’art. 37, comma 1, lett. b), del CCNL del 6.7.1995 e
            successive modificazioni ed integrazioni; c) è cumulabile con l’indennità di cui all’art. 56-quinquies; d) è
            cumulabile con i compensi correlati alla performance individuale e collettiva; e) non è cumulabile con le
            indennità di cui all’art. 70-quinquies;
            <br>
            5. Gli oneri per la corresponsione dell’indennità di cui al presente articolo sono a carico del Fondo
            risorse decentrate di cui all’art. 67.
            <br>
            6. La presente disciplina trova applicazione a far data dal primo contratto integrativo successivo alla
            stipulazione del presente CCNL.
            <br>
            7. Specifiche responsabilità (art. 68, c. 2, lett e CCNL 21.5.2018 ex art. 17, c. 2, lett i. CCNL 01/04/99 )
            € <?php self::getSelect('formula8'); ?>
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var36', $infos[36]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri di
            attribuzione dell’indennità di Specifiche responsabilità :
            <br>
            <?php self::getTextArea('area9', $infos[37]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. f CCNL 21.5.2018
            <br>
            f) indennità di servizio esterno di cui all’art.56-quater;
            <br>
            Art. 56 quinquies CCNL 21.5.2018
            <br>
            1. Al personale che, in via continuativa, rende la prestazione lavorativa ordinaria giornaliera in servizi
            esterni di vigilanza, compete una indennità giornaliera, il cui importo è determinato entro i seguenti
            valori minimi e massimi giornalieri: Euro 1,00 - Euro 10,00.
            <br>
            2. L’indennità di cui al comma 1 è commisurata alle giornate di effettivo svolgimento del servizio esterno e
            compensa interamente i rischi e disagi connessi all’espletamento dello stesso in ambienti esterni.
            <br>
            3. L’indennità di cui al presenta articolo: a) è cumulabile con l’indennità di turno, di cui all’art. 23,
            comma 5; b) è cumulabile con le indennità di cui all’art. 37, comma 1, lett. b), del CCNL del 6.7.1995 e
            successive modificazioni ed integrazioni; c) è cumulabile con i compensi connessi alla performance
            individuale e collettiva; d) non è cumulabile con l’indennità di cui all’art. 70-bis.
            <br>
            4. Gli oneri per la corresponsione dell’indennità di cui al presente articolo sono a carico del Fondo
            risorse decentrate di cui all’art. 67.
            <br>
            5. La presente disciplina trova applicazione a far data dal primo contratto integrativo successivo alla
            stipulazione del presente CCNL.
            <br>
            9. Particolare compenso incentivante personale Unioni dei comuni (art. 68, c. 1 CCNL 21.5.2018)
            € <?php self::getSelect('formula9'); ?>
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var38', $infos[38]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            attribuire il compenso incentivante:
            <br>
            <?php self::getTextArea('area10', $infos[39]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 13 c.5 lett a CCNL 22.1.2004
            <br>
            5. Al fine di favorire la utilizzazione temporanea anche parziale del personale degli enti da parte
            dell’unione, la contrattazione decentrata della stessa unione può disciplinare, con oneri a carico delle
            risorse disponibili ai sensi del comma 3:
            <br>
            a) la attribuzione di un particolare compenso incentivante, di importo lordo variabile, in base alla
            categoria di appartenenza e alle mansioni affidate, non superiore a € 25, su base mensile, strettamente
            correlato alle effettive prestazioni lavorative;
            <br>
            10. Centri estivi asili nido (art. 68, c. 1 CCNL 21.5.2018 e art 31 comma 5 CCNL 14/9/ 2000)
            € <?php self::getSelect('formula10'); ?>
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var40', $infos[40]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            attribuire l’indennità prevista per il personale del nido estivo :
            <br>
            <?php self::getTextArea('area10', $infos[39]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art 31 comma 5 CCNL 14 -9- 2000
            zbr>
            5. Il calendario scolastico, che non può in ogni caso superare le 42 settimane, prevede l’interruzione per
            Natale e Pasqua, le cui modalità attuative sono definite in sede di concertazione. In tali periodi e negli
            altri di chiusura delle scuole il personale è a disposizione per attività di formazione ed aggiornamento
            programmata dall’ente o per attività lavorative connesse al profilo di inquadramento fermo restando il
            limite definito nei commi precedenti. Attività ulteriori, rispetto a quelle definite nel calendario
            scolastico, possono essere previste a livello di ente, in sede di concertazione, per un periodo non
            superiore a quattro settimane, da utilizzarsi sia per le attività dei nidi che per altre attività
            d’aggiornamento professionale, di verifica dei risultati e del piano di lavoro, nell’ambito dei progetti di
            cui all’art.17, co.1, lett. a) del CCNL dell’1.4.1999; gli incentivi economici di tali attività sono
            definiti in sede di contrattazione integrativa decentrata utilizzando le risorse di cui all’art.15 del
            citato CCNL.
            <br>
            11. Maggiorazione per il personale che presta attività lavorativa nel giorno destinato al riposo settimanale
            (Art. 68 comma 2 lett. d CCNL 21.5.2018 e art.24, comma 1 CCNL 14.9.2000)
            € <?php self::getSelect('formula11'); ?>
            <br>
            <?php self::getTextArea('area11', $infos[40]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. d CCNL 21.5.2018
            <br>
            D) compensi di cui all’art. 24, comma 1 del CCNL del 14.9.2000;
            <br>
            Art. 24 comma 1 CCNL 14.9.2000
            <br>
            1. Al dipendente che per particolari esigenze di servizio non usufruisce del giorno di riposo settimanale
            deve essere corrisposta la retribuzione giornaliera di cui all’art.52, comma 2, lett. b) maggiorata del 50%,
            con diritto al riposo compensativo da fruire di regola entro 15 giorni e comunque non oltre il bimestre
            successivo.
            <br>
            12. Premi collegati alla performance organizzativa (art. 68, c. 2, lett a. CCNL 22.5.2018)
            € <?php self::getSelect('formula12'); ?>
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var41', $infos[41]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione della performance:
            <br>
            <?php self::getTextArea('area12', $infos[42]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art.18 D.lgs 150/2009 “Criteri e modalità per la valorizzazione del merito ed
            incentivazione della performance”
            <br>
            1. Le amministrazioni pubbliche promuovono il merito e il miglioramento della performance organizzativa e
            individuale, anche attraverso l'utilizzo di sistemi premianti selettivi, secondo logiche meritocratiche,
            nonché valorizzano i dipendenti che conseguono le migliori performance attraverso l'attribuzione selettiva
            di incentivi sia economici sia di carriera.
            <br>
            2. E' vietata la distribuzione in maniera indifferenziata o sulla base di automatismi di incentivi e premi
            collegati alla performance in assenza delle verifiche e attestazioni sui sistemi di misurazione e
            valutazione adottati ai sensi del presente decreto.
            <br>
            Parere Aran 499-18A8.
            <br>
            Riteniamo che la produttività collettiva possa essere correlata al conseguimento di specifici risultati e/o
            obiettivi assegnati dall'ente ad un gruppo o a una struttura, con la individuazione anche di uno specifico
            finanziamento definito in sede di contrattazione decentrata. La contrattazione decentrata deve,
            naturalmente, stabilire anche i criteri per la valutazione, da parte dei dirigenti, dell'apporto dei singoli
            lavoratori al conseguimento del risultato complessivo.
            <br>
            Suggeriamo, in ogni caso, di non attribuire troppo rilievo all'una o all'altra forma di incentivazione;
            nella sostanza occorre sempre assicurare un corretto percorso di valutazione che ogni ente è tenuto ad
            adottare, previa concertazione, ai sensi dell'art.6 del CCNL del 31.3.99.
            <br>
            13. Premi collegati alla performance individuale (art. 68, c. 2, lett b. CCNL 22.5.2018)
            € <?php self::getSelect('formula13'); ?>
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var43', $infos[43]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione della performance individuale:
            <br>
            <?php self::getTextArea('area13', $infos[44]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. B CCNL 22.5.2018
            <br>
            B) premi correlati alla performance individuale
            <br>
            Art. 69 CCNL 21.5.2018
            <br
                    1. Ai dipendenti che conseguano le valutazioni più elevate, secondo quanto previsto dal sistema di
                    valutazione dell’ente, è attribuita una maggiorazione del premio individuale di cui all’art. 68,
                    comma 2,
                    lett.b), che si aggiunge alla quota di dettopremio attribuita al personale valutato positivamente
                    sulla base
                    dei criteri selettivi.
            <br>
            2. La misura di detta maggiorazione, definita in sede di contrattazione integrativa, non potrà comunque
            essere inferiore al 30% del valore medio pro-capite dei premi attribuiti al personale valutato positivamente
            ai sensi del comma 1.
            <br>
            3. La contrattazione integrativa definisce altresì, preventivamente, una limitata quota massima di personale
            valutato, a cui tale maggiorazione può essere attribuita.
            <br>
            Art.18 D.lgs 150/2009 “Criteri e modalità per la valorizzazione del merito ed
            incentivazione della performance”
            <br>
            1. Le amministrazioni pubbliche promuovono il merito e il miglioramento della performance organizzativa e
            individuale, anche attraverso l'utilizzo di sistemi premianti selettivi, secondo logiche meritocratiche,
            perché valorizzano i dipendenti che conseguono le migliori performance attraverso l'attribuzione selettiva
            di incentivi sia economici sia di carriera.
            <br>
            2. E' vietata la distribuzione in maniera indifferenziata o sulla base di automatismi di incentivi e premi
            collegati alla performance in assenza delle verifiche e attestazioni sui sistemi di misurazione e
            valutazione adottati ai sensi del presente decreto.
            <br>
            Parere Aran 499-18A8.
            <br>
            La produttività individuale potrebbe essere individuata come momento di verifica e di valutazione di ogni
            singolo lavoratore, da parte del competente dirigente, con riferimento agli impegni di lavoro specifici
            derivanti dall'affidamento dei compiti da parte del competente dirigente.
            <br>
            Suggeriamo, in ogni caso, di non attribuire troppo rilievo all'una o all'altra forma di incentivazione;
            nella sostanza occorre sempre assicurare un corretto percorso di valutazione che ogni ente è tenuto ad
            adottare, previa concertazione, ai sensi dell'art.6 del CCNL del 31.3.99.
            <br>
            14. Premi collegati alla performance organizzativa - Incentivazione legata al raggiungimento di obiettivi ai
            sensi dell'art. 67 c.5 lett. b parte variabile (art. 68, c. 2, lett a. CCNL 21.5.2018)
            € <?php self::getSelect('formula14'); ?>
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var45', $infos[45]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione di tali risorse:
            <br>
            <?php self::getTextArea('area14', $infos[46]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. a CCNL 21.5.2018
            <br>
            A) premi correlati alla performance organizzativa;
            <br>
            Art.18 D.lgs 150/2009 “Criteri e modalità per la valorizzazione del merito ed
            incentivazione della performance”
            <br>
            1. Le amministrazioni pubbliche promuovono il merito e il miglioramento della performance organizzativa e
            individuale, anche attraverso l'utilizzo di sistemi premianti selettivi, secondo logiche meritocratiche,
            perché valorizzano i dipendenti che conseguono le migliori performance attraverso l'attribuzione selettiva
            di incentivi sia economici sia di carriera.
            <br>
            2. E' vietata la distribuzione in maniera indifferenziata o sulla base di automatismi di incentivi e premi
            collegati alla performance in assenza delle verifiche e attestazioni sui sistemi di misurazione e
            valutazione adottati ai sensi del presente decreto.
            <br>
            15. Premi collegati alla performance organizzativa per obiettivi finanziati da risorse art 67 c. 5 lett. b)
            di potenziamento dei servizi di controllo finalizzati alla sicurezza urbana e stradale art. 56 quater CCNL
            21.5.2018 ) € <?php self::getSelect('formula15'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var47', $infos[47]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione di tali risorse:
            <br>
            <?php self::getTextArea('area15', $infos[48]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. a CCNL 21.5.2018
            <br>
            A) premi correlati alla performance organizzativa;
            <br>
            Art.18 D.lgs 150/2009 “Criteri e modalità per la valorizzazione del merito ed
            incentivazione della performance”
            <br>
            1. Le amministrazioni pubbliche promuovono il merito e il miglioramento della performance organizzativa e
            individuale, anche attraverso l'utilizzo di sistemi premianti selettivi, secondo logiche meritocratiche,
            perché valorizzano i dipendenti che conseguono le migliori performance attraverso l'attribuzione selettiva
            di incentivi sia economici sia di carriera.
            <br>
            2. E' vietata la distribuzione in maniera indifferenziata o sulla base di automatismi di incentivi e premi
            collegati alla performance in assenza delle verifiche e attestazioni sui sistemi di misurazione e
            valutazione adottati ai sensi del presente decreto.
            <br>
            Art. 56 quater CCNL 21.5.2018
            <br>
            1. I proventi delle sanzioni amministrative pecuniarie riscossi dagli enti, nella quota da questi
            determinata ai sensi dell’art. 208, commi 4 lett.c), e 5, del D.Lgs.n.285/1992 sono destinati, in coerenza
            con le previsioni legislative, alle seguenti finalità in favore del personale: a) contributi datoriali al
            Fondo di previdenza complementare Perseo-Sirio; è fatta salva la volontà del lavoratore di conservare
            comunque l’adesione eventualmente già intervenuta a diverse forme pensionistiche individuali; b) finalità
            assistenziali, nell’ambito delle misure di welfare integrativo, secondo la disciplina dell’art. 72; c)
            erogazione di incentivi monetari collegati a obiettivi di potenziamento dei servizi di controllo finalizzati
            alla sicurezza urbana e stradale.
            <br>
            16. Altre risorse specificatamente contrattate nel CCDI
            dell'anno <?php self::getInput('var49', $infos[49]['valore'], 'red'); ?>
            € <?php self::getSelect('formula16'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var50', $infos[50]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione:
            <br>
            <?php self::getTextArea('area16', $infos[51]['valore'], 'orange'); ?>
            <br>
            17. Incentivazione funzioni tecniche (art. 68, c. 2, lett. g CCNL 21.5.2018)
            € <?php self::getSelect('formula17'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var52', $infos[52]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area17', $infos[53]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. g CCNL 21.5.2018
            <br>
            G) compensi previsti da disposizioni di legge, riconosciuti esclusivamente a valere sulle risorse di cui
            all’art. 67, comma 3, lett. c), ivi compresi i compensi di cui all’art.70-ter;
            <br>
            Art. 67 comma 3 lett. c
            <br>
            C) delle risorse derivanti da disposizioni di legge che prevedano specifici trattamenti economici in favore
            del personale, da utilizzarsi secondo quanto previsto dalle medesime disposizioni di legge;
            <br>
            Art. 113 comma 2 e 3 D.LGS. 18 APRILE 2016, N. 50
            <br>
            2. A valere sugli stanziamenti di cui al comma 1, le amministrazioni aggiudicatrici destinano ad un apposito
            fondo risorse finanziarie in misura non superiore al 2 per cento modulate sull'importo dei lavori, servizi e
            forniture, posti a base di gara per le funzioni tecniche svolte dai dipendenti delle stesse esclusivamente
            per le attivita' di programmazione della spesa per investimenti, di valutazione preventiva dei progetti, di
            predisposizione e di controllo delle procedure di gara e di esecuzione dei contratti pubblici, di RUP, di
            direzione dei lavori ovvero direzione dell'esecuzione e di collaudo tecnico amministrativo ovvero di
            verifica di conformita', di collaudatore statico ove necessario per consentire l'esecuzione del contratto
            nel rispetto dei documenti a base di gara, del progetto, dei tempi e costi prestabiliti. Tale fondo non e'
            previsto da parte di quelle amministrazioni aggiudicatrici per le quali sono in essere contratti o
            convenzioni che prevedono modalita' diverse per la retribuzione delle funzioni tecniche svolte dai propri
            dipendenti. Gli enti che costituiscono o si avvalgono di una centrale di committenza possono destinare il
            fondo o parte di esso ai dipendenti di tale centrale. La disposizione di cui al presente comma si applica
            agli appalti relativi a servizi o forniture nel caso in cui e' nominato il direttore dell'esecuzione. 3.
            L'ottanta per cento delle risorse finanziarie del fondo costituito ai sensi del comma 2 e' ripartito, per
            ciascuna opera o lavoro, servizio, fornitura con le modalita' e i criteri previsti in sede di contrattazione
            decentrata integrativa del personale, sulla base di apposito regolamento adottato dalle amministrazioni
            secondo i rispettivi ordinamenti, tra il responsabile unico del procedimento e i soggetti che svolgono le
            funzioni tecniche indicate al comma 2 nonche' tra i loro collaboratori. Gli importi sono comprensivi anche
            degli oneri previdenziali e assistenziali a carico dell'amministrazione. L'amministrazione aggiudicatrice o
            l'ente aggiudicatore stabilisce i criteri e le modalita' per la riduzione delle risorse finanziarie connesse
            alla singola opera o lavoro a fronte di eventuali incrementi dei tempi o dei costi non conformi alle norme
            del presente decreto. La corresponsione dell'incentivo e' disposta dal dirigente o dal responsabile di
            servizio preposto alla struttura competente, previo accertamento delle specifiche attivita' svolte dai
            predetti dipendenti. Gli incentivi complessivamente corrisposti nel corso dell'anno al singolo dipendente,
            anche da diverse amministrazioni, non possono superare l'importo del 50 per cento del trattamento economico
            complessivo annuo lordo. Le quote parti dell'incentivo corrispondenti a prestazioni non svolte dai medesimi
            dipendenti, in quanto affidate a personale esterno all'organico dell'amministrazione medesima, ovvero prive
            del predetto accertamento, incrementano la quota del fondo di cui al comma 2. Il presente comma non si
            applica al personale con qualifica dirigenziale.
            <br>
            18. Incentivazione specifiche attività - AVVOCATURA (art. 68, c. 2, lett. g CCNL 21.5.2018)
            € <?php self::getSelect('formula18'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var54', $infos[54]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area18', $infos[55]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. g CCNL 21.5.2018
            <br>
            G) compensi previsti da disposizioni di legge, riconosciuti esclusivamente a valere sulle risorse di cui
            all’art. 67, comma 3, lett. c), ivi compresi i compensi di cui all’art.70-ter;
            <br>
            Art. 67 comma 3 lett. c
            <br>
            C) delle risorse derivanti da disposizioni di legge che prevedano specifici trattamenti economici in favore
            del personale, da utilizzarsi secondo quanto previsto dalle medesime disposizioni di legge;
            <br>
            Art. 27 CCNL 14.9.2000
            <br>
            1. Gli enti provvisti di Avvocatura costituita secondo i rispettivi ordinamenti disciplinano la
            corresponsione dei compensi professionali, dovuti a seguito di sentenza favorevole all’ente, secondo i
            principi di cui al regio decreto legge 27.11.1933 n. 1578 e disciplinano, altresì, in sede di contrattazione
            decentrata integrativa la correlazione tra tali compensi professionali e la retribuzione di risultato di cui
            all’art. 10 del CCNL del 31.3.1999. Sono fatti salvi gli effetti degli atti con i quali gli stessi enti
            abbiano applicato la disciplina vigente per l’Avvocatura dello Stato anche prima della stipulazione del
            presente CCNL.
            <br>
            19. Incentivazione specifiche attività - ISTAT (art. 68, c. 2, lett. g CCNL 21.5.2018)
            € <?php self::getSelect('formula19'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var56', $infos[56]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area19', $infos[57]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. g CCNL 21.5.2018
            <br>
            G) compensi previsti da disposizioni di legge, riconosciuti esclusivamente a valere sulle risorse di cui
            all’art. 67, comma 3, lett. c), ivi compresi i compensi di cui all’art.70-ter;
            <br>
            Art. 70 ter CCNL 21.5.2018
            <br>
            1. Gli enti possono corrispondere specifici compensi al personale per remunerare prestazioni connesse a
            indagini statistiche periodiche e censimenti permanenti, rese al di fuori dell’ordinario orario di lavoro.
            <br>
            2. Gli oneri concernenti l’erogazione dei compensi di cui al presente articolo trovanocopertura
            esclusivamente nella quota parte del contributo onnicomprensivo eforfetario riconosciuto dall’Istat e dagli
            Enti e Organismi pubblici autorizzati perlegge, confluita nel Fondo Risorse decentrate, ai sensi dell’art.
            67, comma 3, lett. c).
            <br>
            20. Incentivazione specifiche attività - ICI (art. 68, c. 2, lett. g CCNL 21.5.2018)
            € <?php self::getSelect('formula20'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var58', $infos[58]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area20', $infos[59]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. g CCNL 21.5.2018
            <br>
            G) compensi previsti da disposizioni di legge, riconosciuti esclusivamente a valere sulle risorse di cui
            all’art. 67, comma 3, lett. c), ivi compresi i compensi di cui all’art.70-ter;
            <br>
            Art. 4 CCNL del 5/10/2001 comma 3 Integrazione risorse dell'art. 15 del CCNL dell'1/4/1999
            <br>
            La disciplina dell'art. 15, comma 1, lett. k) del CCNL dell'1.4.1999, ricomprende sia le risorse derivanti
            dalla applicazione dell'art. 3, comma 57 della legge n. 662 del 1996 e dall'art. 59, comma 1, lett. p) del
            D. Lgs.n.446 del 1997 (recupero evasione ICI), sia le ulteriori risorse correlate agli effetti applicativi
            dell'art. 12, comma 1, lett. del D.L. n. 437 del 1996, convertito nella legge n. 556 del 1996
            Art. 3, comma 57 della legge n. 662 del 1996
            <br>
            57. Una percentuale del gettito dell'imposta comunale sugli immobili puo' essere destinata al potenziamento
            degli uffici tributari del comune. I dati fiscali a disposizione del comune sono ordinati secondo procedure
            informatiche, stabilite con decreto del Ministro delle finanze, allo scopo di effettuare controlli
            incrociati coordinati con le strutture dell'amministrazione finanziaria.
            <br>
            Art. 59, comma 1, lett. p) del D. Lgs.n.446 del 1997
            <br>
            p) prevedere che ai fini del potenziamento degli uffici tributari del comune, ai sensi dell'articolo 3,
            comma 57, della legge 23 dicembre 1996, n. 662, possono essere attribuiti compensi incentivanti al personale
            addetto.
            <br>
            21. Incentivazione specifiche attività - Compensi IMU e TARI (art. 68 c. 2, lett. g CCNL 21.5.2018)
            € <?php self::getSelect('formula21'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var60', $infos[60]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area21', $infos[61]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. g CCNL 21.5.2018
            <br>
            G) compensi previsti da disposizioni di legge, riconosciuti esclusivamente a valere sulle risorse di cui
            all’art. 67, comma 3, lett. c), ivi compresi i compensi di cui all’art.70-ter;
            <br>
            Art. 1 comma 1091 della L. 145 del 31.12.2018 - Legge di Bilancio 2019
            <br>
            1091. Ferme restando le facolta' di regolamentazione del tributo di cui all'articolo 52 del decreto
            legislativo 15 dicembre 1997, n. 446, i comuni che hanno approvato il bilancio di previsione ed il
            rendiconto entro i termini stabiliti dal testo unico di cui al decreto legislativo 18 agosto 2000, n. 267,
            possono, con proprio regolamento, prevedere che il maggiore gettito accertato e riscosso, relativo agli
            accertamenti dell'imposta municipale propria e della TARI, nell'esercizio fiscale precedente a quello di
            riferimento risultante dal conto consuntivo approvato, nella misura massima del 5 per cento, sia destinato,
            limitatamente all'anno di riferimento, al potenziamento delle risorse strumentali degli uffici comunali
            preposti alla gestione delle entrate e al trattamento accessorio del personale dipendente, anche di
            qualifica dirigenziale, in deroga al limite di cui all'articolo 23, comma 2, del decreto legislativo 25
            maggio 2017, n. 75. La quota destinata al trattamento economico accessorio, al lordo degli oneri riflessi e
            dell'IRAP a carico dell'amministrazione, è attribuita, mediante contrattazione integrativa, al personale
            impiegato nel raggiungimento degli obiettivi del settore entrate, anche con riferimento alle attività
            connesse alla partecipazione del comune all'accertamento dei tributi erariali e dei contributi sociali non
            corrisposti, in applicazione dell'articolo 1 del decreto-legge 30 settembre 2005, n. 203, convertito, con
            modificazioni, dalla legge 2 dicembre 2005, n. 248. Il beneficio attribuito non può superare il 15 per cento
            del trattamento tabellare annuo lordo individuale. La presente disposizione non si applica qualora il
            servizio di accertamento sia affidato in concessione.
            <br>
            22. Incentivazione specifiche attività – Messi Notificatori (art. 68 comma 2 lett. h CCNL 21.5.2018)
            € <?php self::getSelect('formula22'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var62', $infos[62]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area22', $infos[63]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 54 CCNL del 14/9/2000
            <br>
            Gli enti possono verificare, in sede di concertazione, se esistano le condizioni finanziarie per destinare
            una quota parte del rimborso spese per ogni notificazione di atti dell'amministrazione finanziaria al fondo
            di cui all'art.15 del CCNL dell'1.4.1999 per essere finalizzata all'erogazione di incentivi di produttività
            a favore dei messi notificatori stessi.
            <br>
            Art. 68 comma 2 lett. H CCNL 21.5.2018
            <br>
            h) compensi ai messi notificatori, riconosciuti esclusivamente a valere sulle risorse di all’art. 67, comma
            3, lett. f), secondo la disciplina di cui all’art. 54 del CCNL del 14.9.2000;
            <br>
            23. Incentivazione specifiche attività - Diritto soggiorno Unione Europea D.lgs 30/2007 (art. 68 comma 2
            lett. h CCNL 21.5.2018) € <?php self::getSelect('formula23'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var64', $infos[64]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area23', $infos[65]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 68 comma 2 lett. h CCNL 21.5.2018
            <br>
            G) compensi previsti da disposizioni di legge, riconosciuti esclusivamente a valere sulle risorse di cui
            all’art. 67, comma 3, lett. c), ivi compresi i compensi di cui all’art.70-ter;
            <br>
            LEGGE 24 dicembre 2007, n. 244 art. 2 comma 11
            <br>
            11. Per ciascuno degli anni 2008 e 2009, a valere sul fondo ordinario di cui all'articolo 34, comma 1,
            lettera a), del decreto legislativo 30 dicembre 1992, n. 504, e' disposto un intervento fino a un importo di
            10 milioni di euro per la concessione di un contributo a favore dei comuni per l'attuazione della direttiva
            2004/38/CE del Parlamento europeo e del Consiglio, del 29 aprile 2004, relativa al diritto dei cittadini
            dell'Unione e dei loro familiari di circolare e di soggiornare liberamente nel territorio degli Stati
            membri, di cui al decreto legislativo 6 febbraio 2007, n. 30. Con decreto del Ministro dell'interno sono
            determinate le modalità' di riparto ed erogazione dei contributi.
            <br>
            24. Incentivazione specifiche attività – (art. 68 comma 2 lett. h CCNL 21.5.2018) Legge Regionale specifica
            <?php self::getInput('var66', $infos[66]['valore'], 'orange'); ?>
            € <?php self::getSelect('formula24'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var66', $infos[67]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area24', $infos[68]['valore'], 'orange'); ?>
            <br>
            25. Incentivazione specifiche attività – (art. 68 comma 2 lett. h CCNL
            21.5.2018) <?php self::getInput('var69', $infos[69]['valore'], 'orange'); ?> €
            <?php self::getSelect('formula25'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var70', $infos[70]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area25', $infos[71]['valore'], 'orange'); ?>
            <br>
            26. Premi collegati alla performance organizzativa – Compensi per Sponsorizzazioni, convenzioni e servizi
            conto terzi (art. 67 comma 3 lett. a CCNL 21.5.2018) € <?php self::getSelect('formula26'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var72', $infos[72]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area26', $infos[73]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 4 CCNL del 5/10/2001 comma 4 Integrazione risorse dell'art. 15 del CCNL dell'1/4/1999
            "d) La quota delle risorse che possono essere destinate al trattamento economico accessorio del personale
            nell'ambito degli introiti derivanti dalla applicazione dell'art.43 della legge n.449/1997 con particolare
            riferimento alle seguenti iniziative:
            <br>
            a. contratti di sponsorizzazione ed accordi di collaborazione con soggetti privati ed associazioni senza
            fini di lucro, per realizzare o acquisire a titolo gratuito interventi, servizi, prestazioni, beni o
            attività inseriti nei programmi di spesa ordinari con il conseguimento dei corrispondenti risparmi;
            <br>
            b. convenzioni con soggetti pubblici e privati diretti a fornire ai medesimi soggetti, a titolo oneroso,
            consulenze e servizi aggiuntivi rispetto a quelli ordinari;
            <br>
            c. contributi dell'utenza per servizi pubblici non essenziali o, comunque, per prestazioni, verso terzi
            paganti, non connesse a garanzia di diritti fondamentali.
            <br>
            27. Piani di razionalizzazione (Art. 67 comma 3 lett. b CCNL 21.5.2018ART. 16 C. 5 L. 111/2011 e s.m.i.) €
            <?php self::getSelect('formula27'); ?>;
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var74', $infos[74]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            la distribuzione dello specifico incentivo:
            <br>
            <?php self::getTextArea('area27', $infos[75]['valore'], 'orange'); ?>
            <br>
            RIFERIMENTI NORMATIVI/CONTRATTUALI:
            <br>
            Art. 16, commi 4, 5 e 6 del D.L.n. 98 del 6 luglio 2011, convertito, con modificazioni, nella legge n. 111
            del 15 luglio 2011
            <br>
            4. Fermo restando quanto previsto dall'articolo 11, le amministrazioni di cui all'articolo 1, comma 2, del
            decreto legislativo 30 marzo 2001, n. 165, possono adottare entro il 31 marzo di ogni anno piani triennali
            di razionalizzazione e riqualificazione della spesa, di riordino e ristrutturazione amministrativa, di
            semplificazione e digitalizzazione, di riduzione dei costi della politica e di funzionamento, ivi compresi
            gli appalti di servizio, gli affidamenti alle partecipate e il ricorso alle consulenze attraverso persone
            giuridiche. Detti piani indicano la spesa sostenuta a legislazione vigente per ciascuna delle voci di spesa
            interessate e i correlati obiettivi in termini fisici e finanziari.
            <br>
            5. In relazione ai processi di cui al comma 4, le eventuali economie aggiuntive effettivamente realizzate
            rispetto a quelle gia' previste dalla normativa vigente, dall'articolo 12 e dal presente articolo ai fini
            del miglioramento dei saldi di finanza pubblica, possono essere utilizzate annualmente, nell'importo massimo
            del 50 per cento, per la contrattazione integrativa, di cui il 50 per cento destinato alla erogazione dei
            premi previsti dall'articolo 19 del decreto legislativo 27 ottobre 2009, n. 150. La restante quota e'
            versata annualmente dagli enti e dalle amministrazioni dotati di autonomia finanziaria ad apposito capitolo
            dell'entrata del bilancio dello Stato. La disposizione di cui al precedente periodo non si applica agli enti
            territoriali e agli enti, di competenza regionale o delle provincie autonome di Trento e di Bolzano, del
            SSN. Le risorse di cui al primo periodo sono utilizzabili solo se a consuntivo e' accertato, con riferimento
            a ciascun esercizio, dalle amministrazioni interessate, il raggiungimento degli obiettivi fissati per
            ciascuna delle singole voci di spesa previste nei piani di cui al comma 4 e i conseguenti risparmi. I
            risparmi sono certificati, ai sensi della normativa vigente, dai competenti organi di controllo. Per la
            Presidenza del Consiglio dei Ministri e i Ministeri la verifica viene effettuata dal Ministero dell'economia
            e delle finanze - Dipartimento della Ragioneria generale dello Stato per il tramite, rispettivamente,
            dell'UBRRAC e degli uffici centrali di bilancio e dalla Presidenza del Consiglio - Dipartimento della
            funzione pubblica.
            <br>
            28. Quota recupero somme (Art. 4 DL 16/2014 Salva Roma Ter) <?php self::getSelect('formula28'); ?>;
            <br>
            Quota annuale delle risorse decentrate finalizzata a compensare le somme indebitamente erogate negli anni
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
            b) Quadro di sintesi delle modalità di utilizzo da parte della contrattazione integrativa delle risorse del
            Fondo unico di amministrazione;
            <br>
            <table class="table-fondo">
                <thead>
                <tr>
                    <th scope="col">Utilizzo Fondo</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row"> Totale utilizzo fondo progressioni</th>
                    <td><?php self::getSelect('formula29'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Indennità di comparto art.33 ccnl 22.01.04, quota a carico fondo</th>
                    <td><?php self::getSelect('formula30'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Indennità educatori asilo nido</th>
                    <td><?php self::getSelect('formula30'); ?></td>
                </tr>
                <tr>
                    <th scope="row">ALTRI UTILIZZI</th>
                    <td><?php self::getSelect('formula31'); ?></td>
                </tr>
                <tr>
                    <th scope="row">TOTALE UTILIZZO RISORSE STABILI</th>
                    <td><?php self::getSelect('formula32'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Indennità di turno</th>
                    <td><?php self::getSelect('formula33'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Indennità condizioni di lavoro</th>
                    <td><?php self::getSelect('formula34'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Reperibilità</th>
                    <td><?php self::getSelect('formula35'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Indennità specifiche responsabilità art 70 quinquies c. 1 CCNL 2018 (ex lett. f art.
                        17 comma 2 CCNL 1.4.1999)
                    </th>
                    <td><?php self::getSelect('formula36'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Indennità specifiche responsabilità art 70 quinquies c. 1 CCNL 2018 (ex lett. i art.
                        17 comma 2 CCNL 1.4.1999)
                    </th>
                    <td><?php self::getSelect('formula37'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Indennità di funzione – Art. 56 sexies CCNL 2018 (Vigilanza)</th>
                    <td><?php self::getSelect('formula38'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Indennità di servizio esterno – art. 56 quinquies CCNL 2018 (Vigilanza)</th>
                    <td><?php self::getSelect('formula39'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Indennità particolare compenso incentivante (personale unioni dei comuni)</th>
                    <td><?php self::getSelect('formula40'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Centri estivi asili nido art 31 comma 5 CCNL 14 -9- 2000</th>
                    <td><?php self::getSelect('formula41'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Compenso previsto dall'art.24, comma 1 CCNL 14.9.2000, per il personale che presta
                        attività lavorativa nel giorno destinato al riposo settimanale
                    </th>
                    <td><?php self::getSelect('formula42'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Premi collegati alla performance organizzativa – art. 68 c. 2 lett. a) CCNL 2018
                    </th>
                    <td><?php self::getSelect('formula43'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Premi collegati alla performance individuale - art. 68 c. 2 lett. b) CCNL 2018</th>
                    <td><?php self::getSelect('formula44'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Premi collegati alla performance organizzativa - Obiettivi finanziati con art. 67
                        c.5 lett. B CCNL 2018 parte variabile
                    </th>
                    <td><?php self::getSelect('formula45'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Premi collegati alla performance organizzativa - Obiettivi finanziati da risorse art
                        67 c. 5 lett. b di potenziamento dei servizi di controllo finalizzati alla sicurezza urbana e
                        stradale Art. 56 quater CCNL 2018
                    </th>
                    <td><?php self::getSelect('formula46'); ?></td>
                </tr>
                <tr>
                    <th scope="row">50% ECONOMIE DA PIANI DI RAZIONALIZZAZIONE DA DESTINARE ALLA CONTRATTAZIONE DI CUI
                        IL 50% DESTINATO ALLA PRODUTTIVITA' (escluso dal limite fondo 2010)
                    </th>
                    <td><?php self::getSelect('formula47'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Altro</th>
                    <td><?php self::getSelect('formula48'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Premi collegati alla performance organizzativa - Compensi per sponsorizzazioni</th>
                    <td><?php self::getSelect('formula49'); ?></td>
                </tr>
                <tr>
                    <th scope="row">TOTALE UTILIZZO ALTRE INDENNITA’</th>
                    <td><?php self::getSelect('formula50'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Art. 68 c. 2 lett. g) CCNL 2018
                        FUNZIONI TECNICHE RIF Art. 113 comma 2 e 3 D.LGS. 18 APRILE 2016, N. 50
                    </th>
                    <td><?php self::getSelect('formula51'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Art. 68 c. 2 lett. h CCNL 2018 RIF Compensi per notifiche</th>
                    <td><?php self::getSelect('formula52'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Art. 68 c. 2 lett. g) CCNL 2018 RIF Compensi IMU e TARI c. 1091 Lex 145/2018</th>
                    <td><?php self::getSelect('formula53'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Art. 68 c. 2 lett. g) CCNL 2018 RIF - ISTAT</th>
                    <td><?php self::getSelect('formula54'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Art. 68 c. 2 lett. g) CCNL 2018 RIF - ICI</th>
                    <td><?php self::getSelect('formula55'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Art. 68 c. 2 lett. g) CCNL 2018 RIF - avvocatura
                    </th>
                    <td><?php self::getSelect('formula56'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Art. 68 c. 2 lett. g) CCNL 2018
                        RIF - Diritto soggiorno Unione Europea D.lgs 30/2007
                    </th>
                    <td><?php self::getSelect('formula57'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Art. 68 c. 2 lett. g) CCNL 2018 Legge Regionale specifica</th>
                    <td><?php self::getSelect('formula58'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Art. 68 c. 2 lett. g) CCNL 2018 RIF - Legge o ALTRO</th>
                    <td><?php self::getSelect('formula59'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Quota recupero somme (Art. 4 DL 16/2014 Salva Roma Ter)</th>
                    <td><?php self::getSelect('formula60'); ?></td>
                </tr>
                <tr>
                    <th scope="row">TOTALE UTILIZZO RISORSE VINCOLATE</th>
                    <td><?php self::getSelect('formula61'); ?></td>
                </tr>
                <tr>
                    <th scope="row">TOTALE UTILIZZO FONDO</th>
                    <td><?php self::getSelect('formula62'); ?></td>
                </tr>
                </tbody>
            </table>
            <br>
            c) Gli effetti abrogativi impliciti, in modo da rendere chiara la successione temporale dei contratti
            integrativi e la disciplina vigente delle materie demandate alla contrattazione integrativa;
            <br>
            Risultano attualmente in vigore i seguenti CCDI:
            <br>
            CCDI relativo all’anno <?php self::getInput('var76', $infos[76]['valore'], 'orange'); ?> con il quale sono
            state determinate le modalità di attribuzione dell’indennità
            di <?php self::getInput('var77', $infos[78]['valore'], 'orange'); ?>
            , <?php self::getInput('var78', $infos[78]['valore'], 'orange'); ?>
            , <?php self::getInput('var79', $infos[70]['valore'], 'orange'); ?>
            E <?php self::getInput('var80', $infos[80]['valore'], 'orange'); ?>
            <br>
            CCDI relativo all’anno <?php self::getInput('var81', $infos[81]['valore'], 'orange'); ?> con il quale sono
            state determinate le modalità di attribuzione dell’indennità
            di <?php self::getInput('var82', $infos[82]['valore'], 'orange'); ?>
            , <?php self::getInput('var83', $infos[83]['valore'], 'orange'); ?>
            , <?php self::getInput('var84', $infos[84]['valore'], 'orange'); ?>
            E Per l’anno anno sono state previste nuove progressioni economiche orizzontali.

            Viene ripreso il testo del contratto siglato per l’anno 202x con il quale sono stati definiti i criteri per
            l’attribuzione delle progressioni:
            <br>
            d) Illustrazione e specifica attestazione della coerenza con le previsioni in materia di meritocrazia e
            premialità (coerenza con il Titolo III del Decreto Legislativo n.150/2009, le norme di contratto nazionale
            e la giurisprudenza contabile) ai fini della corresponsione degli incentivi per la performance individuale
            ed organizzativa;
            <br>
            <?php self::getTextArea('area28', $infos[86]['valore'], 'orange'); ?>
            <br>
            e) illustrazione e specifica attestazione della coerenza con il principio di selettività delle progressioni
            economiche finanziate con il Fondo per la contrattazione integrativa - progressioni orizzontali – ai sensi
            dell’articolo23 del Decreto Legislativo n.150/2009 (previsione di valutazioni di merito ed esclusione di
            elementi automatici come l’anzianità di servizio);
            <br>
            Per l’anno <?php self::getInput('var87', $infos[87]['valore'], 'orange'); ?> sono state previste nuove
            progressioni economiche orizzontali.
            <br>
            Viene ripreso il testo del contratto siglato per
            l’anno <?php self::getInput('var88', $infos[88]['valore'], 'orange'); ?> con il quale sono stati definiti i
            criteri per
            l’attribuzione delle progressioni:
            <br>
            <?php self::getTextArea('area29', $infos[89]['valore'], 'orange'); ?>
            <br>
            In particolare sono contenute previsione di valutazioni di merito e sono esclusi elementi automatici come
            l’anzianità di servizio
            <br>
            Per l’anno <?php self::getInput('var90', $infos[90]['valore'], 'orange'); ?> non sono state previste nuove
            progressioni economiche orizzontali. Non sono stati
            contrattati quindi nuovi criteri anche se è stato condiviso tra le parti che il sistema utilizzato per
            valutare la performance sarà utilizzato qualora si dovessero prevedere nuove progressioni economiche.
            <br>
            f) illustrazione dei risultati attesi dalla sottoscrizione del contratto integrativo, in correlazione con
            gli strumenti di programmazione gestionale (Piano della Performance), adottati dall’Amministrazione in
            coerenza con le previsioni del Titolo II del Decreto Legislativo n.150/2009.
            <br>
            E’ stato approvato il Piano della Performance per
            l’anno <?php self::getInput('var91', $infos[91]['valore'], 'orange'); ?>. Ai sensi dell’attuale Regolamento
            degli
            Uffici e dei Servizi ogni anno l’Ente è tenuto ad approvare un Piano della Performance che deve contenere
            gli obiettivi dell’Ente riferiti ai servizi gestiti.
            <br>
            Con la Delibera n. <?php self::getInput('var92', $infos[92]['valore'], 'orange'); ?>
            del <?php self::getInput('var93', $infos[93]['valore'], 'orange'); ?> <?php self::getInput('var94', $infos[94]['valore'], 'red'); ?>
            <?php self::getInput('var95', $infos[95]['valore'], 'orange'); ?> ha approvato il Piano della Performance
            per l’anno <?php self::getInput('var96', $infos[96]['valore'], 'orange'); ?>. Tale piano è stato
            successivamente validato dall’organo di valutazione con il Verbale
            n. <?php self::getInput('var97', $infos[97]['valore'], 'red'); ?>.
            <br>
            Ai sensi dell’attuale Regolamento degli Uffici e dei Servizi ogni anno l’Ente è tenuto ad approvare un Piano
            della Performance che deve contenere le attività di processo dell’Ente riferiti ai servizi gestiti ed
            eventuali obiettivi strategici annuali determinati
            dalla <?php self::getInput('var98', $infos[98]['valore'], 'orange'); ?>.
            <br>
            Gli obiettivi contenuti nel Piano prevedono il crono programma delle attività, specifici indici/indicatori
            (quantità, qualità, tempo e costo) di prestazione attesa e il personale coinvolto. Si rimanda al documento
            per il dettaglio degli obiettivi di performance.
            <br>
            Il/la <?php self::getInput('var99', $infos[99]['valore'], 'orange'); ?> in particolare, con Delibera
            n.<?php self::getInput('var100', $infos[100]['valore'], 'orange'); ?> del
            <?php self::getInput('var101', $infos[101]['valore'], 'orange'); ?> con oggetto “PERSONALE NON DIRIGENTE.
            FONDO RISORSE DECENTRATE PER L’ANN0 <?php self::getInput('var102', $infos[102]['valore'], 'orange'); ?>.
            INDIRIZZI PER LA COSTITUZIONE. DIRETTIVE PER LA CONTRATTAZIONE DECENTRATA INTEGRATIVA” ha stabilito di
            incrementare le risorse variabili con le seguenti voci:
            <br>
            ai sensi dell’art. 67 comma 4 CCNL 21.5.2018 è stata autorizzata l’iscrizione, fra le risorse variabili,
            della quota fino ad un massimo dell'1,2% del monte salari (esclusa la quota riferita alla dirigenza)
            stabilito per l'anno 1997, nel rispetto del limite dell’anno 2016
            e <?php self::getInput('var103', $infos[103]['valore'], 'orange'); ?> finalizzato
            al raggiungimento di specifici obiettivi di produttività e qualità espressamente definiti dall’Ente nel
            Piano esecutivo di Gestione <?php self::getInput('var104', $infos[104]['valore'], 'orange'); ?> unitamente
            al Piano della Performance approvato con Delibera della/del
            <?php self::getInput('var105', $infos[105]['valore'], 'orange'); ?>
            n. <?php self::getInput('var106', $infos[106]['valore'], 'orange'); ?>
            del <?php self::getInput('var107', $infos[107]['valore'], 'orange'); ?> in merito a
            <?php self::getInput('var108', $infos[108]['valore'], 'orange'); ?>
            <br>
            L’importo previsto è pari a € <?php self::getSelect('formula66'); ?> che verrà erogato solo successivamente
            alla verifica dell’effettivo
            conseguimento dei risultati attesi.
            <br>
            Si precisa che gli importi, qualora non dovessero essere interamente distribuiti, non daranno luogo ad
            economie del fondo ma ritorneranno nella disponibilità del bilancio dell’Ente.
            <br>
            ai sensi dell’art. 67, comma 5 lett. b) del CCNL 21.5.2018 è stata autorizzata l’iscrizione, fra le risorse
            variabili, delle somme necessarie per il conseguimento di obiettivi dell’ente, anche di mantenimento, nonché
            obiettivi di potenziamento dei servizi di controllo finalizzati alla sicurezza urbana e stradale Art. 56
            quater CCNL 2018, per un importo pari a € <?php self::getSelect('formula67'); ?>. In particolare tali
            obiettivi sono contenuti nel Piano
            esecutivo di Gestione <?php self::getInput('var109', $infos[109]['valore'], 'orange'); ?> unitamente al
            Piano della Performance approvato con Delibera della/del
            <?php self::getInput('var110', $infos[110]['valore'], 'orange'); ?>
            n. <?php self::getInput('var111', $infos[111]['valore'], 'orange'); ?>
            del <?php self::getInput('var112', $infos[112]['valore'], 'orange'); ?> e ne
            vengono qui di seguito elencati i titoli:
            <br>
            – <?php self::getInput('var113', $infos[113]['valore'], 'orange'); ?>,
            <br>
            - <?php self::getInput('var114', $infos[114]['valore'], 'orange'); ?>
            <br>
            <?php self::getTextArea('area30', $infos[115]['valore'], 'orange'); ?>
            <br>
            Si precisa che gli importi qualora non dovessero essere interamente distribuiti, non daranno luogo ad
            economie del fondo ma ritorneranno nella disponibilità del bilancio dell’Ente.
            <br>
            ai sensi dell’art. 67 comma 5 lett. b) del CCNL 21.5.2018 è stata autorizzata l'iscrizione della sola quota
            di maggior incasso rispetto all’anno precedente a seguito di obiettivi di potenziamento dei servizi di
            controllo finalizzati alla sicurezza urbana e stradale Art. 56 quater CCNL 2018, come risorsa NON soggetta
            al limite secondo dalla Corte dei Conti Sezione delle Autonomie con delibera n. 5 del 2019, per un importo
            pari a € <?php self::getSelect('formula68'); ?>.;
            <br>
            ai sensi della Legge 111/2011 e dell’art. 67 comma 3 lett. B del CCNL 21.5.2018, vista la
            Delibera <?php self::getInput('var116', $infos[116]['valore'], 'orange'); ?>
            <?php self::getInput('var117', $infos[117]['valore'], 'orange'); ?>
            n. <?php self::getInput('var118', $infos[118]['valore'], 'orange'); ?>
            del <?php self::getInput('var119', $infos[119]['valore'], 'orange'); ?> di
            approvazione del Piano di razionalizzazione anno è stata autorizzata l’iscrizione tra le risorse variabili
            dell’importo pari a € <?php self::getSelect('formula69'); ?>, che dovrà essere distribuito nel rigoroso
            rispetto dei principi introdotti dalla
            norma vigente e solo in presenza, a consuntivo, del parere favorevole espresso dal Revisore dei Conti /
            Collegio dei Revisori;
            <br>
            ai sensi dell’art. 67 c.7 e Art.15 c.7 CCNL 2018 è stata autorizzata all'iscrizione fra le risorse variabili
            la quota di incremento del Fondo trattamento accessorio per riduzione delle risorse destinate alla
            retribuzione di posizione e di risultato delle PO rispetto al tetto complessivo del salario accessorio art.
            23 c. 2 D.Lgs 75/2017, per un importo pari a € <?php self::getSelect('formula70'); ?>;
            <br>
            g) altre informazioni eventualmente ritenute utili per la migliore comprensione degli istituti regolati dal
            contratto.
            <br>
            Nessun'altra informazione
            <br>
            <h6>Relazione tecnico finanziaria</h6>
            <br>
            Modulo I -La costituzione del Fondo per la contrattazione integrativa
            <br>
            Il Fondo per lo sviluppo delle risorse umane per
            l’anno <?php self::getInput('var120', $infos[120]['valore'], 'orange'); ?> ha seguito il seguente iter:
            <br>
            - Delibera n. <?php self::getInput('var121', $infos[121]['valore'], 'orange'); ?>
            del <?php self::getInput('var122', $infos[122]['valore'], 'orange'); ?> di
            indirizzo <?php self::getInput('var123', $infos[123]['valore'], 'orange'); ?> alla delegazione di parte
            pubblica e per la costituzione del Fondo <?php self::getInput('var124', $infos[124]['valore'], 'orange'); ?>
            <br>
            - Determina n. <?php self::getInput('var125', $infos[125]['valore'], 'orange'); ?>
            del <?php self::getInput('var126', $infos[126]['valore'], 'orange'); ?>
            del <?php self::getInput('var127', $infos[127]['valore'], 'orange'); ?> di
            costituzione del Fondo <?php self::getInput('var128', $infos[128]['valore'], 'orange'); ?>;
            <br>
            <h6>
                Sezione I - Risorse fisse aventi carattere di certezza e stabilità
            </h6>
            <br>
            Il fondo destinato alle politiche di sviluppo delle risorse umane ed alla produttività, in applicazione
            dell’art. 67 del CCNL del 21.05.2018, per
            l’anno <?php self::getInput('var129', $infos[129]['valore'], 'orange'); ?> risulta, come da allegato schema
            di costituzione del
            Fondo così riepilogato:
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Risorse fisse aventi carattere di certezza e stabilità</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td scope="row">Totale Risorse storiche - Unico importo consolidato art. 67 c. 1 CCNL 21.05.2018
                        (A)
                    </td>
                    <td><?php self::getSelect('formula71'); ?></td>
                </tr>
                <tr>
                    <th scope="row">Incrementi stabili</th>
                    <th></th>
                </tr>
                <tr>
                    <td>Art. 67 c. 2 lett. c) CCNL 2018 - RIA e assegni ad personam</td>
                    <td><?php self::getSelect('formula72'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 2 lett. d) CCNL 2018 - eventuali risorse riassorbite</td>
                    <td><?php self::getSelect('formula73'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 2 lett. e) CCNL 2018 - Oneri trattamento accessorio personale trasferito dal 2018
                    </td>
                    <td><?php self::getSelect('formula74'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 2 lett. g) CCNL 2018 - Riduzione stabile Fondo Straordinario dal 2018</td>
                    <td><?php self::getSelect('formula75'); ?></td>
                </tr>
                <tr>
                    <td>Art . 67 c. 5 lett. a) CCNL 2018 - incremento dotazione organica dal 2018</td>
                    <td><?php self::getSelect('formula76'); ?></td>
                </tr>
                <tr>
                    <td>Art. 33 comma 2 DL 34/2019 - Incremento valore medio procapite del fondo rispetto al 2018</td>
                    <td><?php self::getSelect('formula77'); ?></td>
                </tr>
                <tr>
                    <td> Totale incrementi stabili (a)</td>
                    <td><?php self::getSelect('formula78'); ?></td>
                </tr>
                <tr>
                    <td>Totale risorse stabili SOGGETTE al limite (A+a)</td>
                    <td><?php self::getSelect('formula79'); ?></td>
                </tr>
                <tr>
                    <th>Incrementi con carattere di certezza e stabilità NON soggetti al limite</th>
                    <td></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 2 lett. b) CCNL 2018 - Rivalutazione delle PEO</td>
                    <td><?php self::getSelect('formula80'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 2 lett. a) CCNL 2018 - Incremento 83,20 a valere dal 2019</td>
                    <td><?php self::getSelect('formula81'); ?></td>
                </tr>
                <tr>
                    <td>Art. 11 c. 1 lett. b) D.L. 135/2018 – Incremento trattamento accessorio</td>
                    <td><?php self::getSelect('formula81'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 2 lett. e) CCNL 2018 – Rif Art. 1 c. 800 L. 205/2017 Armonizzazione personale
                        province transitato
                    </td>
                    <td><?php self::getSelect('formula82'); ?></td>
                </tr>
                <tr>
                    <td>Altre risorse</td>
                    <td><?php self::getSelect('formula83'); ?></td>
                </tr>
                <tr>
                    <td>Totale incrementi stabili non soggetti al limite (b)</td>
                    <td><?php self::getSelect('formula84'); ?></td>
                </tr>
                <tr>
                    <td>TOTALE RISORSE FISSE AVENTI CARATTERE DI CERTEZZA E STABILITÀ (A+a+b)</td>
                    <td><?php self::getSelect('formula85'); ?></td>
                </tr>
                </tbody>
            </table>
            <br>
            <h6>
                Sezione II - Risorse variabili
            </h6>
            <br>
            Quali voci variabili di cui all’art. 67 comma 3 CCNL 21.5.2018 sono state stanziate:
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Risorse variabili</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <th>Risorse variabili sottoposte al limite</th>
                    <td></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. a) CCNL 2018- – sponsorizzazioni</td>
                    <td><?php self::getSelect('formula86'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. c) CCNL 2018 ICI</td>
                    <td><?php self::getSelect('formula87'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. c) CCNL 2018 - Legge Regionale specifica (es. SARDEGNA n. 19 del 1997)
                    </td>
                    <td><?php self::getSelect('formula88'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. f) CCNL 2018 - – Compensi per Notifiche</td>
                    <td><?php self::getSelect('formula89'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 4 CCNL 2018 - integrazione 1,2%</td>
                    <td><?php self::getSelect('formula90'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 5 lett. b) CCNL 2018 - Obiettivi dell'Ente (anche potenziamento controllo Codice
                        Strada)
                    </td>
                    <td><?php self::getSelect('formula91'); ?></td>
                </tr>
                <tr>
                    <td> INTEGR. FONDO CCIAA IN EQ. FIN. (ART.15 C.1 L. N CCNL 98-01) R116</td>
                    <td><?php self::getSelect('formula92'); ?></td>
                </tr>
                <tr>
                    <td> Art. 67 c. 3 lett. g) CCNL 2018 - Compensi personale case da gioco R130</td>
                    <td><?php self::getSelect('formula93'); ?></td>
                </tr>
                <tr>
                    <td> Art. 67 c. 3 lett. k) CCNL 2018 - Oneri trattamento accessorio personale trasferito</td>
                    <td><?php self::getSelect('formula94'); ?></td>
                </tr>
                <tr>
                    <td> Art. 67 c. 3 lett. d) CCNL 2018 - Ria e assegni ad personam personale cessato quota rateo anno
                        di cessazione
                    </td>
                    <td><?php self::getSelect('formula95'); ?></td>
                </tr>
                <tr>
                    <td> Art. 67 c.7 e Art.15 c.7 CCNL 2018 – Quota incremento Fondo per riduzione retribuzione di PO e
                        di risultato
                    </td>
                    <td><?php self::getSelect('formula96'); ?></td>
                </tr>
                <tr>
                    <td> Altre risorse</td>
                    <td><?php self::getSelect('formula98'); ?></td>
                </tr>
                <tr>
                    <td> Totale voci variabili sottoposte al limite</td>
                    <td><?php self::getSelect('formula99'); ?></td>
                </tr>
                <tr>
                    <th>Risorse variabili NON sottoposte al limite</th>
                    <td></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. b) CCNL 2018- - Economie da piani di razionalizzazione</td>
                    <td><?php self::getSelect('formula100'); ?></td>

                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. c) CCNL 2018 - Compensi ISTAT</td>
                    <td><?php self::getSelect('formula101'); ?></td>

                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. c) CCNL 2018 - Avvocatura</td>
                    <td><?php self::getSelect('formula102'); ?></td>

                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. c) CCNL 2018 - Somme finanziate da fondi di derivazione dell'Unione Europea
                    </td>
                    <td><?php self::getSelect('formula103'); ?></td>

                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. c) CCNL 2018 - - INCENTIVI PER FUNZIONI TECNICHE Art. 113 D.Lgs. 50/2016</td>
                    <td><?php self::getSelect('formula104'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. c) CCNL 2018 - Compensi IMU e TARI c. 1091 L. 145/2018</td>
                    <td><?php self::getSelect('formula105'); ?></td>
                </tr>
                <tr>
                    <td>Altro - Art. 67 c. 3 lett. c) CCNL 2018 (Da specificare)</td>
                    <td><?php self::getSelect('formula106'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. a) CCNL 2018 - – sponsorizzazioni (per convenzioni successive al 2016)</td>
                    <td><?php self::getSelect('formula107'); ?></td>
                </tr>
                <tr>
                    <td>ALTRE RISORSE (Da specificare)</td>
                    <td><?php self::getSelect('formula108'); ?></td>
                </tr>
                <tr>
                    <td>Art. 68 c. 1 CCNL 2018 - Risparmi Fondo Stabile Anno Precedente</td>
                    <td><?php self::getSelect('formula109'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 3 lett. e) CCNL 2018 - Risparmi Fondo Straordinario Anno Precedente</td>
                    <td><?php self::getSelect('formula110'); ?></td>
                </tr>
                <tr>
                    <td>Art. 67 c. 5 lett. b) CCNL 2018 - Quota incremento CDS maggior incasso rispetto anno
                        precedente
                    </td>
                    <td><?php self::getSelect('formula111'); ?></td>
                </tr>
                <tr>
                    <td>Totale voci variabili NON sottoposte al limite</td>
                    <td><?php self::getSelect('formula113'); ?></td>
                </tr>
                <tr>
                    <td>TOTALE RISORSE VARIABILI</td>
                    <td><?php self::getSelect('formula114'); ?></td>
                </tr>
                </tbody>
            </table>
            <br>
            <h6>
                Sezione III - (eventuali) Decurtazioni del fondo
            </h6>
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">DECURTAZIONI SULLE RISORSE AVENTI CARATTERE DI CERTEZZA E STABILITA’ (a detrarre)
                    </th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>Decurtazione ATA</td>
                    <td><?php self::getSelect('formula115'); ?></td>
                </tr>
                <tr>
                    <td>Decurtazione incarichi di Posizione Organizzativa (Enti con e Senza Dirigenza)</td>
                    <td><?php self::getSelect('formula116'); ?></td>
                </tr>
                <tr>
                    <td>Articolo 19, comma 1 CCNL 1.4.1999
                        DECURTAZIONE primo inquadramento di alcune categorie di lavoratori in applicazione del CCNL del
                        31.3.1999 (area di vigilanza e personale della prima e seconda qualifica funzionale).
                    </td>
                    <td><?php self::getSelect('formula117'); ?></td>
                </tr>
                <tr>
                    <td>Decurtazione art 67 c. 2 lett. e) Ccnl 2018 - personale trasferito presso altri Enti per delega
                        o trasferimento di funzioni, da disposizioni di legge o altro
                    </td>
                    <td><?php self::getSelect('formula118'); ?></td>
                </tr>
                <tr>
                    <td> ALTRE RISORSE (da specificare)</td>
                    <td><?php self::getSelect('formula119'); ?></td>
                </tr>
                <tr>
                    <td>Decurtazione parte stabile operate nel periodo 2011/2014 ai sensi dell'art. 9 C. 2 bis
                        L.122/2010 secondo periodo
                    </td>
                    <td><?php self::getSelect('formula120'); ?></td>
                </tr>
                <tr>
                    <td> Decurtazioni PARTE STABILE operate nel 2016 per cessazioni e rispetto limite 2015</td>
                    <td><?php self::getSelect('formula121'); ?></td>
                </tr>
                <tr>
                    <td> Decurtazione parte stabile per rispetto limite 2016</td>
                    <td><?php self::getSelect('formula122'); ?></td>
                </tr>
                <tr>
                    <td>TOTALE DECURTAZIONI AVENTI CARATTERE DI CERTEZZA E STABILITA’</td>
                    <td><?php self::getSelect('formula123'); ?></td>
                </tr>
                </tbody>
            </table>
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">Decurtazioni Risorse variabili</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <th>Risorse variabili sottoposte al limite</th>
                    <td></td>
                </tr>
                <tr>
                    <td>Altre decurtazioni</td>
                    <td><?php self::getSelect('formula124'); ?></td>
                </tr>
                <tr>
                    <td>Decurtazione parte variabile operate nel periodo 2011/2014 ai sensi dell'art. 9 C. 2 bis
                        L.122/2010 secondo periodo
                    </td>
                    <td><?php self::getSelect('formula125'); ?></td>
                </tr>
                <tr>
                    <td>Decurtazioni PARTE variabile operate nel 2016 per cessazioni e rispetto limite 2015
                    </td>
                    <td><?php self::getSelect('formula126'); ?></td>
                </tr>
                <tr>
                    <td>Decurtazione parte variabile per rispetto limite 2016</td>
                    <td><?php self::getSelect('formula127'); ?></td>
                </tr>
                <tr>
                    <td>TOTALE DECURTAZIONI PARTE VARIABILE</td>
                    <td><?php self::getSelect('formula128'); ?></td>
                </tr>
                <tr>
                    <td>TOTALE DECURTAZIONI</td>
                    <td><?php self::getSelect('formula129'); ?></td>
                </tr>
                </tbody>
            </table>
            <br>
            Si evidenzia che il secondo periodo dell’art. 9 c. 2 bis del DL 78/2010 convertito con modificazioni nella
            legge n. 122/2010, inserito dalla Legge di Stabilità 2014 (Legge n. 147/2013) all'art. 1, comma 456,
            stabilisce “ che: «A decorrere dal 1º gennaio 2015, le risorse destinate annualmente al trattamento
            economico accessorio sono decurtate di un importo pari alle riduzioni operate per effetto del precedente
            periodo»
            <br>
            Pertanto, a partire dall'anno 2015 le risorse decentrate dovranno essere ridotte dell'importo decurtato per
            il triennio 2011/2014, mediante la conferma della quota di decurtazione operata nell'anno 2014 per
            cessazioni e rispetto del 2010 (Circolare RGS n. 20 del 8.5.20105).
            <br>
            Nel periodo 2011-2014 <?php self::getSelect('formula130'); ?> risultano decurtazioni rispetto ai vincoli
            sul fondo 2010 e pertanto <?php self::getSelect('formula131'); ?> deve
            essere applicata una riduzione del fondo
            del <?php self::getInput('var130', $infos[130]['valore'], 'orange'); ?> pari a
            € <?php self::getSelect('formula132'); ?>.
            <br>
            Si evidenzia che l’art. 1 c. 236 della L. 208/2015 prevedeva che a decorrere dal 1° gennaio 2016 (nelle more
            dell'adozione dei decreti legislativi attuativi degli articoli 11 e 17 della legge 7 agosto 2015, n. 124,
            con particolare riferimento all'omogeneizzazione del trattamento economico fondamentale e accessorio della
            dirigenza,), l'ammontare complessivo delle risorse destinate annualmente al trattamento accessorio del
            personale non può superare il corrispondente importo dell’anno 20105. Lo stesso comma disponeva la riduzione
            in misura proporzionale dello stesso in conseguenza della cessazione dal servizio di una o più unità di
            personale dipendente (tenendo conto del personale assumibile ai sensi della normativa vigente) .
            <br>
            Si evidenzia inoltre che l'art. 23 del D.Lgs. 75/2017 ha stabilito che “a decorrere dal 1° gennaio 2017,
            l'ammontare complessivo delle risorse destinate annualmente al trattamento accessorio del personale, anche
            di livello dirigenziale, di ciascuna delle amministrazioni pubbliche di cui all'articolo 1,comma 2, del
            decreto legislativo 30 marzo 2001, n. 165, non può superare il corrispondente importo determinato per l'anno
            2016. A decorrere dalla predetta data l'articolo 1, comma 236, della legge 28 dicembre 2015, n. 208 e'
            abrogato.”
            <br>
            In seguito all’introduzione delle disposizioni dell’art. 33 comma 2, del D.L.34/2019, convertito in Legge
            58/2019 (c.d. Decreto “Crescita”), il tetto al salario accessorio, così come introdotto dall'articolo 23,
            comma 2, del D.Lgs 75/2017, può essere modificato. La modalità di applicazione definita nel DPCM del
            17.3.2020, pubblicato in GU in data 27.4.2020, concordata in sede di Conferenza Unificata Stato Regioni del
            11.12.2019, prevede che il limite del salario accessorio, a partire dal 20 aprile 2020, debba essere
            adeguato in aumento rispetto al valore medio procapite del 2018 in caso di incremento del numero di
            dipendenti presenti nel <?php self::getInput('var131', $infos[131]['valore'], 'orange'); ?> , rispetto ai
            presenti al 31.12.2018, al fine di garantire l’invarianza della
            quota media procapite rispetto al 2018. Ed in particolare è fatto salvo il limite iniziale qualora il
            personale in servizio sia inferiore al numero rilevato al 31 dicembre 2018. Tale incremento va calcolato in
            base alle modalità fornite dalla Ragioneria dello Stato da ultimo con nota Prot. 12454 del 15.1.2021.
            <br>
            Nell'anno 2016 <?php self::getSelect('formula133'); ?> risultano decurtazioni rispetto ai vincoli sul fondo
            2015 e pertanto <?php self::getSelect('formula134'); ?> deve essere
            applicata una riduzione del fondo pari a <?php self::getSelect('formula135'); ?>
            <br>
            Si precisa che il totale del fondo (solo voci soggette al blocco) per l'anno 2016 era pari a
            € <?php self::getSelect('formula136'); ?> (include
            eventuale rivalutazione ai sensi dell’art. 33 comma 2, del D.L.34/2019, nel caso l'ente ne abbia facoltà)
            mentre per l’anno <?php self::getInput('var132', $infos[132]['valore'], 'orange'); ?> al netto delle
            decurtazioni è pari ad € <?php self::getSelect('formula137'); ?> .
            <br>
            Pertanto si attesta che il fondo <?php self::getInput('var133', $infos[133]['valore'], 'orange'); ?> risulta
            non superiore al fondo anno 2016 (Tali valori non includono
            avvocatura, ISTAT, di cui art. 67 comma 3 lett. c CCNL 21.5.2018, importi di cui all’art. 67 comma 3 lett. c
            CCNL 21.5.2018, importi di cui all’67 comma 3 lett. a, ove tale attività non risulti ordinariamente resa
            dall’Amministrazione precedentemente l’entrata in vigore del D.Lgs 75/2017, importi di cui all’art. 67 comma
            2 lett.b, economie del fondo dell’anno precedente e economie del fondo straordinario anno precedente).
            <br>
            <h6>
                Sezione IV - Sintesi della costituzione del Fondo sottoposto a certificazione </h6>
            <br>
            <table class="table">
                <tbody>

                <tr>
                    <td>TOTALE Risorse fisse aventi carattere di certezza e stabilità (A)</td>
                    <td><?php self::getSelect('formula138'); ?></td>
                </tr>
                <tr>
                    <td>TOTALE decurtazioni aventi carattere di certezza e stabilita’ (B)</td>
                    <td><?php self::getSelect('formula139'); ?></td>
                </tr>
                <tr>
                    <td>TOTALE Risorse fisse aventi carattere di certezza e stabilità DOPO LE DECURTAZIONI
                        (A-B)

                    </td>
                    <td><?php self::getSelect('formula140'); ?></td>
                </tr>
                <tr>
                    <td>TOTALE Risorse variabili (C)</td>
                    <td><?php self::getSelect('formula141'); ?></td>
                </tr>
                <tr>
                    <td>DECURTAZIONI sulle voci variabili (D)</td>
                    <td><?php self::getSelect('formula142'); ?></td>
                </tr>
                <tr>
                    <td>Totale risorse variabili dopo le decurtazioni (C-D)</td>
                    <td><?php self::getSelect('formula143'); ?></td>
                </tr>
                <tr>
                    <td> TOTALE FONDO
                        (A-B)+ (C-D)
                    </td>
                    <td><?php self::getSelect('formula144'); ?></td>
                </tr>
                </tbody>
            </table>
            <br>
            <h6>
                Sezione V - Risorse temporaneamente allocate all'esterno del fondo </h6>
            Parte non pertinente allo specifico accordo illustrato.
            <br>
            Si precisa che ai sensi dell'Art. 33 del CCNL 22.1.2004 l'indennità di comparto prevede una parte di risorse
            a carico del bilancio (cosiddetta quota a) e una parte a carico delle risorse decentrate (cosiddette quote b
            e c). Gli importi di cui alla lettera a) risultano pari a
            € <?php self::getInput('var134', $infos[134]['valore'], 'orange'); ?>, gli importi di cui alle lettere b) e
            c)
            ammontano ad un totale di € <?php self::getSelect('formula145'); ?> .
            <br>
            <?php self::getTextArea('area31', $infos[135]['valore'], 'red'); ?>
            <br>
            <?php self::getTextArea('area32', $infos[136]['valore'], 'red'); ?>
            <br>
            <h4>Modulo II - Definizione delle poste di destinazione del Fondo per la contrattazione integrativa</h4>
            <br>
            <h6>Sezione I - Destinazioni non disponibili alla contrattazione integrativa o comunque non regolate
                specificamente dal Contratto Integrativo sottoposto a certificazione</h6>
            <br>
            Per l’anno <?php self::getInput('var137', $infos[137]['valore'], 'orange'); ?> con la determina di
            costituzione del Fondo n. <?php self::getInput('var138', $infos[138]['valore'], 'orange'); ?> del
            <?php self::getInput('var139', $infos[139]['valore'], 'orange'); ?>
            il <?php self::getInput('var140', $infos[140]['valore'], 'orange'); ?> ha reso indisponibile alla
            contrattazione ai sensi dell’art. 68
            comma 1 del CCNL 21.5.2018 alcuni compensi gravanti sul fondo (es. indennità di comparto, progressioni
            economiche) poiché già determinate negli anni precedenti.
            <br>
            Vanno, inoltre, sottratte alla contrattazione le risorse non regolate specificatamente dal Contratto
            Integrativo poiché regolate nelle annualità precedenti.
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th>UTILIZZO RISORSE NON DISPONIBILI ALLA CONTRATTAZIONE</th>
                    <th><?php self::getInput('var140', $infos[140]['valore'], 'orange'); ?></th>
                </tr>
                </thead>
                <tbody>

                <tr>
                    <td>Inquadramento ex Led</td>
                    <td><?php self::getSelect('formula146'); ?></td>
                </tr>
                <tr>
                    <td>Progressioni economiche STORICHE</td>
                    <td><?php self::getSelect('formula147'); ?></td>
                </tr>
                <tr>
                    <td>Indennità di comparto art. 33 CCNL 22.01.04, quota a carico fondo

                    </td>
                    <td><?php self::getSelect('formula148'); ?></td>
                </tr>
                <tr>
                    <td>Indennità educatori asilo nido</td>
                    <td><?php self::getSelect('formula149'); ?></td>
                </tr>
                <tr>
                    <td>ALTRI UTILIZZI</td>
                    <td><?php self::getSelect('formula150'); ?></td>
                </tr>
                <tr>
                    <td>Totale utilizzo risorse stabili</td>
                    <td><?php self::getSelect('formula151'); ?></td>
                </tr>
                <tr>
                    <td>TOTALE RISORSE NON REGOLATE SPECIFICAMENTE DAL CONTRATTO INTEGRATIVO
                    </td>
                    <td><?php self::getSelect('formula152'); ?></td>
                </tr>
                </tbody>
            </table>
            <br>
            CALCOLO RISORSE PER PROGRESSIONI ORIZZONTALI IN ESSERE:
            <br>
            <?php self::getTextArea('area33', $infos[137]['valore'], 'red'); ?>
            <br>
            COSTO PER INDENNITA’ DI COMPARTO
            <br>
            <?php self::getTextArea('area34', $infos[138]['valore'], 'red'); ?>
            <br>
            <h6>
                <h6>
                    Sezione III - (eventuali) Decurtazioni del fondo
                </h6>
                <br>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">DESTINAZIONI REGOLATE SPECIFICAMENTE DAL CONTRATTO INTEGRATIVO
                        </th>
                        <th><?php self::getInput('var141', $infos[141]['valore'], 'orange'); ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <tr>
                        <td>Progressioni economiche specificatamente contratte nel CCDI dell'anno</td>
                        <td><?php self::getSelect('formula153'); ?></td>
                    </tr>
                    <tr>
                        <td>Turno</td>
                        <td><?php self::getSelect('formula154'); ?></td>
                    </tr>
                    <tr>
                        <td>Indennità condizioni di lavoro Art. 70 bis CCNL 2018 (Maneggio valori, attività disagiate e
                            esposte a rischi)
                        </td>
                        <td><?php self::getSelect('formula155'); ?></td>
                    </tr>
                    <tr>
                        <td>Reperibilità
                        </td>
                        <td><?php self::getSelect('formula156'); ?></td>
                    </tr>
                    <tr>
                        <td> Indennità specifiche Responsabilità art. 70 quinquies c. 1 CCNL 2018 (ex art. 17 lett. f)
                        </td>
                        <td><?php self::getSelect('formula157'); ?></td>
                    </tr>
                    <tr>
                        <td>Indennità specifiche Responsabilità art. 70 quinquies c. 1 CCNL 2018 (ex art. 17 lett. i)
                        </td>
                        <td><?php self::getSelect('formula158'); ?></td>
                    </tr>
                    <tr>
                        <td> Particolare compenso incentivante personale Unioni dei comuni (art. 13 c. 5 CCNL
                            22.1.2004)
                        </td>
                        <td><?php self::getSelect('formula159'); ?></td>
                    </tr>
                    <tr>
                        <td> Centri estivi asili nido (art 31 c. 5CCNL 14 .9.2000 Code)</td>
                        <td><?php self::getSelect('formula160'); ?></td>
                    </tr>
                    <tr>
                        <td>Compenso previsto dall'art.24, comma 1 CCNL 14.9.2000, per il personale che presta attività
                            lavorativa nel giorno destinato al riposo settimanale
                        </td>
                        <td><?php self::getSelect('formula161'); ?></td>
                    </tr>
                    <tr>
                        <td>Premi collegati alla performance organizzativa – art. 68 c. 2 lett. a) CCNL 2018</td>
                        <td><?php self::getSelect('formula162'); ?></td>
                    </tr>
                    <tr>
                        <td>Premi collegati alla performance individuale - art. 68 c. 2 lett. b) CCNL 2018</td>
                        <td><?php self::getSelect('formula163'); ?></td>
                    </tr>
                    <tr>
                        <td>Premi collegati alla performance organizzativa - Obiettivi finanziati con risorse Art. 67 c.
                            5 lett. b) CCNL 2018
                        </td>
                        <td><?php self::getSelect('formula164'); ?></td>
                    </tr>
                    <tr>
                        <td>Premi collegati alla performance organizzativa - Obiettivi collegati a risorse art 67 c. 5
                            lett. b di potenziamento dei servizi di controllo finalizzati alla sicurezza urbana e
                            stradale Art. 56 quater CCNL 2018
                        </td>
                        <td><?php self::getSelect('formula165'); ?></td>
                    </tr>
                    <tr>
                        <td>Indennità di servizio esterno – art. 56 quinquies CCNL 2018 (Vigilanza)</td>
                        <td><?php self::getSelect('formula166'); ?></td>
                    </tr>
                    <tr>
                        <td>Indennità di funzione – Art. 56 sexies CCNL 2018 (Vigilanza)</td>
                        <td><?php self::getSelect('formula167'); ?></td>
                    </tr>
                    <tr>
                        <td>Compensi 50% economie da Piani di Razionalizzazione - Art. 67 c. 3 lett. b) CCNL 2018-Art.
                            16 C. 5 L. 111/2011
                        </td>
                        <td><?php self::getSelect('formula168'); ?></td>
                    </tr>
                    <tr>
                        <td>ALTRI UTILIZZI (contrattati nel CCDI dell'anno)
                        </td>
                        <td><?php self::getSelect('formula169'); ?></td>
                    </tr>
                    <tr>
                        <td>Premi collegati alla performance organizzativa - Compensi per SPONSORIZZAZIONI Art. 67 c. 3
                            lett. a) CCNL 2018
                        </td>
                        <td><?php self::getSelect('formula170'); ?></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018 FUNZIONI TECNICHE RIF Art. 113 comma 2 e 3 D.LGS. 18 APRILE
                            2016, N. 50
                        </td>
                        <td><?php self::getSelect('formula171'); ?></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018 COMPENSI IMU e TARI c. 1091 L. 145/2018
                        </td>
                        <td><?php self::getSelect('formula172'); ?></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. h CCNL 2018 - Compensi per notifiche
                        </td>
                        <td><?php self::getSelect('formula173'); ?></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018 RIF – ISTAT
                        </td>
                        <td><?php self::getSelect('formula174'); ?></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018 RIF - ICI
                        </td>
                        <td><?php self::getSelect('formula175'); ?></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018 RIF – avvocatura
                        </td>
                        <td><?php self::getSelect('formula176'); ?></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018 RIF - Diritto soggiorno Unione Europea D.lgs 30/2007
                        </td>
                        <td><?php self::getSelect('formula177'); ?></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018 Legge Regionale specifica
                        </td>
                        <td><?php self::getSelect('formula178'); ?></td>
                    </tr>
                    <tr>
                        <td>Altri utilizzi Art. 68 c. 2 lett. g) CCNL 2018
                        </td>
                        <td><?php self::getSelect('formula179'); ?></td>
                    </tr>
                    <tr>
                        <td>Quota recupero somme (Art. 4 DL 16/2014 Salva Roma Ter)
                        </td>
                        <td><?php self::getSelect('formula180'); ?></td>
                    </tr>
                    <tr>
                        <td>TOTALE RISORSE REGOLATE SPECIFICAMENTE DAL CONTRATTO INTEGRATIVO
                        </td>
                        <td><?php self::getSelect('formula180'); ?></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <h6> Sezione III - (eventuali) Destinazioni ancora da regolare</h6>
                <br>
                Parte non pertinente allo specifico accordo illustrato.
                <br>
                Le risorse ancora da contrattare ammontano ad € <?php self::getSelect('formula181'); ?>
                <br>

                <h6>Sezione IV - Sintesi della definizione delle poste di destinazione del Fondo per la contrattazione
                    integrativa sottoposto a certificazione</h6>
                <br>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>TOTALE RISORSE non regolate specificamente dal Contratto Integrativo (A)
                        </td>
                        <td><?php self::getSelect('formula182'); ?></td>
                    </tr>
                    <tr>
                        <td>TOTALE RISORSE regolate specificamente dal Contratto Integrativo (B)
                        </td>
                        <td><?php self::getSelect('formula183'); ?></td>
                    </tr>
                    <tr>
                        <td>TOTALE UTILIZZO
                            (A+B)

                        </td>
                        <td><?php self::getSelect('formula184'); ?></td>
                    </tr>
                    <tr>
                        <td>TOTALE DESTINAZIONI ANCORA DA REGOLARE [TOTALE FONDO – (A+B)]
                        </td>
                        <td><?php self::getSelect('formula185'); ?></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <h6>Sezione V Destinazioni temporaneamente allocate all'esterno del fondo</h6>
                <br>
                Parte non pertinente allo specifico accordo illustrato.
                <br>
                Si precisa che ai sensi dell'Art. 33 del CCNL 22.1.2004 l'indennità di comparto prevede una parte di
                risorse a carico del bilancio (cosiddetta quota a) e una parte a carico delle risorse decentrate
                (cosiddette quote b e c). Gli importi di cui alla lettera a) risultano pari a
                € <?php self::getInput('var142', $infos[142]['valore'], 'orange'); ?>, gli importi di cui
                alle lettere b) e c) ammontano ad un totale di € <?php self::getSelect('formula186'); ?> .
                <br>
                <?php self::getTextArea('area35', $infos[143]['valore'], 'red'); ?>
                <br>
                <?php self::getTextArea('area36', $infos[144]['valore'], 'red'); ?>
                <br>
                <h6>Sezione VI - Attestazione motivata, dal punto di vista tecnico-finanziario, del rispetto di vincoli
                    di
                    carattere generale</h6>
                <br>
                La presente relazione, in ossequio a quanto disposto dall’art. 40 c. 3 sexies del D.Lgs 165/2001, così
                come modificato dal D. Lgs 150/2009 persegue l’obiettivo di fornire una puntuale e dettagliata
                relazione, dal punto di vista finanziario, circa le risorse economiche costituenti il fondo per le
                risorse decentrate e, dal punto di vista tecnico, per illustrare le scelte effettuate e la coerenza di
                queste con le direttive dell’Amministrazione.
                <br>
                Con la presente si attesta:
                <br>
                a) Il rispetto della copertura delle risorse destinate a finanziare indennità di carattere certo e
                continuativo con risorse stabili e consolidate.
                <br>
                Come evidenziato dalle precedenti sezioni, le indennità fisse di carattere certo e continuativo (PEO,
                Indennità di comparto) pari a € <?php self::getSelect('formula186'); ?> sono completamente finanziate
                dalle risorse stabili pari ad € <?php self::getSelect('formula187'); ?>.
                b) Il rispetto del principio di attribuzione selettiva degli incentivi economici.
                Le previsioni sono coerenti con le disposizioni in materia di meritocrazia e premialità in quanto viene
                applicato il Sistema di Valutazione e Misurazione della Performance, adeguato al D.lgs 150/2009 e
                all’art. 68 comma lett. a-b del CCNL 21.5.2018.
                <br>
                Le risorse destinate alla performance saranno riconosciute attraverso la predisposizione di obiettivi
                strategici ed operativi dell’Amministrazione (contenuti nel Piano Performance), al fine di contribuire
                al raggiungimento dei risultati previsti negli strumenti di pianificazione e gestione.
                Sinteticamente viene riportata la modalità di ripartizione delle risorse destinate alla performance
                <br>
                <?php self::getTextArea('area37', $infos[145]['valore'], 'red'); ?>
                <br>
                c) Il rispetto del principio di selettività delle progressioni di carriera.
                In particolare, si evidenzia che
                <br>
                per l’anno in corso non è previsto il riconoscimento di progressioni orizzontali
                <br>
                per l’anno in corso è previsto il riconoscimento di progressioni orizzontali che saranno attribuite con
                la seguente modalità
                <br>
                <?php self::getTextArea('area38', $infos[146]['valore'], 'red'); ?>
                <br>
                <h4> Modulo III - Schema generale riassuntivo del Fondo per la contrattazione integrativa e confronto
                    con il
                    corrispondente Fondo certificato dell’anno precedente</h4>
                <br>
                In dettaglio:
                <br>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Tabella 1</th>

                    </tr>
                    <tr>
                        <th scope="col">COSTITUZIONE DEL FONDO</th>
                        <th scope="col">Fondo <?php self::getInput('var147', $infos[147]['valore'], 'orange'); ?>(A)
                        </th>
                        <th scope="col">Fondo <?php self::getSelect('formula188'); ?>
                            (B)
                        </th>
                        <th scope="col">Diff A-B</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">Risorse fisse aventi carattere di certezza e stabilità</th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Risorse storiche (A)</th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Unico importo consolidato anno 2017 (art. 67 c. 1 Ccnl EELL 2018)</td>
                        <td><?php self::getSelect('formula189'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Incrementi stabili (A)</th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Art. 67 c. 2 lett. c) CCNL 2018 - RIA e assegni ad personam</td>
                        <td><?php self::getSelect('formula190'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Art. 67 c. 2 lett. d) CCNL 2018 - eventuali risorse riassorbite</td>
                        <td><?php self::getSelect('formula191'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Art. 67 c. 2 lett. e) CCNL 2018 - Oneri trattamento accessorio personale trasferito dal
                            2018
                        </td>
                        <td><?php self::getSelect('formula192'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Art. 67 c. 2 lett. g) CCNL 2018 - Riduzione stabile Fondo Straordinario dal 2018</td>
                        <td><?php self::getSelect('formula193'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Art . 67 c. 5 lett. a) CCNL 2018 - incremento dotazione organica dal 2018</td>
                        <td><?php self::getSelect('formula194'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Art. 33 comma 2 DL 34/2019 - Incremento valore medio procapite del fondo rispetto al 2018
                        </td>
                        <td><?php self::getSelect('formula195'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Incrementi con carattere di certezza e stabilità NON soggetti al limite (b)
                        </th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Art. 67 c. 2 lett. b) CCNL 2018 - Rivalutazione delle PEO</td>
                        <td><?php self::getSelect('formula196'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Art. 67 c. 2 lett. a) CCNL 2018 Incremento € 83,20 a valere dal 2019</td>
                        <td><?php self::getSelect('formula197'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Art. 11 c.1 lett. b) D.L.135/2018 R148</td>
                        <td><?php self::getSelect('formula198'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Art. 67 c. 2 lett. e) CCNL 2018 – Rif Art. 1 c. 800 L. 205/2017 Armonizzazione personale
                            province transitato
                        </td>
                        <td><?php self::getSelect('formula199'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Altre risorse stabili</td>
                        <td><?php self::getSelect('formula200'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Totale risorse fisse aventi carattere di certezza e stabilità SOGGETTE al limite (A+a)</td>
                        <td><?php self::getSelect('formula201'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Totale risorse fisse con carattere di certezza
                            e stabilità
                        </td>
                        <td><?php self::getSelect('formula202'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Risorse variabili
                        </th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Risorse variabili sottoposte al limite
                        </th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Art. 67 c. 3 lett. a) CCNL 2018 – sponsorizzazioni
                        </td>
                        <td><?php self::getSelect('formula203'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Art. 67 c. 3 lett. c) CCNL 2018 ICI
                        </td>
                        <td><?php self::getSelect('formula204'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Art. 67 c. 3 lett. c) CCNL 2018 Legge Regionale specifica (es. SARDEGNA n. 19 del 1997)
                        </td>
                        <td><?php self::getSelect('formula205'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Art. 67 c. 3 lett. f) CCNL 2018 – Compensi per Notifiche
                        </td>
                        <td><?php self::getSelect('formula206'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Art. 67 c. 4 CCNL 2018 (1,2% m salari 1997)
                        </td>
                        <td><?php self::getSelect('formula207'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Art. 67 c. 5 lett. b) CCNL 2018 - Obiettivi dell'Ente (anche potenziamento controllo Codice
                            Strada)
                        </td>
                        <td><?php self::getSelect('formula208'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> INTEGR. FONDO CCIAA IN EQ. FIN. (ART.15 C.1 L. N CCNL 98-01) R116
                        </td>
                        <td><?php self::getSelect('formula209'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Art. 67 c. 3 lett. d) CCNL 2018 - Ria e assegni ad personam personale cessato quota rateo
                            anno di cessazione
                        </td>
                        <td><?php self::getSelect('formula210'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Art. 67 c. 3 lett. g) CCNL 2018 - Compensi personale case da gioco
                        </td>
                        <td><?php self::getSelect('formula211'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Art. 67 c. 3 lett. k) CCNL 2018 - Oneri trattamento accessorio personale trasferito
                        </td>
                        <td><?php self::getSelect('formula212'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Art. 67 c.7 e Art.15 c.7 CCNL 2018 – Quota incremento Fondo per riduzione retribuzione di
                            PO e di risultato
                        </td>
                        <td><?php self::getSelect('formula213'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Altre risorse
                        </td>
                        <td><?php self::getSelect('formula214'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Poste variabili non sottoposte al limite
                        </th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Art. 67 c. 3 lett. b) CCNL 2018 (Piani di razionalizzazione)
                        </td>
                        <td><?php self::getSelect('formula215'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 67 c. 3 lett. c) CCNL 2018 ISTAT
                        </td>
                        <td><?php self::getSelect('formula216'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 67 c. 3 lett. c) CCNL 2018 AVVOCATURA
                        </td>
                        <td><?php self::getSelect('formula217'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 67 c. 3 lett. c) CCNL 2018 FUNZIONI TECNICHE
                        </td>
                        <td><?php self::getSelect('formula218'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 67 c. 3 lett. c) CCNL 2018 Compensi IMU e TARI
                        </td>
                        <td><?php self::getSelect('formula219'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 67 c. 3 lett. c) CCNL 2018 Somme finanziate da fondi di derivazione dell'Unione Europea
                        </td>
                        <td><?php self::getSelect('formula220'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Altro - Art. 67 c. 3 lett. c) CCNL 2018
                        </td>
                        <td><?php self::getSelect('formula221'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 67 c. 3 lett. a) CCNL 2018 – sponsorizzazioni
                        </td>
                        <td><?php self::getSelect('formula222'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Altre risorse
                        </td>
                        <td><?php self::getSelect('formula223'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 1 CCNL 2018 - Risparmi Fondo Stabile Anno Precedente
                        </td>
                        <td><?php self::getSelect('formula224'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 67 c. 3 lett. e) CCNL 2018 - Risparmi Fondo Straordinario Anno Precedente
                        </td>
                        <td><?php self::getSelect('formula225'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 67 c. 5 lett. b) CCNL 2018 - Quota incremento CDS maggior incasso rispetto anno
                            precedente
                        </td>
                        <td><?php self::getSelect('formula226'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Totale risorse variabili
                        </td>
                        <td><?php self::getSelect('formula227'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Decurtazioni del fondo
                        </th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Decurtazione operate nel periodo 2011/2014 ai sensi dell'art. 9 C. 2 bis L.122/2010 secondo
                            periodo
                        </td>
                        <td><?php self::getSelect('formula228'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Decurtazioni operate nel 2016 per cessazioni e rispetto limite 2015
                        </td>
                        <td><?php self::getSelect('formula229'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Decurtazione per rispetto limite 2016
                        </td>
                        <td><?php self::getSelect('formula230'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Altre decurtazioni del fondo
                        </td>
                        <td><?php self::getSelect('formula231'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Totale decurtazioni del fondo
                        </td>
                        <td><?php self::getSelect('formula232'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Risorse del Fondo sottoposte a certificazione
                        </th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Risorse fisse aventi carattere di certezza e stabilità
                        </td>
                        <td><?php self::getSelect('formula233'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Risorse variabili
                        </td>
                        <td><?php self::getSelect('formula234'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Altre decurtazioni
                        </td>
                        <td><?php self::getSelect('formula235'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Totale risorse Fondo sottoposte a certificazione
                        </td>
                        <td><?php self::getSelect('formula236'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Tabella 2</th>

                    </tr>
                    <tr>
                        <th scope="col">COSTITUZIONE DEL FONDO</th>
                        <th scope="col">Fondo <?php self::getInput('var148', $infos[148]['valore'], 'orange'); ?>(A)
                        </th>
                        <th scope="col">Fondo <?php self::getSelect('formula237'); ?>
                            (B)
                        </th>
                        <th scope="col">Diff A-B</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">Destinazioni non regolate in sede di contrattazione integrativa</th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Inquadramento ex Led</td>
                        <td><?php self::getSelect('formula238'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>

                        <td>Progressioni economiche STORICHE</td>
                        <td><?php self::getSelect('formula239'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Indennità di comparto art.33 ccnl 22.01.04, quota a carico fondo</td>
                        <td><?php self::getSelect('formula240'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Indennità educatori asilo nido
                        </td>
                        <td><?php self::getSelect('formula241'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>ALTRI UTILIZZI</td>
                        <td><?php self::getSelect('formula242'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Totale destinazioni non regolate in sede di contrattazione integrativa</td>
                        <td><?php self::getSelect('formula243'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <th scope="row">Destinazioni regolate in sede di contrattazione integrativa
                        </th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Progressioni economiche specificatamente contratte nel CCDI dell'anno</td>
                        <td><?php self::getSelect('formula244'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Indennità di turno</td>
                        <td><?php self::getSelect('formula245'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Indennità condizioni di lavoro Art. 70 bis CCNL 2018 (Maneggio valori, attività disagiate e
                            esposte a rischi)
                        </td>
                        <td><?php self::getSelect('formula246'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Reperibilità
                        </td>
                        <td><?php self::getSelect('formula247'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td>Indennità Specifiche Responsabilità art. 70 quinquies c. 1 CCNL 2018 (ex art. 17 lett. f)
                        </td>
                        <td><?php self::getSelect('formula248'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Indennità Specifiche Responsabilità art. 70 quinquies c. 1 CCNL 2018 (ex art. 17 lett. i)
                            R77
                        </td>
                        <td><?php self::getSelect('formula249'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Indennità particolare compenso incentivante (personale Unioni dei comuni)
                        </td>
                        <td><?php self::getSelect('formula250'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>

                        <td> Indennità centri estivi asili nido art 31 comma 5 CCNL 14 -9- 2000 code
                        </td>
                        <td><?php self::getSelect('formula251'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Compenso previsto dall'art.24, comma 1 CCNL 14.9.2000, per il personale che presta attività
                            lavorativa nel giorno destinato al riposo settimanale
                        </td>
                        <td><?php self::getSelect('formula252'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>

                        <td> Premi collegati alla performance organizzativa – art. 68 c. 2 lett. a) CCNL 2018
                        </td>
                        <td><?php self::getSelect('formula253'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Premi collegati alla performance individuale - art. 68 c. 2 lett. b) CCNL 2018 contrattate
                            nel CCDI dell'anno
                        </td>
                        <td><?php self::getSelect('formula254'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Premi collegati alla performance organizzativa - Obiettivi finanziati con risorse Art. 67
                            c. 5 lett. b) CCNL 2018
                        </td>
                        <td><?php self::getSelect('formula255'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Premi collegati alla performance organizzativa -Obiettivi finanziati da risorse art 67 c. 5
                            lett. b) per potenziamento dei servizi di controllo finalizzati alla sicurezza urbana e
                            stradale Art. 56 QUATER CCNL 2018
                        </td>
                        <td><?php self::getSelect('formula256'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Compensi 50% economie da Piani di Razionalizzazione - Art. 67 c. 3 lett. b) CCNL 2018-Art.
                            16 C. 5 L. 111/2011
                        </td>
                        <td><?php self::getSelect('formula257'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Indennità di servizio esterno – art. 56 quinquies CCNL 2018 (Vigilanza)
                        </td>
                        <td><?php self::getSelect('formula258'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Indennità di funzione – Art. 56 sexies CCNL 2018 (Vigilanza)
                        </td>
                        <td><?php self::getSelect('formula259'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> ALTRE indennità contrattate nel CCDI dell'anno trasferito
                        </td>
                        <td><?php self::getSelect('formula260'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> Premi collegati alla performance organizzativa – Compensi per sponsorizzazioni Art. 67 c. 3
                            lett. a) CCNL 2018
                        </td>
                        <td><?php self::getSelect('formula261'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018
                            FUNZIONI TECNICHE

                        </td>
                        <td><?php self::getSelect('formula262'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td> Art. 68 c. 2 lett. g) CCNL 2018 - Compensi IMU e TARI
                        </td>
                        <td><?php self::getSelect('formula263'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. h CCNL 2018 - Compensi per notifiche
                        </td>
                        <td><?php self::getSelect('formula264'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018
                            RIF – ISTAT

                        </td>
                        <td><?php self::getSelect('formula265'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018
                            RIF - ICI

                        </td>
                        <td><?php self::getSelect('formula266'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018
                            RIF - avvocatura

                        </td>
                        <td><?php self::getSelect('formula267'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018
                            RIF - Diritto soggiorno Unione Europea D.lgs 30/2007

                        </td>
                        <td><?php self::getSelect('formula268'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018
                            Legge Regionale specifica

                        </td>
                        <td><?php self::getSelect('formula269'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Art. 68 c. 2 lett. g) CCNL 2018
                            RIF - Legge o ALTRO

                        </td>
                        <td><?php self::getSelect('formula270'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Quota recupero somme (Art. 4 DL 16/2014 Salva Roma Ter)
                        </td>
                        <td><?php self::getSelect('formula271'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Totale destinazioni regolate in sede di contrattazione integrativa
                        </td>
                        <td><?php self::getSelect('formula272'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">(eventuali) destinazioni da regolare
                        </th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Risorse ancora da contrattare
                        </td>
                        <td><?php self::getSelect('formula273'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Totale (eventuali) destinazioni ancora da regolare
                        </td>
                        <td><?php self::getSelect('formula274'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <th scope="row">Destinazioni fondno sottoposte a certificazione
                        </th>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Destinazioni non regolate in sede di contrattazione integrativa
                        </td>
                        <td><?php self::getSelect('formula275'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>

                    <tr>
                        <td>Destinazioni regolate in sede di contrattazione integrativa
                        </td>
                        <td><?php self::getSelect('formula276'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>(eventuali) destinazioni ancora da regolare
                        </td>
                        <td><?php self::getSelect('formula277'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Totale destinazioni Fondo sottoposte a certificazione
                        </td>
                        <td><?php self::getSelect('formula278'); ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                <h4>Modulo IV - Compatibilità economico-finanziaria e modalità di copertura degli oneri del Fondo con
                    riferimento agli strumenti annuali e pluriennali di bilancio</h4>
                <br>
                <h6>Sezione I - Esposizione finalizzata alla verifica che gli strumenti della contabilità
                    economico-finanziaria dell’Amministrazione presidiano correttamente i limiti di spesa del Fondo
                    nella
                    fase programmatoria della gestione</h6>
                <br>
                Per ciascun argomento si evidenzia quanto segue:
                <br>
                a) Rispetto dei vincoli di bilancio: l’ammontare delle risorse per le quali si contratta la destinazione
                trovano copertura negli stanziamenti del bilancio
                anno <?php self::getInput('var149', $infos[149]['valore'], 'orange'); ?>;
                <br>

                b) Rispetto dei vincoli derivanti dalla legge e dal contratto nazionale Le fonti di alimentazione del
                fondo sono previste dal contratto nazionale e la loro quantificazione è elaborata sulla base delle
                disposizioni stesse (Vedi Modulo I). La destinazione comprende esclusivamente istituti espressamente
                devoluti dalla contrattazione nazionale a quella decentrata (Vedi Modulo II)
                <br>
                c) Imputazione nel Bilancio: La destinazione del fondo disciplinata dall’ipotesi di accordo in oggetto
                trova finanziamento nel bilancio di
                previsione <?php self::getInput('var150', $infos[150]['valore'], 'orange'); ?> come segue:
                <br>
                 le voci di utilizzo fisse (Indennità di comparto e progressioni orizzontali già in atto) saranno
                imputate ai capitoli/interventi di spesa previsti in bilancio per ciascun dipendente;
                <br>
                 la restante parte di utilizzo oggetto di contrattazione (fondo generale e indennità individuali) sarà
                imputata all’intervento <?php self::getInput('var151', $infos[151]['valore'], 'orange'); ?> del
                bilancio <?php self::getInput('var152', $infos[152]['valore'], 'orange'); ?> gestione competenza.
                <br>
                 le voci relative agli incentivi di cui all’art. 113 del D. Lgs 50/2016 saranno iscritte negli
                stanziamenti dei diversi interventi a cui si riferiscono;
                <br>
                Si attesta che la spesa del personale per l'anno 2008 era pari ad
                € <?php self::getInput('var153', $infos[153]['valore'], 'orange'); ?>
                <br>
                Si attesta che la spesa del personale per la media del triennio 2011-2013 era pari ad
                € <?php self::getInput('var154', $infos[154]['valore'], 'orange'); ?>
                <br>
                Si attesta che la spesa del personale per
                l'anno <?php self::getInput('var155', $infos[155]['valore'], 'orange'); ?> è pari ad
                € <?php self::getInput('var156', $infos[156]['valore'], 'orange'); ?>
                <br>
                Si attesta, pertanto, che sono stati rispettati i limiti dei parametri di virtuosità fissati per la
                spesa di personale dalle attuali norme vigenti.
                <br>
                Sezione II -Esposizione finalizzata alla verifica a consuntivo che il limite di spesa del Fondo
                dell'anno precedente risulta rispettato
                <br>
                La costituzione del fondo per l'anno <?php self::getInput('var157', $infos[157]['valore'], 'orange'); ?>
                , così come previsto dal D.Lgs. 75/2017 non risulta superare
                l'importo determinato per l'anno 2016.
                <br>
                Si precisa, inoltre, che il fondo dell'anno precedente risultava pari a
                € <?php self::getInput('var158', $infos[158]['valore'], 'orange'); ?> mentre per
                l'anno <?php self::getInput('var159', $infos[159]['valore'], 'orange'); ?>
                è pari ad € <?php self::getSelect('formula279'); ?>.
                <br>
                In seguito all’introduzione delle disposizioni dell’art. 33 comma 2, del D.L.34/2019, convertito in
                Legge 58/2019 (c.d. Decreto “Crescita”), il tetto al salario accessorio, così come introdotto
                dall'articolo 23, comma 2, del D.Lgs 75/2017, può essere modificato. La modalità di applicazione
                definita nel DPCM del 17.3.2020, pubblicato in GU in data 27.4.2020, concordata in sede di Conferenza
                Unificata Stato Regioni del 11.12.2019, prevede che il limite del salario accessorio, a partire dal 20
                aprile 2020, debba essere adeguato in aumento rispetto al valore medio procapite del 2018 in caso di
                incremento del numero di dipendenti presenti
                nel <?php self::getInput('var160', $infos[160]['valore'], 'orange'); ?>, rispetto ai presenti al
                31.12.2018, al fine di
                garantire l’invarianza della quota media procapite rispetto al 2018. Tale incremento va calcolato in
                base alle modalità fornite dalla Ragioneria dello Stato da ultimo con nota Prot. 12454 del 15.1.2021.
                <br>
                Si precisa che in questo Ente:
                <br>
                • il numero di dipendenti in servizio
                nel <?php self::getInput('var161', $infos[161]['valore'], 'orange'); ?> calcolato in base alle modalità
                fornite dalla Ragioneria
                dello Stato da ultimo con nota Prot. 12454 del 15.1.2021, pari
                a <?php self::getSelect('formula280'); ?> è inferiore o uguale al numero dei
                dipendenti in servizio al 31.12.2018 pari a <?php self::getSelect('formula281'); ?>, pertanto, in
                attuazione dell’art. 33 c. 2 D.L. 34/2019
                convertito nella L. 58/2019, il fondo e il limite di cui all’art. 23 c.2 D.Lgs. 75/2017 non deve essere
                adeguato in aumento al fine di garantire il valore medio pro-capite riferito al 2018
                <br>
                • il numero di dipendenti in servizio
                nel <?php self::getInput('var162', $infos[162]['valore'], 'orange'); ?> calcolato in base alle modalità
                fornite dalla Ragioneria
                dello Stato da ultimo con nota Prot. 12454 del 15.1.2021, pari
                a <?php self::getSelect('formula282'); ?> è superiore al numero dei
                dipendenti in servizio al 31.12.2018 pari a <?php self::getSelect('formula283'); ?>, pertanto, in
                attuazione dell’art. 33 c. 2 D.L. 34/2019
                convertito nella L. 58/2019, il fondo risorse decentrate e il relativo limite di cui all’art. 23 c. 2
                D.Lgs. 75/2017 deve essere adeguato in aumento al fine di garantire il valore medio pro-capite riferito
                al 2018, per un importo pari ad € <?php self::getSelect('formula284'); ?>;
                <br>
                • l’Ente si impegna a modificare la costituzione del fondo nel caso di incremento o diminuzione del
                numero di dipendenti in servizio rispetto al 31.12.2018 e comunque a rideterminare (anche in
                diminuzione) il salario accessorio complessivo in caso di sopraggiunte modifiche normative, chiarimenti
                ministeriali, interventi giurisprudenziali, sentenze o pareri di Corte dei Conti sulle modalità di
                calcolo di tale integrazione;
                <br>
                Si precisa che i valori esposti equivalgono al totale del fondo dell’anno al netto della eventuale
                decurtazione del limite dell’anno 2016. Tali valori non includono avvocatura, ISTAT, di cui art. 67
                comma 3 lett. c CCNL 21.5.2018, importi di cui all’art. 67 comma 3 lett. c CCNL 21.5.2018, importi di
                cui all’67 comma 3 lett. a, ove tale attività non risulti ordinariamente resa dall’Amministrazione
                precedentemente l’entrata in vigore del D.Lgs 75/2017, importi di cui all’art. 67 comma 2 lett.b,
                economie del fondo dell’anno precedente e economie del fondo straordinario anno precedente.
                <br>

                Viene ulteriormente specificato che il limite di cui all’art. 23 c. 2 del Dl. Lgs 75/2017 deve essere
                rispettato per l’Amministrazione nel suo complesso, in luogo che distintamente per le diverse categorie
                di personale (es. dirigente e non dirigente) che operano nell’amministrazione, così come chiarito da
                diverse ma costanti indicazioni di sezioni regionali della Corte dei Conti e dal MEF e RGS;
                <br>
                • l'Ente si è avvalso della facoltà prevista dall'art. 11-bis comma 2 D.L. 135/2018, che prevede di
                utilizzare le facoltà assunzionali per incrementare il fondo delle PO;
                <br>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">Anno 2016</th>
                        <th scope="col">Anno <?php self::getInput('var163', $infos[163]['valore'], 'orange'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Fondo complessivo risorse decentrate soggette al limite</td>
                        <td><?php self::getSelect('formula285'); ?></td>
                        <td><?php self::getSelect('formula286'); ?></td>
                    </tr>
                    <tr>
                        <td>Indennità di Posizione e risultato PO</td>
                        <td><?php self::getSelect('formula287'); ?></td>
                        <td><?php self::getSelect('formula288'); ?></td>
                    </tr>
                    <tr>
                        <td>Indennità di Posizione e risultato PO anno corrente COMPRESO Quota integrazione PO
                            finanziate dalla rinuncia delle capacità assunzionali (Incremento Art. 11-bis comma 2 D.L.
                            135/2018) e Quota art. 33 del DL 34/2019
                        </td>
                        <td><?php self::getSelect('formula289'); ?></td>
                        <td><?php self::getSelect('formula290'); ?></td>
                    </tr>
                    <tr>
                        <td>Fondo Straordinario 2016</td>
                        <td><?php self::getSelect('formula291'); ?></td>
                        <td><?php self::getSelect('formula292'); ?></td>
                    </tr>
                    <tr>
                        <td>Indennità di Posizione e risultato DIRIGENTI</td>
                        <td><?php self::getSelect('formula293'); ?></td>
                        <td><?php self::getSelect('formula294'); ?></td>
                    </tr>
                    <tr>
                        <td>Quota di incremento valore medio procapite del trattamento accessorio rispetto al 2018 -
                            Art. 33 c. 2 DL 34/2019- aumento virtuale limite 2016
                        </td>
                        <td><?php self::getSelect('formula295'); ?></td>
                        <td><?php self::getSelect('formula296'); ?></td>
                    </tr>
                    <tr>
                        <td>Quota di incremento valore medio procapite del trattamento accessorio rispetto al 2018 -
                            Art. 33 c. 2 DL 34/2019- aumento virtuale limite 2016
                        </td>
                        <td><?php self::getSelect('formula297'); ?></td>
                        <td><?php self::getSelect('formula298'); ?></td>
                    </tr>
                    <tr>
                        <td>TOTALE TRATTAMENTO ACCESSORIO SOGGETTO AL LIMITE ART. 23 C. 2 D.LGS 75/2017 COMPRESO Quota
                            integrazione PO finanziate dalla rinuncia delle capacità assunzionali (Incremento Art.
                            11-bis comma 2 D.L. 135/2018) e Quota art. 33 del DL 34/2019
                        </td>
                        <td><?php self::getSelect('formula299'); ?></td>
                        <td><?php self::getSelect('formula300'); ?></td>
                    </tr>
                    <tr>
                        <td>Quota integrazione PO finanziate dalla rinuncia delle capacità assunzionali (Incremento Art.
                            11-bis comma 2 D.L. 135/2018)
                        </td>
                        <td><?php self::getSelect('formula301'); ?></td>
                        <td><?php self::getSelect('formula302'); ?></td>
                    </tr>
                    <tr>
                        <td>RISPETTO DEL LIMITE TRATTAMENTO ACCESSORIO</td>
                        <td><?php self::getSelect('formula303'); ?></td>
                        <td><?php self::getSelect('formula304'); ?></td>
                    </tr>
                    <tr>
                        <td>RISPETTO DEL LIMITE TRATTAMENTO ACCESSORIO COMPRESO Quota integrazione PO finanziate dalla
                            rinuncia delle capacità assunzionali (Incremento Art. 11-bis comma 2 D.L. 135/2018) e Quota
                            art. 33 del DL 34/2019
                        </td>
                        <td><?php self::getSelect('formula305'); ?></td>
                        <td><?php self::getSelect('formula306'); ?></td>
                    </tr>
                    </tbody>
                </table>
                <br>
                Per quanto riguarda la spesa, esaminata la parte di utilizzo oggetto della contrattazione, si evidenzia
                che a consuntivo risulta rispettato il limite di spesa del Fondo, pertanto l’ente risulta nella presente
                condizione:
                <br>
                <?php self::getTextArea('area39', $infos[164]['valore'], 'red'); ?>
                <br>
                <?php self::getTextArea('area40', $infos[165]['valore'], 'red'); ?>
                <br>
                <h6>Sezione III – Verifica delle disponibilità finanziarie dell'Amministrazione ai fini della copertura
                    delle diverse voci di destinazione del Fondo</h6>
                <br>
                Si rappresenta che, in ossequio ai disposti di cui all'art. 48, comma 4, ultimo periodo, del D.Lgs.
                n.165/2001, l'Ente ha autorizzato, con distinta indicazione dei mezzi di copertura, le spese relative al
                contratto collettivo decentrato integrativo – parte economica
                anno <?php self::getInput('var166', $infos[166]['valore'], 'orange'); ?>, attraverso le procedure di
                approvazione del bilancio di previsione
                dell'esercizio <?php self::getInput('var167', $infos[167]['valore'], 'orange'); ?>. La spesa derivante
                dalla contrattazione
                decentrata trova copertura sulla disponibilità delle pertinenti risorse previste nel bilancio di
                previsione <?php self::getInput('var168', $infos[168]['valore'], 'orange'); ?>, approvato con
                deliberazione consiliare n. <?php self::getInput('var169', $infos[169]['valore'], 'orange'); ?> del
                <?php self::getInput('var170', $infos[170]['valore'], 'orange'); ?> esecutiva.
                <br>
                L’Ente non versa in condizioni deficitarie.
                <br>
                La costituzione del fondo per le risorse decentrate risulta compatibile con i vincoli in tema di
                contenimento della spesa del personale.
                <br>
                Il totale del fondo come da determinazione
                n. <?php self::getInput('var171', $infos[171]['valore'], 'orange'); ?> del
                <?php self::getInput('var172', $infos[172]['valore'], 'orange'); ?> è impegnato al
                capitolo <?php self::getInput('var173', $infos[173]['valore'], 'orange'); ?> del
                bilancio <?php self::getInput('var174', $infos[174]['valore'], 'orange'); ?> e precisamente agli
                impegni n. <?php self::getInput('var175', $infos[175]['valore'], 'orange'); ?>.
                <br>
                Con riferimento al fondo per il lavoro straordinario di cui all’art. 14 comma 1 CCNL 1/4/1999, si dà
                atto che la somma stanziata rimane fissata, come dall’anno 2000, nell’importo di
                € <?php self::getSelect('formula307'); ?>.
                <br>
                Specificare inoltre:
                <br>
                - <?php self::getTextArea('area41', $infos[176]['valore'], 'red'); ?>

                - <?php self::getTextArea('area42', $infos[177]['valore'], 'red'); ?>
                <br>
                - <?php self::getTextArea('area43', $infos[178]['valore'], 'red'); ?>
                <br>
                - <?php self::getTextArea('area44', $infos[179]['valore'], 'red'); ?>

                <br>
                Il Presidente della Delegazione trattante di parte pubblica <?php self::getInput('var180', $infos[180]['valore'], 'black'); ?>
                <br>
                Per la parte relativa allo schema di relazione tecnico – finanziaria
                <br>
                Il <?php self::getInput('var181', $infos[181]['valore'], 'orange'); ?> <?php self::getInput('var182', $infos[182]['valore'], 'orange'); ?>

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