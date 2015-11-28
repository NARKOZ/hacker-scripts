<?php

/**
 * Class hangover
 *
 * @package HackerSripts
 *
 * Aminu Bakori 2015
 */

class hangover
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
     * Boss Phone number
     * @var string
     */

    protected $number_of_boss = "+";

    /**
     * The Excuse
     * @var array
     */

    protected $excuse = array(
                    'Locked out',
                    'Pipes broke',
                    'Food poisoning',
                    'Not feeling well'
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

            echo "Message sent at: ".date('d/m/Y - g:i:s a', time())." | Excuse: ".$this->excuse;

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
             * Get random excuse
             */

            $this->excuse = $this->excuse[array_rand($this->excuse)];

            /**
             * Send message
             */

            $this->twilio->account->messages->create(array(
                "From" => $this->my_number,
                "To" => $this->number_of_boss,
                "Body" => "Gonna work from home. ".$this->excuse,
            ));

            /**
             * Execute our callback
             */

            call_user_func( $callback );
        }
    }
}