<?php
//Replace the line with require "vendor/autoload.php" if you are using the Samples from outside of _Samples folder
include(dirname(__FILE__) . '/../config.php');

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;

// Prep Data Services
// $dataService = DataService::Configure(array(
//        'auth_mode' => 'oauth1',
//          'consumerKey' => "lve2eZN6ZNBrjN0Wp26JVYJbsOOFbF",
//          'consumerSecret' => "fUhPIeu6jrq1UmNGXSMsIsl0JaHuHzSkFf3tsmrW",
//          'accessTokenKey' => "qye2etcpyquO3B1t8ydZJI8OTelqJCMiLZlY5LdX7qZunwoo",
//          'accessTokenSecret' => "2lEUtSEIvXf64CEkMLaGDK5rCwaxE9UvfW1dYrrH",
//          'QBORealmID' => "193514489870599",
//          'baseUrl' => "https://qbonline-e2e.api.intuit.com/"
// ));

$dataService = DataService::Configure(array(
       'auth_mode' => 'oauth2',
         'ClientID' => "L0vlXq781VLVbZiKEh2lgh5pA0yZt04Vq9lP4c6y5hLkf6LwNZ",
         'ClientSecret' => "cQPD3eEb743sOmTFKA434DHEW9cSkR4sCPfIpS5B",
         'accessTokenKey' => "eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..aftaKbjjircZ5tAZGRBJPg.N4xs4WgFg6jXt1A6WdxSDCunGhP-VysFBroq3t3qqOVN4XecUVEwLx1ZOnoHI3g1ugQiM2-ObjmNLqq_j6P446nSHpofDvfqkbhL2OVUDTZ1vMxXFeTGIAlxGKBADaw-U3_IqkFFAn5ctRoatcC3tBYZkfH1t8pI3HS6ZHTsfLnzqZmV2PAT4CS8oHHRS6t41ZqXRoufRqQzFSXECTKmKblWbi8s0u1W3vRdFMQ3xP0TDzNMUv6mp1TB2v_1nivtXkg_jj0dtavwWrO5E5UYj5RLd2x7Vw_7H41mGM-aErcRW7Xp21_LO5IiF3FfdwR34p5Su_qJJ8Hg-kC3SEtQzC7SMK5kDuhd9t2moidSK8nh5tMvHnZQy1eyXh3aeBg2uKJKniuCXc-l8aIYct_W4sUy-uadGAAfhgCA5JkiAwFQ8nPKRp2DlCu-mzleiBHumgPp-AwaKAe_Fr58q9PmZLPWr1Yv08C6BV8up6x5TuXEf1Vy2wTVkjE91Dnyf97RfDZE9j0202YpzeyG0Ed0aAPvuslmx4KQ3AyVHWEgIULpbQjQtI1448R7zaKI-yef2INA1B4rYUBAOi_3AQRF-d3dMs36zOkuRCVEN93-ns715EckSdIjKqKPxoga26Xbl7x-3Ama7dBcgpPEKlxGfAv5V6Kp1trICRpJYeAm8uond8Y2Ft2_kOJ6mewsD-kE.cjHhiSJjXtsIkWvrwqGuqQ",
         'refreshTokenKey' => "Q011521992216XUEph8VspNbdOd9uf3eREX9A3peDVnEbkZsen",
         'QBORealmID' => "123145946400044",
         'baseUrl' => "Development"
));

// $dataService = DataService::Configure(dirname(__FILE__) . '/../sdk.config');

$dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");

// Iterate through all Customers, even if it takes multiple pages
$i = 0;
while (1) {
    $allCustomers = $dataService->FindAll('Customer', $i, 500);
    $error = $dataService->getLastError();
    if ($error != null) {
        echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
        echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
        echo "The Response message is: " . $error->getResponseBody() . "\n";
        exit();
    }
    if (!$allCustomers || (0==count($allCustomers))) {
        break;
    }

    foreach ($allCustomers as $oneCustomer) {
        echo "Customer[".($i++)."]: {$oneCustomer->DisplayName}\n";
        echo "\t * Id: [{$oneCustomer->Id}]\n";
        echo "\t * Active: [{$oneCustomer->Active}]\n";
        echo "\n";
    }
}

/*

Example output:

Customer[0]: JIMCO
     * Id: [NG:953957]
     * Active: [true]

Customer[1]: ACME Corp
     * Id: [NG:953955]
     * Active: [true]

Customer[2]: Smith Inc.
     * Id: [NG:952359]
     * Active: [true]


...

*/
