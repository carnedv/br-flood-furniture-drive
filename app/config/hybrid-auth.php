<?php

/**
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2015, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */
// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return [
        "base_url" => "http://ffd.dev/auth",
        "providers" => array(
            "Google" => array(
                "enabled" => true,
                "keys" => array(
                    "id" => "73809102130-8sd6h9920ff2jp17frp3mo3hiacocmqh.apps.googleusercontent.com",
                    "secret" => "SNg8RHcHkMQFeb2oSpsppqHN"
                ),
            )
        ),
        "debug_mode" => false,
        "debug_file" => "../logs/hybrid-auth.log"
];