<?php
//Replace the line with require "vendor/autoload.php" if you are using the Samples from outside of _Samples folder
include('../config.php');

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;

// Prep Data Services
$dataService = DataService::Configure(array(
       'auth_mode' => 'oauth2',
         'ClientID' => "L0vlXq781VLVbZiKEh2lgh5pA0yZt04Vq9lP4c6y5hLkf6LwNZ",
         'ClientSecret' => "cQPD3eEb743sOmTFKA434DHEW9cSkR4sCPfIpS5B",
         'accessTokenKey' => "eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..mV4xovyZK6mRexR9DCq2wQ.RdDJ0Lip4YzZia91gZMfvuHPAmLgsLUbb2R37nuO_qJkAJmove5d2WL1Tu3lRLf6sGEMkpAHDxhph2ZGSx-7JgI1NLkR6xggcH5qr5EXiWI0YaOgZqOooMBuUvX3WM_j5BKPW2iFj7ZvVk4ymLIyagV1XTD3gqg2vBqZwGcKZqSpxVtJOBt0HuWOgCFCes8HVl8aiZlY3hXR0VQTnFcU5mw-RSfiyO5eW3YwrWgC4-_0Fj8oKavx4QQrxHArVD9yQwxVOzw46R5mUmWnHek-MEpvhCHCp2mLxYna6roCMIgEI0ALTJzI9nly24BUXSENNE98HWtV4E8xfnXHnPiTUkeIpTYJj9Mbwt4xFgNrN19vOFwA66j-urbQxX_crj-VhYvv0pW99qxIrkib3Mk03yIf5ioyuWC_mivXdLDP8A25c1JuBZGXqleBjxcmy5CShLAyT72TZ0YNzn1pDedaxJkEJ2FKeNzOW-bNCd2mxFTom_QcgEGmgXZ4-pcmuLh3kzrJ5qW3u8yb2qIJt41fV-fCUNbhDVlBGcssR_PTQ2O6wiYZtdiJIcm_ENmIhVXMwd9Cg3heKuFQG5VgdScLMWM8DJ1scd70OjQSobc-ZTlBvAgqRaL1g3-ovr8i25r-yVRf4MSauV7IkdKMvSexTYI4OgS_cxKQsW-cnlTsE185mRnGfksQAloDaQVnl9f0.avItU0TwmZhsoHQkpLeVZw",
         'refreshTokenKey' => "Q011521985818jvSS7efQ4JJpMk0307t1kmDV0peN9hrkhwIzH",
         'QBORealmID' => "123145946400044",
         'baseUrl' => "Development"
));


$CompanyInfo = $dataService->getCompanyInfo();
$error = $dataService->getLastError();
if ($error != null) {
    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
    echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
    echo "The Response message is: " . $error->getResponseBody() . "\n";
    echo "The Intuit Helper message is: IntuitErrorType:{" . $error->getIntuitErrorType() . "} IntuitErrorCode:{" . $error->getIntuitErrorCode() . "} IntuitErrorMessage:{" . $error->getIntuitErrorMessage() . "} IntuitErrorDetail:{" . $error->getIntuitErrorDetail() . "}";
} 