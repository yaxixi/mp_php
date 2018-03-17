<?php
//error_reporting(0);

include_once "alipay-sdk-PHP-20180122110057/AopSdk.php";

$app_id = $_REQUEST['app_id'];
$auth_code = $_REQUEST['auth_code'];
$scope = $_REQUEST['scope'];

echo json_encode($_REQUEST);

$c = new AopClient;
$c->gatewayUrl = "https://openapi.alipaydev.com/gateway.do"; //"https://openapi.alipay.com/gateway.do";
$c->appId = "2016091100488614";
$c->rsaPrivateKey = 'MIIEpAIBAAKCAQEAvVM2aQ27kSWRr1Ip3WZA7TcX7qak8jEwuWu/v+fxyolWZ68FIdu3obFBHWv3kAzqPxCnAaTidSOVs0iBEPMZfQlBurihNW5AvD3803X+ksEZMz7ChCYI6gsOW57a16S/UsL+RsQl4gfYc8oOmfSJYSYebmFZ2oFsi/vmZvhHZWVSwqVYjNiKJy6bJk1w9PcPa3hKga7ZzC59ggrT+o2JvRDSHSdYIJRtzLVWeRNVPelN77SDdxzxL+QpHadkj46dnXeBWa2cEkTgUpkQMLmW9comfbkud2/2G1wmUxk8ZVegudjr0pOIa6Hwh/aiAbhjwb50IxNJ1xfuDDkc20IY9wIDAQABAoIBAGhHKgPo9XO3zqtTRQ3WIVLG8p6XGOjxIRYv+9h2p53X0UighOImQ9mCaQwMiLnF80uzH1lveO/uHqk1+SjdzR8qdxKiWOC2Dl/ggJ30MrigNQIdwkPJM0W9uzXJpF38Nwkdr7JInVkG2zjDhRFVOEnTFhXT9wq8GI5tu3ThsWlt12qS9HId+fT1Um3lMAgrQoUgzhJzqzwe0lz9VzJhpCRdgXH0AAgQMPCXsnJZKtnaNWLQ2EiV94H7OxuGnMuHkCe4JtsfcKdRg+Wr7Gj76db+evpLg0SkipRVUp6lrRwUmT7KMNCU/UhzVd0w0mdWBJJi413A0FaPvmtd5EEQjlkCgYEA7GTIT8PyXfSxR3WDrx+NEN5353mbjLeFhsX+eIr8ZwXDYbw8I6k4hbpvtm9nkUTv/6keol9AoiawfGJrYWPCxDYNmDa3V4YFQZKRiSVBaCe+4/5fwHjI8Hse6+o+NhMP/ChMQI3Gj/ed0GuwGN5C5n4j9OZEPOiMmuz3iVprkU0CgYEAzQcMLorFKns+kvpicP8ZSE/b6xjM2CKUymzRirhcQtJMElI6YyNlLP40RU85lwfIEkb3f/5ZT8uPXdlJBE/Xpw5LHsdNh4MiVCBySjl1dCMNtOoQ+UmjNHSPau7rsU+xfuoTa3wiRFlCvQ1/fpPQhd0ijw/1K1xmFbC9QZy7cVMCgYEApO5oJ+cByfX4XnAqMVwlExj0tYbONW6x9edcdYQbOFH4EM/BcewWKI98c9/kdaGQhWLcodMtiDTQO+h7HRb6g+VmtMWwQ5uZPTfi3LommWHpuK745ZQgNlDgsR7cyuOf6oG/OebxG+9ap1kLIz6nlKN7uNmT0J87wrgEuUth850CgYBMsxc+jhFmbAajq2GKThRiNeXalEOFk6I/++eD0qZJJSzqKbLIBaTC6TIav6GWu4WAOOY8DqXQsSEoBbJIF6pgR3uYvwxk5ozAFgdLdyVWVgq5pFrHN2IDrJs+cLThdyO6i8zKB/01voK2AMeyTY/TlD9qb5jTKNTTxnN2iTCiGwKBgQCDlwyiyOMZknZlwuREiQ1F+MEYJYDYx/i/xn/YFiSXFoUPLmV4YgYOeh0+kKBVBxrBNdQuOs/KtCPCt5h3YzL6qS9+AHKsQAz9BA1+DVs2nui+nrXJXBTdqL87t/8FzNYyNnVIEFiwjlhqpWvoyssmOg8Fi46TTsVcz54K0L1Mig==';
$c->format = "json";
$c->charset= "GBK";
$c->signType= "RSA2";
$c->alipayrsaPublicKey = 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvVM2aQ27kSWRr1Ip3WZA7TcX7qak8jEwuWu/v+fxyolWZ68FIdu3obFBHWv3kAzqPxCnAaTidSOVs0iBEPMZfQlBurihNW5AvD3803X+ksEZMz7ChCYI6gsOW57a16S/UsL+RsQl4gfYc8oOmfSJYSYebmFZ2oFsi/vmZvhHZWVSwqVYjNiKJy6bJk1w9PcPa3hKga7ZzC59ggrT+o2JvRDSHSdYIJRtzLVWeRNVPelN77SDdxzxL+QpHadkj46dnXeBWa2cEkTgUpkQMLmW9comfbkud2/2G1wmUxk8ZVegudjr0pOIa6Hwh/aiAbhjwb50IxNJ1xfuDDkc20IY9wIDAQAB';

echo "1111";
$request = new AlipaySystemOauthTokenRequest();
$request->setCode($auth_code);
$request->setGrantType("authorization_code");

echo "wwwww";
try
{
//$response= $c->execute($request);
    throw new Exception(" check sign Fail! The reason : signData is Empty");
}
catch(Exception $e)
{
    echo 'Message: ' .$e->getMessage();
}

echo "test";
die(json_encode($response));

?>
