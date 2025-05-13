<?php
namespace App;
class Client extends User {
    public \$name, \$nrc, \$country;
    public function __construct(\$data){ foreach(\$data as \$k=>\$v)\$this->\$k=\$v; }
    public function dashboard(){ include __DIR__.'/../client/index.php'; }
    public function applyLoan(\$amount,\$term){ return (new Loan())->create(\$this->id,\$amount,\$term); }
    public function bid(\$auctionId,\$amt){ return (new Auction(\$auctionId))->placeBid(\$this->id,\$amt); }
}
?>