<<<<<<< HEAD
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
=======
#!/usr/bin/env php
<?php

require 'vendor/autoload.php';

(new Dotenv\Dotenv(__DIR__))->load();
(strpos(exec('who'), getenv('USER')) === false) or exit('session found');

$my_number = '+xxx';
$number_of_boss = '+xxx';
$excuse = ['Locked out', 'Pipes broke', 'Food poisoning', 'Not feeling well'];
$excuse = $excuse[array_rand($excuse)];

$twilio = new Services_Twilio(getenv('TWILIO_ACCOUNT_SID'), getenv('TWILIO_AUTH_TOKEN'));
$twilio->account->messages->sendMessage(
	$my_number,
	$number_of_boss,
	"Gonna work from home. {$excuse}"
);

echo "Message sent at: #".date('Y-m-d')." | Excuse: {$excuse}";
>>>>>>> NARKOZ/master
