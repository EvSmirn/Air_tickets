<?php
$xmlstr = "
<S:Envelope xmlns:S='http://schemas.xmlsoap.org/soap/envelope/'>
	<S:Body>
		<ns2:searchFlights xmlns:ns2='http://partner.v3.webservice.berlogic.de/'>
			<settings>
				<agencyCode>pulkovoairport</agencyCode>
				<salesPoint>pulkovoairport</salesPoint>
				<agentCode>pulkovoairport</agentCode>
				<agentPassword>KG=bR5C8zrW2!</agentPassword>
				<dateTolerance>0</dateTolerance>
				<lang>ru</lang>
				<mixedVendors>true</mixedVendors>
				<eticketsOnly>true</eticketsOnly>
				<preferredCurrency>RUB</preferredCurrency>
				<skipConnected>false</skipConnected>
                               
				<serviceClass>".$s_serviceClass."</serviceClass>
				<route>
                                    	<beginLocation>".$s_beginLocation."</beginLocation>
					<date>".$s_date."</date>
					<endLocation>".$s_endLocation."</endLocation>
                                </route>
				
				<seats>
					<entry>
						<key>ADULT</key>
						<value>".$s_adult."</value>
					</entry>
                                        <entry>
						<key>CHILD</key>
						<value>".$s_child."</value>
					</entry>
                                        <entry>
						<key>INFANT</key>
						<value>".$s_infant."</value>
					</entry>
					
				</seats>
			</settings>
		</ns2:searchFlights>
	</S:Body>
</S:Envelope>
";

    // echo '<pre>';
    // print_r($xmlstr);
     //echo '</pre>';