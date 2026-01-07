  <?php    require_once ("../include/initialize.php");
  
  	$select11= $conn->prepare("SELECT * FROM  tbl_aparw_member ");
            $select11->execute();
            while($cAPA = $select11->fetch()){
            
           
           $phone12=$cAPA['phone'];
           $names=$cAPA['names'];  
    $company_number="0788595652"; 
    $msg="Munyamuryango wacu $names Mutumiwe munama y'inteko rusange izaba kucyumweru kuwa 07/08/2022 Mucyumba cy'inama cy'umurenge wa Gisozi saa tatu(9h:00). President wa Aparwa Tel: $company_number";
            
           $cp_name_char="APARWA"; 
          
           echo $names."<br>";
            //  $data=array(
            //           "sender"=>"$cp_name_char",
            //             "recipients"=>"$phone12",
            //             "message"=>"$msg",
            //     );
        
            // $url="https://www.intouchsms.co.rw/api/sendsms/.json";
            // $data=http_build_query($data);
            // $username="twagiramungus";
            // $password="M00dle!!";
        
            // $ch=curl_init();
            // curl_setopt($ch,CURLOPT_URL,$url);
            // curl_setopt($ch,CURLOPT_USERPWD,$username.":".$password);
            // curl_setopt($ch,CURLOPT_POST,true);
            // curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            // curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,0);
            // curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
            // $result=curl_exec($ch);
            // $httpcode=curl_getinfo($ch,CURLINFO_HTTP_CODE);
            // curl_close($ch);
            }
            ?>