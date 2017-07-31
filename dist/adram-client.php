<?php
    
    define ('ADRAM_ROOT', dir(realpath (__DIR__))->path);
    
    function adram_get_code()
    {
        $Options = json_decode(file_get_contents(ADRAM_ROOT.'/adram-client.json'), true);
        $Options['AdRam']['React']['Options']['AdRecovery']['Code'] = file_get_contents(ADRAM_ROOT.'/unprotected-code.html');
        
        $CURL = curl_init();
        curl_setopt_array($CURL,
            [
                CURLOPT_URL => 'https://adram.media/api/2.2/raw/AdRam/GetDetectAndReactScript',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($Options),
                CURLOPT_HTTPHEADER =>
                [
                    'cache-control: no-cache',
                    'content-type: application/json'
                ]
            ]);
        $Response = curl_exec($CURL);
        $Error = curl_error($CURL);
        curl_close($CURL);
        
        if ($Error)
            return $Error;
        else
        {
            file_put_contents(ADRAM_ROOT.'/protected-code.html' , $Response);
            return true;
        }
    }
    
    adram_get_code();