<?php
$xmlstr = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
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
                               
				<serviceClass>ECONOM</serviceClass>
				<route>
                                    	<beginLocation>MOW</beginLocation>
					<date>2017-10-12T03:00:00Z</date>
					<endLocation>LED</endLocation>
                                </route>
				
				<seats>
					<entry>
						<key>ADULT</key>
						<value>1</value>
                                        </entry>
                                        <entry>
						<key>CHILD</key>
						<value>1</value>
					</entry>
					<entry>
						<key>INFANT</key>
						<value>1</value>
					</entry>
										
				</seats>
			</settings>
		</ns2:searchFlights>
	</S:Body>
</S:Envelope>
XML;
