<#
.SYNOPSIS
    Simple script to connect to a coffee part using TelNet then issue specific commands that
	brew and pour a cup of coffee for the user.
.DESCRIPTION
    This script was converted using the ruby version of the fucking_coffee script. In this script,
    I left the use of environment variables since its only use was to determine if the user was
    still logged in to the system.  Per issue #42 (https://github.com/NARKOZ/hacker-scripts/issues/42)
    I left the password string hard coded until a decision is made by NARKOZ, the project owner, as
    to how the information should be stored.
.OUTPUT
    None
.NOTES
    Author:            Tyler Hughes
    Twitter:           @thughesIT
    Blog:              http://tylerhughes.info/

    Changelog:
       1.0             Initial Release
#>

Function Fucking-Coffee
{
    # Exit early if no sessions with my username are found
    if ($env:Username.Count > 0) {
        return
    }

    $coffee_machine_ip = '10.10.42.42'
    $password = '1234'

    Start-Sleep -s 17

    $socket = New-Object System.Net.Sockets.TcpClient($coffee_machine_ip)
    if ($socket) {
        $stream = $connection.GetStream()
        $Writer = New-Object System.IO.StreamWriter($Stream)
        $Buffer = New-Object System.Byte[] 1024
        $Encoding = New-Object System.Text.AsciiEncoding

        # Start issuing the commands
        Send-TelNetCommands($Writer, $password, 1)
        Send-TelNetCommands($Writer, "sys brew", 24)
        Send-TelNetCommands($Writer, "sys pour", 4)

        $socket.Close()
    }
}

Function Send-TelNetCommands
{
    Param (
        [Parameter(ValueFromPipeline=$false)]
        [System.IO.StreamWriter]$writer,
        [String]$command,
        [int]$WaitTime
    )

    $writer.WriteLine($command)
    $writer.Flush()
    Start-Sleep -Milliseconds $WaitTime
}
