<?php
session_start();
$_SESSION['dependencies'] = "TEST";

define("PARENT_INCLUDE_DIR", dirname(dirname(__FILE__)));
define("CURRENT_INCLUDE_DIR", dirname(__FILE__));

include PARENT_INCLUDE_DIR."\control\connection.php";
include PARENT_INCLUDE_DIR."\control\Queries.php";
include PARENT_INCLUDE_DIR."\constants\StatusCodes.php";
include PARENT_INCLUDE_DIR."\constants\HX.php";

//stubs
function hx_info()
{

}

class TransactionTest extends \PHPUnit\Framework\TestCase
{
    public function testBalanceCorrectAfterInjectionTransaction()
    {
        // echo CURRENT_INCLUDE_DIR;
        // echo "Hello this dirname is: ".dirname(dirname(__FILE__))."\control\connection.php";
        // include '../control/connection.php';
        // include '../control/Queries.php';
        $conn = connectTest();
        $connPDO = connectPDOTest();
        $buyer_username = "martin";
        $seller_username = "Al Lure";

        $res_buyer = searchAccount($conn, $buyer_username);
        echo json_encode($res_buyer);
        $res_seller = searchAccount($conn, $seller_username);

        // $this->assertGreaterThan($res_buyer->num_rows, 0, "Buyer ".$buyer_username." doesn't exist");
        // $this->assertGreaterThan($res_seller->num_rows, 0, "Seller ".$seller_username." doesn't exist");

        closeCon($conn);
    }
}
?>