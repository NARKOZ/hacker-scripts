<?php
namespace HackerScripts;

/**
 * Class fucking_coffee
 *
 * @package HackerSripts
 *
 * Lewis Lancaster 2015
 */

use HackerScripts\Lib\Telnet;

class fucking_coffee
{

    /**
     * The telnet class
     *
     * @var HackerScripts\Lib\Telnet
     */

    protected $telnet;

    /**
     * The password for this coffee machine
     *
     * @var string
     */

    protected $password = "";

    /**
     * The host of this coffee machine
     *
     * @var string
     */

    protected $host = "";

    /**
     * The port of this coffee machine
     *
     * @var string
     */

    protected $port = "";

    /**
     * Delay for 24 seconds.
     *
     * @var int
     */

    protected $delay = 24;

    /**
     * What we do when we construct this class
     */

    public function __construct()
    {

        /**
         * Lets not run this on the weekends
         */

        if( $this->IsWeekend( date('m.d.y') ) == false )
        {

            return false;
        }

        /**
         * Create a new telnet class
         */

        $this->telnet = new Telnet( $this->host, $this->port );

        /**
         * Once we have completed this, we can brew our coffee!
         */

        $this->BrewCoffee( function(){

            /**
             * Echo out a message
             */

            echo "coffee has been poured";

            /**
             * Unset
             */

            unset( $this->telnet );
        });

        /**
         * Return tue
         */

        return true;
    }

    /**
     * Brews our coffee
     *
     * @param $callback
     */

    public function BrewCoffee( $callback )
    {

        if( $this->telnet != null )
        {

            /**
             * Execute and enter the password
             */

            $this->telnet->exec('Password: ' . $this->password);

            /**
             * Brew the coffee
             */

            $this->telnet->exec('sys brew');

            /**
             * Wait
             */

            sleep( $this->delay );

            /**
             * Pour the coffee
             */

            $this->telnet->exec('sys pour');

            /**
             * Execute our callback
             */

            call_user_func( $callback );
        }
    }

    /**
     * Is this currently the weekend?
     *
     * @param $date
     *
     * @return bool
     */

    public function IsWeekend( $date )
    {

        if( date('N', strtotime( $date ) ) >= 6 )
        {

            return true;
        }

        return false;
    }

}