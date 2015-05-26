<?php

// Methodless role types
interface TransferMoneySinkInterface {

    public function transferTo();
}

interface TransferMoneySourceInterface {

    public function transferFrom($recipient, $amount);
}

// Traits to implement Methodful roles
// 
trait TransferMoneySinkTrait {

    public function transferTo() {
        return;
    }
}

trait TransferMoneySourceTrait {

    public function transferFrom($recipient, $amount) {
        if ($this instanceof Account) {
            // proceed
            // $a = new SavingsAccount();
            $this->decreaseBalance($amount);
            $this->log("WITHDRAWING: " . $amount);
            $recipient->increaseBalance($amount);
            $recipient->log("DEPOSITING: " . $amount);
        }
        else {
            // error!!
            echo "Do not proceed!!";
        }
    }

}

// Context Object
class TransferMoneyContext {

    private $src = null;
    private $snk = null;
    private $amount = null;

    public function __construct(TransferMoneySourceInterface $source, TransferMoneySinkInterface $sink, $amount) {
        if (null === $this->src) {
            $this->src = $source;
        }
        if (null === $this->snk) {
            $this->snk = $sink;
        }
        if (null === $this->amount) {
            $this->amount = $amount;
        }
    }
    
    public function doIt() {
        $this->src->transferFrom($this->snk, $this->amount);
        // $this->snk->transferTo();
    }

}

// Model
// Abstract Domain Object
abstract class Account {

    public abstract function decreaseBalance($amount);

    public abstract function increaseBalance($amount);

    public abstract function log($message);
}

// Concrete Domain Object
class ATM {
    use TransferMoneySinkTrait;

    use TransferMoneySourceTrait;
    
}

class SavingsAccount extends Account implements TransferMoneySinkInterface, TransferMoneySourceInterface {

    use TransferMoneySinkTrait;

    use TransferMoneySourceTrait;

    private $balance = null;

    public function __construct() {
        $this->balance = 10000;
    }

    public function __toString() {
        return "Balance: " . $this->balance . "<br/>";
    }

    public function log($message) {
        echo "LOG: MESSAGE: $message <br/>";
    }

    public function decreaseBalance($amount) {
        $this->balance -= $amount;
    }

    public function increaseBalance($amount) {
        $this->balance += $amount;
    }

}

class App {

    //put your code here
    public function runIt() {
        echo "Running the App!! <br/>";
        echo "BEFORE: <br/>";
        $src = new \SavingsAccount();
        $snk = new \SavingsAccount();
        echo "SRC: " . $src;
        echo "SINK: " . $snk;
        echo "<br/>>>>>>>>>>>>>>>>>><br/><br/>RUN TRANSFER!!! <br/>";
        $tmc= new TransferMoneyContext($src, $snk, 1000);
        $tmc->doIt();
        echo "<br/>>>>>>>>>>>>>>>>>><br/><br/>TRANSFER FINITO!!! <br/>";
        echo "AFTER: <br/>";
        echo "SRC: " . $src;
        echo "SINK: " . $snk;
        $atm = new ATM();
        $atm->transferFrom($snk, 500);
        
    }

}

$app = new App();
$app->runIt();
