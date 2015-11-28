<?php

/**
 * Class smack_my_bitch_up
 *
 * @package HackerSripts
 *
 * Aminu Bakori 2015
 */

class smack_my_bitch_up
{

    /**
     * The twilio services class
     *
     * @var HackerScripts\Lib\Services_Twilio
     */

    protected $twilio;

    /**
     * The twilio account sid
     * @var string
     */

    protected $TWILIO_ACCOUNT_SID = "";

    /**
     * The twilio auth token
     * @var string
     */

    protected $TWILIO_AUTH_TOKEN = "";

    /**
     * My Phone number
     * @var string
     */

    protected $my_number = "+";

    /**
     * Her Phone number
     * @var string
     */

    protected $her_number = "+";

    /**
     * The reason
     * @var array
     */

    protected $reason = array(
                    'Working hard',
                    'Gotta ship this feature',
                    'Someone fucked the system again'
                );


    /**
     * What we do when we construct this class
     */

    public function __construct()
    {

        /**
         * Create a new twilio services class
         */

        $this->twilio = new Services_Twilio($this->TWILIO_ACCOUNT_SID, $this->TWILIO_AUTH_TOKEN);

        /**
         * Once we have completed this, we can send message!
         */

        $this->SendMessage( function(){

            /**
             * Log message
             */

            echo "Message sent at: ".date('d/m/Y - g:i:s a', time())." | Reason: ".$this->reason;

            /**
             * Unset
             */

            unset( $this->twilio );
        });

        /**
         * Return tue
         */

        return true;
    }

    /**
     * Sends our message
     *
     * @param $callback
     */

    public function SendMessage( $callback )
    {

        if( $this->twilio != null )
        {

            /**
             * Get random reason
             */

            $this->reason = $this->reason[array_rand($this->reason)];

            /**
             * Send message
             */

            $this->twilio->account->messages->create(array(
                "From" => $this->my_number,
                "To" => $this->her_number,
                "Body" => "Late at work. ".$this->reason,
            ));

            /**
             * Execute our callback
             */

            call_user_func( $callback );
        }
    }

}