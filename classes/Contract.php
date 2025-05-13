<?php
namespace App;
class Contract {
    public static function generate($clientId, $loanId) {
        // fetch client and loan details
        // build PDF including NRC, country, terms
        // save PDF path to contracts table
    }
    public static function approve($contractId) {
        // mark contract approved by client
    }
}

?>