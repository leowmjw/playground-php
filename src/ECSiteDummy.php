<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace EC;

/**
 * Description of ECSiteDummy
 *
 * @author leow
 */
class ECSiteDummy implements ECSiteInterface {

    private $voters_ic = null;

    public function __construct($ic) {
        // Validation needed??
        $this->voters_ic = $ic;
    }

    public function getLabels() {
        return array(
            "ic" => $this->voters_ic,
            "oldic" => '',
            "fullname" => "EN X",
            "dob" => "01 Feb 1909",
            "gender" => "LELAKI",
            "locality" => "107 / 37 / 10 / 003 - BANDAR UTAMA DAMANSARA",
            "dm" => "107 / 37 / 10 - BANDAR UTAMA BU 3 - BU 8",
            "dun" => "107 / 37 - BUKIT LANJAN",
            "par" => "107 - SUBANG",
            "state" => "SELANGOR"
        );
    }

}
