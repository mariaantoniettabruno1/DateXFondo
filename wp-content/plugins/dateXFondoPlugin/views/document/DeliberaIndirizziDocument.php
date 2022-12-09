<?php

namespace dateXFondoPlugin;

class DeliberaIndirizziDocument
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

    public static function render()
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

        </head>

        <body>
        <h2><?php self::getInput('var1', 'Il/La', 'red'); ?><?php self::getInput('var2', 'nome_soggetto_deliberante', 'orange'); ?> </h2>
        <button class="btn btn-outline-secondary btn-edit" style="width:10%">Modifica</button>
        <h3>OGGETTO: PERSONALE NON DIRIGENTE. FONDO RISORSE DECENTRATE PER
            L’ANNO <?php self::getInput('var3', 'anno', 'orange'); ?>. INDIRIZZI PER LA COSTITUZIONE PARTE VARIABILE.
            DIRETTIVE PER LA CONTRATTAZIONE DECENTRATA INTEGRATIVA.</h3>
        <div>Visti:
            <br>
            - la deliberazione
            di <?php self::getInput('var4', 'Consiglio', 'red'); ?>   <?php self::getInput('var5', 'Comunale/Assemblea', 'red'); ?>
            n. <?php self::getInput('var6', 'numero_delibera_approvazione_bilancio', 'orange'); ?> del
            <?php self::getInput('var7', 'data_delibera_approvazione_bilancio', 'orange'); ?>, esecutiva, relativa a:
            “Bilancio di previsione <?php self::getInput('var8', 'anno', 'orange'); ?>, bilancio
            pluriennale
            e <?php self::getInput('var9', 'DUP/PEG', 'red'); ?><?php self::getInput('var10', '2022/2024', 'red'); ?> ,
            piano di investimenti – approvazione”;
            <br>
            -la
            deliberazione <?php self::getInput('var11', 'della/del', 'red'); ?>  <?php self::getInput('var12', 'nome_soggetto_deliberante', 'blue'); ?>
            n.<?php self::getInput('var13', 'numero_delibera_approvazione_peg', 'orange'); ?> del
            , esecutiva, relativa all’approvazione del Piano esecutivo di
            Gestione <?php self::getInput('var14', 'anno', 'orange'); ?>
            unitamente al Piano della Performance;
            <br>
            -i successivi atti di variazione del bilancio del comune e del P.E.G./Piano Performance;
            <br>
            -il vigente Regolamento di Organizzazione degli Uffici e dei Servizi;
            <br>
            -la
            deliberazione <?php self::getInput('var15', 'della/del', 'red'); ?> <?php self::getInput('var16', 'nome_soggetto_deliberante', 'blue'); ?>
            n.<?php self::getInput('var17', 'numero_delibera_nomina', 'orange'); ?>
            del <?php self::getInput('var18', 'data_delibera_nomina', 'orange'); ?> di
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
        </div>

        </body>

        <script>
            $(document).ready(function () {
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
                })
            });
        </script>
        </html lang="en">

        <?php
    }

}