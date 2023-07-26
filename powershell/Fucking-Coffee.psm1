Function Fucking-Coffee {

    [CmdletBinding()]
    Param(
        [Parameter(Position = 0,Mandatory = $true)]
        [ipaddress]$CoffeMachineIp,
        [Parameter(Position = 1,Mandatory = $true)]  
        [int]$Port,
        [Parameter(Position = 2,Mandatory = $true)]  
        [string]$Password      
    )

    if ($env:Username.Count -lt 1) {
        break
    }

    $CommandTimeTable = [ordered]@{
        $Password = 17
        "sys brew" = 24
        "sys pour" = 1
    }
    
    $TcpClient = New-Object System.Net.Sockets.TcpClient
    $TcpClient.Connect($CoffeMachineIp, $Port)
    
    if ($TcpClient.Connected -eq $true) {
        
        $Stream = $TcpClient.GetStream()

        #Start issuing the commands based on $CommandTimeTable values
        foreach ( $CommandTimePair in $CommandTimeTable.GetEnumerator() ) {

            [byte[]]$CommandBytes = [text.encoding]::ASCII.GetBytes($CommandTimePair.Name)
            $Stream.Write($CommandBytes,0,$CommandBytes.Length)
            $Stream.Flush()
            Start-Sleep -Seconds $CommandTimePair.Value

        }

        $TcpClient.Dispose()
        $Stream.Dispose()

    }

}
