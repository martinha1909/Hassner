<?php
session_start();
$_SESSION['dependencies'] = "TEST";

define("PARENT_INCLUDE_DIR", dirname(dirname(__FILE__)));
define("CURRENT_INCLUDE_DIR", dirname(__FILE__));
define("CURRENT_DATE", date('Y-m-d H:i:s'));

include PARENT_INCLUDE_DIR."\control\db_comms\DBComms.php";
// include PARENT_INCLUDE_DIR."\shared\include\Helper.php";
include PARENT_INCLUDE_DIR."\constants\StatusCodes.php";
include PARENT_INCLUDE_DIR."\constants\AccountTypes.php";
include PARENT_INCLUDE_DIR."\constants\ShareInteraction.php";
include PARENT_INCLUDE_DIR."\constants\HX.php";
include PARENT_INCLUDE_DIR."\constants\Timezone.php";
include CURRENT_INCLUDE_DIR.'\stubs\transaction\BalanceTestStubs.php';
include CURRENT_INCLUDE_DIR.'\include/TestHelpers.php';
// include 'stubs/transaction/BalanceTestStubs.php';

date_default_timezone_set(Timezone::MST);

class TransactionTest extends \PHPUnit\Framework\TestCase
{
    private function accountExistsAssert($conn, $username)
    {
        $res = searchAccount($conn, $username);
        $account_info = $res->fetch_assoc();

        $this->assertNotEquals(null, $account_info, "User ".$username." doesn't exist");
    }

    public function testBalanceCorrectAfterInjectionTransaction()
    {
        $conn = connectTest();
        $connPDO = connectPDOTest();
        $stock_quantity = 10;
        $stock_price = 10;
        $buyer_username = "martin";
        $seller_username = "Al Lure";
        $buyer_account_type = AccountType::User;
        $seller_account_type = AccountType::Artist;
        $buyer_balance = 100;
        $seller_balance = 0;
        $buyer_new_balance = $buyer_balance - ($stock_quantity * $stock_price);
        $seller_new_balance = $seller_balance + ($stock_quantity * $stock_price);

        $this->accountExistsAssert($conn, $buyer_username);
        $this->accountExistsAssert($conn, $seller_username);

        $this->assertEquals(StatusCodes::Success, updateUserBalance($conn, $buyer_username, $buyer_balance), "Failed to initiate buyer balance");
        $this->assertEquals(StatusCodes::Success, updateUserBalance($conn, $seller_username, $seller_balance), "Failed to initiate seller balance");

        //any parameters that are not relevant to this test case will be set to -1
        $status = purchaseAskedPriceShare($connPDO,
                                          $buyer_username,
                                          $seller_username,
                                          AccountType::User,
                                          AccountType::Artist,
                                          $seller_username,
                                          $buyer_new_balance,
                                          $seller_new_balance,
                                          -1,
                                          -1,
                                          -1,
                                          -1,
                                          $stock_quantity,
                                          $stock_price,
                                          -1,
                                          CURRENT_DATE,
                                          "",
                                          -1);

        $this->assertEquals(StatusCodes::Success, $status, "purchaseAskedPriceShare failed to execute");

        $new_buyer_balance = getUserBalance($conn, $buyer_username);
        $new_seller_balance = getUserBalance($conn, $seller_username);
        $expected_new_buyer_balance = $buyer_balance - ($stock_quantity * $stock_price);
        $expected_new_seller_balance = $seller_balance + ($stock_quantity * $stock_price);

        $this->assertEquals($expected_new_buyer_balance, $new_buyer_balance, "Buyer new balance doesn't match with expected value (".$new_buyer_balance." != ".$expected_new_buyer_balance.")");
        $this->assertEquals($expected_new_seller_balance, $new_seller_balance, "Seller new balance doesn't match with expected value (".$new_seller_balance." != ".$expected_new_seller_balance.")");

        closeCon($conn);
    }
}
?>