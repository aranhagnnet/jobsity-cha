<?php

// API URL
$url = 'http://172.16.1.20';


/***********************************************************************************************************************************

No need to change anything below this line

************************************************************************************************************************************/ 
ob_start();
session_start();

error_reporting(E_ALL & ~E_NOTICE);

$self = $_SERVER['PHP_SELF'];
$submit = $_POST['submit'];
$command = $_POST['command'];
$sess = $_POST['sess'];

if($submit==NULL)
{
    $submit=$_GET['submit'];
}


?>

<html> 
<head>
<title> Chatbot </title>
</head>
<body>

<?php

if(($submit==NULL) or ($submit=="Cancel"))// home, login & create user options
{
?>
    <table width=80% align=center valign=middle> 
    <form action='<?php echo($self)?>' method='post' name='loginform'>
    <tr> <td> <h1> Welcome to Chatbot </h1> </td> </tr>
    <tr> <td> <hr/> </td> </tr>
    <tr> <td> <h2> <a href=<?php echo($self);?>?submit=create_new_user> Create New User </a> </h2> </td> </tr>
    <tr> <td> <h2> Login </h2> </td> </tr>
    <tr> <td> username: <input type='text' name='username'/> </td> </tr>
    <tr> <td> password: <input type='password' name='password'/> </td> </tr>
    <tr> <td> <input type='submit' name='submit' value='Login' /> </td> </tr>
    </form>

    <?PHP
       if($_GET['msg']=='error_login')
       {
           ?>
        <tr> <td> <h2> Login or password error </h2> </td> </tr>
           <?php 
       }
       if($_GET['msg']=='u_err')
       {
           ?>
        <tr> <td> <h2> User creation error :( </h2> </td> </tr>
           <?php 
       }
       if($_GET['msg']=='u_ok')
       {
           ?>
        <tr> <td> <h2> User has been created successfully  :) </h2> </td> </tr>
           <?php 
       }
    ?>
    </table>
    </body>
    </html>

<?php
}
else if($submit=="Login") 
{
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(($username==NULL) or($password==NULL))
    {
        header("Location: $self");
    }

    $url="$url/login/$username/password/$password";

    // Create a new cURL resource
    $ch = curl_init($url);

    // Setup request to send json via POST
    $data = array(
        'login' => $username,
        'password' => $password
    );

    # Form data string
    $postString = http_build_query($data, '', '&');

    $payload = json_encode($data);

    # Setting our options
    curl_setopt($ch, CURLOPT_POST, 1);

    // Attach encoded JSON string to the POST fields
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

    // Set the content type to application/json
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

    // Return response instead of outputting
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the POST request
    $result = json_decode(curl_exec($ch));  // returns STRING, not BOOLEAN

    //print($result);exit();

    if($result!=NULL) // I got a session id! 
    {
        $_SESSION['username'] = $username;
        $_SESSION['sess'] = $result;
        header("Location: $self?submit=chatbot&username=$username");
        exit();
    }
    else
    {
        header("Location: $self?msg=error_login");
        exit();
    }
}
else if ($submit=="Logout")
{
    $username = $_SESSION['username'];

    if($username!=NULL)
    {
        $url="$url/logout/$username";

        // Create a new cURL resource
        $ch = curl_init($url);
    
        // Return response instead of outputting
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
        // Execute the POST request
        $result = json_decode(curl_exec($ch)); 
    
        header("Location: $self?");
    }
}



else if($submit=="create_new_user")
{
?>

    <table width=80% align=center valign=middle> 
    <form action='<?php echo($self)?>' method='post' name='loginform'>
    <tr> <td colspan=2> <h1> Welcome to Chatbot </h1> </td> </tr>
    <tr> <td colspan=2> <hr/> </td> </tr>
    <tr> <td colspan=2> <h2> Please, fill the form below to create your new user </h2> </td> </tr>
    <tr> <td align=right> First name: </td><td> <input type='text' name='first_name'/> </td> </tr>
    <tr> <td align=right> Last name: </td><td> <input type='text' name='last_name'/> </td> </tr>    
    <tr> <td align=right> username: </td><td> <input type='text' name='username'/> </td> </tr>
    <tr> <td align=right> default currency: </td><td> <select name='default_currency'> 
                    <option value='AED'> AED </option> <option value='AFN'> AFN </option> <option value='ALL'> ALL </option> <option value='AMD'> AMD </option> <option value='ANG'> ANG </option> <option value='AOA'> AOA </option> <option value='ARS'> ARS </option> <option value='AUD'> AUD </option> <option value='AWG'> AWG </option> <option value='AZN'> AZN </option> <option value='BAM'> BAM </option> <option value='BBD'> BBD </option> <option value='BDT'> BDT </option> <option value='BGN'> BGN </option> <option value='BHD'> BHD </option> <option value='BIF'> BIF </option> <option value='BMD'> BMD </option> <option value='BND'> BND </option> <option value='BOB'> BOB </option> <option value='BRL'> BRL </option> <option value='BSD'> BSD </option> <option value='BTC'> BTC </option> <option value='BTN'> BTN </option> <option value='BWP'> BWP </option> <option value='BYN'> BYN </option> <option value='BYR'> BYR </option> 
                    <option value='BZD'> BZD </option> <option value='CAD'> CAD </option> <option value='CDF'> CDF </option> <option value='CHF'> CHF </option> <option value='CLF'> CLF </option> <option value='CLP'> CLP </option> <option value='CNY'> CNY </option> <option value='COP'> COP </option> <option value='CRC'> CRC </option> <option value='CUC'> CUC </option> <option value='CUP'> CUP </option> <option value='CVE'> CVE </option> <option value='CZK'> CZK </option> <option value='DJF'> DJF </option> <option value='DKK'> DKK </option> <option value='DOP'> DOP </option> <option value='DZD'> DZD </option> <option value='EGP'> EGP </option> 
                    <option value='ERN'> ERN </option> <option value='ETB'> ETB </option> <option value='EUR'> EUR </option> <option value='FJD'> FJD </option> <option value='FKP'> FKP </option> <option value='GBP'> GBP </option> <option value='GEL'> GEL </option> <option value='GGP'> GGP </option> <option value='GHS'> GHS </option> <option value='GIP'> GIP </option> <option value='GMD'> GMD </option> <option value='GNF'> GNF </option> <option value='GTQ'> GTQ </option> <option value='GYD'> GYD </option> <option value='HKD'> HKD </option> <option value='HNL'> HNL </option> <option value='HRK'> HRK </option> <option value='HTG'> HTG </option> <option value='HUF'> HUF </option> <option value='IDR'> IDR </option> <option value='ILS'> ILS </option> <option value='IMP'> IMP </option> <option value='INR'> INR </option> <option value='IQD'> IQD </option> <option value='IRR'> IRR </option> <option value='ISK'> ISK </option> <option value='JEP'> JEP </option> <option value='JMD'> JMD </option> <option value='JOD'> JOD </option> <option value='JPY'> JPY </option> <option value='KES'> KES </option> <option value='KGS'> KGS </option> <option value='KHR'> KHR </option> <option value='KMF'> KMF </option> <option value='KPW'> KPW </option> <option value='KRW'> KRW </option> <option value='KWD'> KWD </option> <option value='KYD'> KYD </option> <option value='KZT'> KZT </option> <option value='LAK'> LAK </option> <option value='LBP'> LBP </option> <option value='LKR'> LKR </option> <option value='LRD'> LRD </option> <option value='LSL'> LSL </option> <option value='LTL'> LTL </option> <option value='LVL'> LVL </option> <option value='LYD'> LYD </option> <option value='MAD'> MAD </option> <option value='MDL'> MDL </option> <option value='MGA'> MGA </option> <option value='MKD'> MKD </option> <option value='MMK'> MMK </option> <option value='MNT'> MNT </option> <option value='MOP'> MOP </option> <option value='MRO'> MRO </option> <option value='MUR'> MUR </option> <option value='MVR'> MVR </option> <option value='MWK'> MWK </option> <option value='MXN'> MXN </option> <option value='MYR'> MYR </option> <option value='MZN'> MZN </option> <option value='NAD'> NAD </option> <option value='NGN'> NGN </option> <option value='NIO'> NIO </option> <option value='NOK'> NOK </option> <option value='NPR'> NPR </option> <option value='NZD'> NZD </option> <option value='OMR'> OMR </option> <option value='PAB'> PAB </option> <option value='PEN'> PEN </option> <option value='PGK'> PGK </option> <option value='PHP'> PHP </option> <option value='PKR'> PKR </option> <option value='PLN'> PLN </option> <option value='PYG'> PYG </option> <option value='QAR'> QAR </option> <option value='RON'> RON </option> <option value='RSD'> RSD </option> <option value='RUB'> RUB </option> <option value='RWF'> RWF </option> <option value='SAR'> SAR </option> <option value='SBD'> SBD </option> <option value='SCR'> SCR </option> <option value='SDG'> SDG </option> <option value='SEK'> SEK </option> <option value='SGD'> SGD </option> <option value='SHP'> SHP </option> <option value='SLL'> SLL </option> <option value='SOS'> SOS </option> <option value='SRD'> SRD </option> <option value='STD'> STD </option> <option value='SVC'> SVC </option> <option value='SYP'> SYP </option> <option value='SZL'> SZL </option> <option value='THB'> THB </option> <option value='TJS'> TJS </option> <option value='TMT'> TMT </option> <option value='TND'> TND </option> <option value='TOP'> TOP </option> <option value='TRY'> TRY </option> <option value='TTD'> TTD </option> <option value='TWD'> TWD </option> <option value='TZS'> TZS </option> <option value='UAH'> UAH </option> <option value='UGX'> UGX </option> <option value='USD'> USD </option> <option value='UYU'> UYU </option> <option value='UZS'> UZS </option> <option value='VEF'> VEF </option> <option value='VND'> VND </option> <option value='VUV'> VUV </option> <option value='WST'> WST </option> <option value='XAF'> XAF </option> <option value='XAG'> XAG </option> <option value='XAU'> XAU </option> <option value='XCD'> XCD </option> <option value='XDR'> XDR </option> <option value='XOF'> XOF </option> <option value='XPF'> XPF </option> <option value='YER'> YER </option> <option value='ZAR'> ZAR </option> <option value='ZMK'> ZMK </option> <option value='ZMW'> ZMW </option> <option value='ZWL'> ZWL </option>
                    </select> </td> </tr>
    <tr> <td align=right> e-mail: </td><td> <input type='text' name='email'/> </td> </tr>
    <tr> <td align=right> password: </td><td> <input type='text' name='password'/> </td> </tr>
    <tr> <td colspan=2> <input type='submit' name='submit' value='Create User' /> </td> </tr>
    <tr> <td colspan=2> <input type='submit' name='submit' value='Cancel' /> </td> </tr>
    </form>
    <?PHP
       if($_GET['msg']=='error_login')
       {
           ?>
        <tr> <td> <h2> Login or password error </h2> </td> </tr>
           <?php 
       }
    ?>
    </table>
    </body>
    </html>

<?php
}
else if($submit=="Create User")
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $default_currency = $_POST['default_currency'];

    // API URL
    $url = "$url/users";

    // Create a new cURL resource
    $ch = curl_init($url);

    // Setup request to send json via POST
    $data = array(
        'username' => $username,
        'password' => $password,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'default_currency' => $default_currency
    );

    # Form data string
    $postString = http_build_query($data, '', '&');
    # Setting our options
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute the POST request
    $result = curl_exec($ch);
    $result_j = json_decode($result);
    // Close cURL resource
    curl_close($ch);

    if(is_int($result_j->user_id))
    {
        header("Location: $self?submit=Cancel&msg=u_ok");
    }
    else 
    {
        header("Location: $self?submit=Cancel&msg=u_err");
    }

}
else if(($submit=="chatbot") or ($submit=="Send Command"))
{
    if(($command!=NULL) and ($submit=="Send Command"))
    {
        $command = trim($command); 

        $original_command = $command;
        $array_commands = explode(" ", $command);
        $test_command = $array_commands[0];

        if($test_command=="convert") // convert the currency parameters to uppercase letters
        {
            $array_commands[2]=strtoupper($array_commands[2]);
            $array_commands[3]=strtoupper($array_commands[3]);
            $command = implode(" ", $array_commands);
            $original_command = $command;
        }

        $command = str_replace(' ', '/', $command); 
        $last_char = substr($command, 0, -1);
        if($last_char=="/")
        {
            $command = substr($command, 0, strlen($command)-1);
        }
    
        if(($command=="balance") or ($command=="transactions") or ($test_command=="withdraw") or ($test_command=="deposit") or ($test_command=="convert"))
        {
            $username = $_SESSION['username'];  
            $sess = $_SESSION['sess'];            
            $command=$command."/$username/$sess";
        }
   
        // API URL
        $url = "$url/$command";
        
        // Create a new cURL resource
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // debugs output to screen when disabled! 
        $result_command = curl_exec($ch);
        $result_command = json_decode($result_command, true);
        curl_close($ch);
    }

?>
    <table width=80% align=center valign=middle> 
    <form action='<?php echo($self)?>' method='post' name='loginform'>
    <tr> <td> <h1> Welcome to Chatbot </h1> </td> </tr>
    <tr> <td> <hr/> </td> </tr>
    <tr> <td> <h2> Type your command below </h2> </td> </tr>
    <tr> <td> <input style='width:200px' type='text' name='command'/> </td> </tr>
    <tr> <td> <input type='submit' name='submit' value='Send Command' /> </td> </tr>
    <tr> <td> <input type='submit' name='submit' value='Logout' /> </td> </tr>
    <input type='hidden' name='sess' value='$sess' /> 
    </form>
    </table>
    <br><br>

    <?PHP

    if(is_array($result_command))
    {
        print("<table border=1 width=60% align=center>");
        print("<tr> <td> <b> $original_command </b> </td></tr> ");
        if($original_command=="transactions")
        {
            print("<tr><td><b> ID </b></td> <td><b> username </b></td> <td><b> previous balance </b></td> <td><b> operation </b></td>
            <td><b> amount </b></td> <td><b> currency </b></td> <td><b> current balance </b></td> <td><b> date/time </b></td></tr>");
        }
        foreach ($result_command as $row)
        {
            if(is_array($row))
            {
                print("<tr>");
                foreach($row as $elem)
                {
                   print("<td> $elem </td> ");
                }
                print("</tr>");                
            }
            else 
            {
                print("<tr> <td> $row </td></tr> ");
            }
        }
        print("</table><br/><br/>");
    }
    else if(is_float($result_command))
    {
        $result_command = number_format($result_command, 2);
        print("<table border=1 width=60% align=center>");
        print("<tr> <td> <b> $original_command </b> </td></tr> ");
        print("<tr> <td> $result_command </td></tr> </table>");
    }
    ?>

    <table width=80% align=center valign=middle> 
    <tr> <td colspan=2> <h3> Commands available</h3> </td> </tr>
    <tr> <td> balance </td> <td> returns account balance and default currency </td> </tr>
    <tr> <td> deposit 100 USD </td> <td> Makes a deposit of US$ 100 USD and shows the remaining balance</td> </tr>
    <tr> <td> withdraw 25 USD </td> <td> Makes a withdraw of US$ 25 USD and shows the remaining balance </td> </tr>
    <tr> <td> deposit 15 EUR </td> <td> Makes a deposit of US$ 15 EUR (will be converted to your default currency automatically) and shows the remaining balance</td> </tr>
    <tr> <td> withdraw 200 BRL </td> <td> If available, makes a withdraw of R$ 200 (will be converted to your default currency automatically) and shows the remaining balance</td> </tr>
    <tr> <td> convert 200 BRL EUR</td> <td> Will convert a value from first currency to second (use currency codes in uppercase)</td> </tr>
    <tr> <td> transactions</td> <td> Will print a history of all your account transactions </td> </tr>
    </table>
    </body>
    </html>

<?php
}

ob_flush();

?>