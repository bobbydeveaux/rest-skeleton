<?php

namespace DVO;

use DVO\Session;
use DVO\ClientFactory;
use DVO\Mail;

/**
 * Class to find Clients
 *
 * @author DVO Media (https://www.dvomedia.net)
 **/
class ClientFinder
{
    /**
     * Find a client, and send details to bobby@dvomedia.net
     *
     * @param ClientFactory $clientFactory
     */
    public function findClient(ClientFactory $clientFactory)
    {
        $user = Session::get('YOU');

        // Everyone knows someone..
        do {
            $client = $clientFactory->load($user->getRandomContact());
        } while (false === $client->needsDevelopmentWork());

        Mail::send('bobby@dvomedia.net', $client->getDetails());

        if (true === $client->isSale()) {
            $user->sendThanks();
        }

        return $client;
    }
}
