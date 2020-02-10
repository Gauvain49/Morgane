<?php
namespace App\Services\Payment;
use Lyra\Client;

class ClicAndPay
{
	public function getDefaultValue()
	{
        /**
         * Define configuration
         */

        /* Username, password and endpoint used for server to server web-service calls */
        Client::setDefaultUsername("61962027");
        Client::setDefaultPassword("testpassword_rEoACMDQVUFDUrWkzRu4PuFnHqksvHF3o32CRUlW0KxiM");
        Client::setDefaultEndpoint("https://api-clicandpay.groupecdn.fr");

        /* publicKey and used by the javascript client */
        Client::setDefaultPublicKey("61962027:testpublickey_5adpAqbWO1l9REvYgMyvKhp6tqP0Dlg6AAE6I16fYyZjL");

        /* SHA256 key */
        Client::setDefaultSHA256Key("Y04TKntfBOTYtQnDHqhfapnzi9XrOAcDe5Ot9LWK4veGC");
    }

	public function getDefaultValueTest()
	{
        /**
         * Define configuration
         */

        /* Username, password and endpoint used for server to server web-service calls */
        Client::setDefaultUsername("69876357");
        Client::setDefaultPassword("testpassword_DEMOPRIVATEKEY23G4475zXZQ2UA5x7M");
        Client::setDefaultEndpoint("https://api.payzen.eu");

        /* publicKey and used by the javascript client */
        Client::setDefaultPublicKey("69876357:testpublickey_DEMOPUBLICKEY95me92597fd28tGD4r5");

        /* SHA256 key */
        Client::setDefaultSHA256Key("38453613e7f44dc58732bad3dca2bca3");
    }
}