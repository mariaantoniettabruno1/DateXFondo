<?php

namespace dateXFondoPlugin;

use GFAPI;

class ShortCodeCreateFondo
{
    public static function create_fondo(){
        $lastEntry = GFAPI::get_entries(7);
        echo "<pre>";
        print_r($lastEntry);
        echo "</pre>";
        if(!empty($lastEntry[0])){
            $lastEntry = GFAPI::get_entries(7)[0];
            $new_fondo = new CreateFondo();
            $new_fondo->setTitoloFondo($lastEntry[1]);
            $new_fondo->setAnno($lastEntry[25]);
            $new_fondo->setEnte($lastEntry[26]);
            $new_fondo->setDescrizione($lastEntry[2]);
            $new_fondo->setFondoDiRiferimento($lastEntry[6]);
            $new_fondo->setModelloDiRiferimento($lastEntry[7]);
            $new_fondo->setNomeSoggettoDeliberante($lastEntry[8]);
            $new_fondo->setNumeroDeliberaApprovazioneBilancio($lastEntry[9]);
            $new_fondo->setDataLiberaApprovazioneBilancio($lastEntry[10]);
            $new_fondo->setResponsabile($lastEntry[11]);
            $new_fondo->setNumeroDeliberaApprovazionePEG($lastEntry[12]);
            $new_fondo->setDataDeliberaDiApprovazione($lastEntry[13]);
            $new_fondo->setNumeroDeliberaApprovazionePEG($lastEntry[14]);
            $new_fondo->setDataDeliberaDiNomina($lastEntry[15]);
            $new_fondo->setNumeroDeliberaApprovazioneRazionalizzazione($lastEntry[16]);
            $new_fondo->setDataDelibera($lastEntry[17]);
            $new_fondo->setNumeroDeliberaCostituzioneFondo($lastEntry[18]);
            $new_fondo->setDataDeliberaDiCostituzione($lastEntry[19]);
            $new_fondo->setNumeroDeliberaIndirizzoCostituzioneContrattazione($lastEntry[20]);
            $new_fondo->setDataDeliberaIndirizzoAnnoCorrente($lastEntry[21]);
            $new_fondo->setPrincipioRiduzioneSpesaPersonale($lastEntry[22]);
            $new_fondo->setUfficiale($lastEntry['is_approved']);
            $tablename_fondo = 'DATE_entry_new_fondo_'. $new_fondo->getTitoloFondo();
            $temp = $new_fondo->checkIfTableExist($tablename_fondo);
            if($temp){
                //$new_fondo->inserDataFondo($tablename_fondo);
            }
            /*else{
                $tablename_fondo = $new_fondo->createNewTableFondo($tablename_fondo);
                $new_fondo->insertDataFondo($tablename_fondo);
            }*/

        }

        return '';
    }

}