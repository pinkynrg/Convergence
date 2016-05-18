<?php 

$tests[] = array(
	'I' => "QUERY|TICKETS|4157",
	'O' => "Customer reported LGVs go into alarm in multiple areas near line 4.\n\n1. LGVs faults out when picking up gaylords from Gaylord pick up area. Happens near B.W.011. When the lgv brings material to the station, it sees the finished goods in the last station causing the lgv to stop for about 10-15 seconds. The lgv resets after.\n2. Similar issue near the raw material handover. The LGV sees the product in front of it causing the lgv to stop for 10 seconds. The lgv resets after 10 seconds."
);

$tests[] = array(
	'I' => "QUERY|TICKETS|4516",
	'O' => "A roll showed up in the UNRAPPED Rolls report this morning. It was wrapped in E80 but quality status is HLD. But in Elixir, the roll was unwrapped and quality status was good. Harold wrapped the roll and it is now wrapped in Elixir and E80. We checked the roll in the warehouse to see if the quality status is good and it was so we changed the quality status in E80. It was in the warehouse in line with the A and Z roll just as it should have been."
);


$tests[] = array(
	'I' => "QUERY|TICKETS|4512",
	'O' => "Alarms A8.06, A8.07, A8.08, & A8.09 are displayed when LGV 10 exits a rack and starts turning. If the LGV is reset it will continue. The LGV can do a few missions, but eventually stop with this alarm."
);

$tests[] = array(
	'I' => "QUERY|TICKETS|4497",
	'O' => "Customer is having an issue on the transfer from the palletizers to the Barge LGVs where the pallet will not logically transfer over."
);

$tests[] = array(
	'I' => "QUERY|TICKETS|1350",
	'O' => "Mike Simlin:  \n\"Need an E80 evaluation of lowering the cornering speed for stability code 0 items like gallons\""
);

$tests[] = array(
	'I' => "QUERY|TICKETS|1548",
	'O' => "From the customer  \n  \nWe have been struggling with communication faults in our Finished Goods System that are really impacting production. It has been thought to be caused by our network, and I still think that is likely however, I don't understand why we would see a difference after rebooting the LGV system if the problem is simply within the wireless access points. Can anyone tell me if this still seems like a wireless network issue, or if maybe there is something else going on that we need to look at, or have E80 look at?  \n  \nUpdate from customer:  \n  \nAfter talking with someone since I wrote that message, I believe that they reset everything PC and all LGVs. And I believe that the reason that appeared to make things better is simply because the LGV having the communication issue at that moment was included in the reset, and had that LGV been reset by itself its likely that they would have seen the same results. It seems that when one LGV has a comm fault the whole system is affected until that LGV radio gets reset again, and we are having a lot of problems in one area causing all the LGVs to fault frequently there."
);

$tests[] = array(
	'I' => "QUERY|TICKETS|1936",
	'O' => "All,  \n  \nWe have trial product being run here in Granite with a QT/Q2 disposition on it however E80 does not have the Q2 so in Matrics the QT and Q2 dispositions are on the pallet but in E80 only the QT Disposition code exists.   \n  \nMatrics Support,  \n  \nI'm not sure but I believe this may be a Maintenance Transaction from Matrics to E80 that is missing as the Q2 hold is not part of the PRN template and would not be sent by the Auto PRN please check and see if we are sending a Maintenance transaction for ticket 6188270"
);

$tests[] = array(
	'I' => "QUERY|POSTS|6613",
	'O' => "David,   \n  \nI have analyzed the alarms you are having on your robot. The fanuc manual gives me no information of the cause or solution of SRVO-352. SYST-045 tells me you have the switch of the fanuc in the ON position. I do not know what host 244 is. A134 on the HMI tells me there is a hold signal on the robot preventing it from going to the next line of code. If SRVO-352 is resolved, the other alarms will probably go away. Fanuc needs to be contacted to find out the cause of this alarm and what host 244 ethernet throttle is. You can call them at 1-800-477-6268 or a PO can be issued and we can contact them directly. If you call them, provide them the E number (E followed by 5 or 6 digits) found at robot base or at the bottom of fanuc cabinet.  \n  \nThanks"
);

$tests[] = array(
	'I' => "QUERY|POSTS|11517",
	'O' => "1. Check that both the palletizer and the dragon are in manual mode.\n\n2. Empty all the lines of product, boxes, pallets, half-pallets, etc.\n\n3. Reset the counters in the palletizer HMI and the dragon HMI.\n\n4. Ask if the program is using is new or an old program that has been tested before.  \n  \n a. old program:  \n  \n The cause is most likely an operational error.  \n  \n b. new program:  \n  \n you will neet to configure the programs in the palletizer HMI, the palletizer teach pentant, and the dragon teach pentant."
);

$tests[] = array(
	'I' => "QUERY|TICKETS|3552",
	'O' => "Please open a ticket for our inbound conveyor.\n\nSince **_midnight_** the calls don't seem to reach LGV manager and all our investigations point to a PLC issue on the conveyor or KEPserver problem.\n\nLast night right after midnight was the last successful pickup from the conveyor - L30001631066 LGV 15 dropped it of at the rack and there were no issues at all with it.\n\nMaster log shows only one line from CV0501 since midnight:\n\n2015-07-13 00:01:22.0057 | Info | Load Unit[3407785] created at Station[CV0501] with parameters LPN[NDW10Z24PSP.IND0000217.120], Status[1], Wrapped[1]\n\nThe conveyor itself looks and acts just fine and here what I did so far this morning.\n\n- rebooted the PC\n- can get to the share drive of the PC so network connection is ok\n- Tried a couple of manual pallet pulls with a forklift and the conveyor always moves the next pallets in place and shows a call again see pics double call ok.jpg, and single call ok.jpg\n- on the client it shows that the conveyor has no call and is not in run see pic: CV0501 no call and in alarm on client.\n\nI logged on to smart and checked the OPC client on CV0501 and did not see the same data as on the other conveyors. But I never looked at CV0501 before and do not have any records to how it looks normally.\n\nIt might be something that started right at midnight.\n\nFYI **E80 safety team is here working on the master too so please don't kick them out. If you need to kick them out please call Efe before hand so they can be ready.**"
);

$tests[] = array(
	'I' => "QUERY|POSTS|12104",
	'O' => "Info for Dino or other engineer.  \n- when I rebooted the PC the first time on Monday morning I got an error in a file that at least started with DataAcccess and it was a .NET framework error and the FEMSA did not communicate. Don't think it has anything to do with it but the DataAccesConfig.xml was open when I checked this morning.  \n- since we can connect locally with the OPC scout is it possible to see if that can reach a Krones PLC. I.e. CV0101 at 10.60.71.81 and memory - DB950,B0[30] for the PLC\\_to\\_PC data -t hat should show us the heart beat.  \nIf that works we would at least know that the data can get through the network ok unless KEPserver uses something different than the Siemens OPC scout. If it doesn't work it might not mean much since we never tried it while it was working ok."
);

$tests[] = array(
	'I' => "QUERY|TICKETS|3658",
	'O' => "Customer repoted to Jimy today that there is \"Not enough space to unload\" alarm happening with all truck loading LGVs when there they unload a Heavy prodcut (More than 800lbs).\n\nIt happens with all LGVs.\n\nIt happens in a regular basis every day when they have a heavy product.\n\nWhen LGV unload the heavy product on the left side at the trailer this alarm occurs right before LGV unloads the pallet. Operator press the \"force search ok\" button from the HMI pannel and LGV unloads the pallet fine. But the when LGV goes back to unload the pallet on the right side then they are receiving another 4 alarm and they end up unloding the pallet which is on the right side manually. They have to repeat this action for all trailer if they have this problem starts from the beginning.\n\nCustomer will send some pictures and more information through e mail. Waiting their repsonse for more information."
);

$tests[] = array(
	'I' => "QUERY|POSTS|1682",
	'O' => "Willi reports:  \n1. LGVs reboot actually on pretty much all \"old\" batteries (the new ones we received this spring are fine). On the sheet below all batteries highlighted in yellow are batteries were we documented that they rebooted the on a low battery. There might be more batteries that cause reboots we did not catch yet since the indications for an operator who responds to an alarm are not that obvious that the PC rebooted. The columns after the warranty exp. show the average Amp Hours we put back in the battery.  \nNot sure if I mentioned it but the PC reboot usually happens while the LGV is lifting a load and accelerating out of block storage which obviously uses the most amount of energy. We also saw that happen when the LGV did not even have the low battery warning (comes before low battery alarm). Our first thought was to maybe limit lift & drive at the same time when the battery is in low battery warning.  \nIt was not just two batteries or at least it progressed now to affecting almost all old batteries. A rough guess would be 5 reboots a day. We then force a battery change so it wont happen again.  \nLong term/future LGV idea would be to put at least the PC on a small UPS so we just get an alarm and not a reboot that can cause all kinds of other issues. There are situations where we just get inverter alarms and in those cases the alarm happens when an LGV lifts up a load to the second level in block storage.   \nOn the first batch we send 4 batteries to our local battery company (ABT set that up) so they can replace the bad cells with used ones. Out of the first 4 batteries they could only make one good battery and they got two more batteries they are working on now. I also have two more batteries out of service because they just take between 80 -120 AH charge. That leaves us right now running with 7 batteries less than normal.  \n2. Good news I assume there was a difference in some parameters ?  \n3. Yes, we are working with Ken Fearn and want to transition over to wet cell batteries since the gel batteries are not standing up to our requirements. Right now we are trying to get some better discounts on the wet cell batteries since the gel ones are still under the Hawker 3 year warranty."
);

$tests[] = array(
	'I' => "QUERY|POSTS|1658",
	'O' => "I emailed to Willi:  \n  \nToday I am writing to you in regards to ticket #520 & #653.   \n  \nYou raised three points in ticket#520:  \n  \n1. Severe voltage drops with bad/old batteries   \n2. Collision alarms with bad/old batteries   \n3. Reduced amp hour capacity of the batteries.   \n  \nMy comments are:  \n  \n1. You noted two batteries which were found to be the cause of Beckhoff restarts and removed from service.   \na. Issue closed  \n2. We have opened a separate ticket # 653 for this issue. We have already downloaded the parameters from the GOOD first inverter you sent us, and we just received the BAD second today, and we will download the parameters and return the inverter to you ASAP.   \n3. Has ABT been working with you on this issue?  \n  \nCan you please give me a short quick update on the battery situation at Niagara indy?   \n  \n How are the batteries?  \n  \n Is ABT working with Niagara Indy on the current battery issues?"
);

$tests[] = array(
	'I' => "QUERY|POSTS|15655",
	'O' => "Hi all,\n\nI found old booking on this station from 2014. I have removed booking and made location functional for use.\n\nselect \\* from WMSPORTHUDSON.Storage.StorageLocation where Name like 'PS\\_CR\\_PR\\_16'  \nselect \\* from WMSPORTHUDSON.Storage.StoragePosition WHERE Id\\_StorageLocation = 2062\n\nSELECT \\* FROM WMSPORTHUDSON.Storage.StockUnitPosition WHERE Id\\_StoragePosition IN   \n(select ID from WMSPORTHUDSON.Storage.StoragePosition WHERE Id\\_StorageLocation = 2062)\n\nselect \\* from Inventory.StockUnit where Id = 727927\n\nupdate WMSPORTHUDSON.Storage.StockUnitPosition set booking = 0 where Id = 1538638 and Booking = 1\n\nI am attaching some screen shots."
);

$tests[] = array(
	'I' => "QUERY|POSTS|3576",
	'O' => "Following change was made in GetEmptyWHLocationsInfoForHousekeeping ticket # 1348\n\nselect sl.Dbf\\_l\\_Id,\n\n sl.Dbf\\_s\\_Name,\n\n sl.Dbf\\_f\\_Clearance,\n\n sl.Dbf\\_f\\_Length \n\n from Storage.Tbl\\_StorageLocation sl\n\n join Storage.Tbl\\_StorageLocationType slt\n\n ON sl.Dbf\\_l\\_Id\\_LocationType= slt.Dbf\\_l\\_Id\n\n AND slt.Dbf\\_s\\_Name= 'BLOCK'\n\n --WHERE isnull(sl.Dbf\\_b\\_RetrivalBlocked,0 ) = 0\n\n --AND isnull(sl.Dbf\\_b\\_RetrivalDisabled,0 ) = 0 \n\n WHERE isnull(sl.Dbf\\_b\\_StorageBlocked,0) = 0--JM\n\n AND isnull(sl.Dbf\\_b\\_StorageDisabled,0) = 0--JM\n\n AND sl.Dbf\\_l\\_Idnot in(select P.Dbf\\_l\\_Id\\_StorageLocationfrom Storage.Tbl\\_StoragePosition pWHERE\n\n P.Dbf\\_b\\_StorageBlocked 0 OR P.Dbf\\_b\\_StorageDisabled 0 )--JM\n\n AND NOT EXISTS(\n\n SELECT SUPI.Dbf\\_l\\_Id\n\n FROM Storage.Tbl\\_StockUnitPosition SUPI\n\n JOIN Storage.Tbl\\_StoragePosition SPI\n\n ON SUPI.Dbf\\_l\\_Id\\_StoragePosition= SPI.Dbf\\_l\\_Id\n\n AND SPI.Dbf\\_l\\_Id\\_StorageLocation= SL.Dbf\\_l\\_Id\n\n )"
);

$tests[] = array(
	'I' => "QUERY|POSTS|2246",
	'O' => "I was working on the previous example - 'S9444536' According to my research 1) E80 had send STUB\\_AUTO\\_PMAN\\_PLTF on 2013-02-07 at 12:09 for first time as shown in our database log. There is a certain time stamp difference. 2) Transport order attached to it was for 'Shipping' and it was aborted / cancelled and pallet went to carwash. 3) SKU attached to this LPN on this transport order is '2027211' was not 'INVALID SKU' 4) WMS logs had printed error connecting to oracle database. So the code printed is -99 for above LPN. 5) E80 sets the status of pallet to processed in E80 database while Red Praire is missing data about this LPN. We need to work out proper solution that would avoid this oracle connectivity error and re-send the LPN/Pallet data to Red Praire."
);

$tests[] = array(
	'I' => "QUERY|POSTS|3028",
	'O' => "Customer emailed :  \n1. \"SA176883 deposited in bay BS4526-03 on 6-1-2013 @ 11:30am\"  \n2. \"SA176884 deposited in bay BS4503-01 by LGV 9 on 6-01-2013 @ 11:45 am\"  \n3. \"SA178090 deposited in bay BS3205-04 by LGV 14 on 6-1-2013 @ 2:05pm\"  \n4. \"LGV 14 stopped in bay BS4542-01 while trying to pick up two pallets of the Sparkle(r) item 21650511. There was a good LPN and a bad LPN in the double stack. The good LPN that contained all the information was SA178613. The other pallet in the double stack that only had pallet information in E-80 was SA178612. The mission on LGV 14 was canceled around 7:15 on 6-2-2013 and the pallets were sent to the Carwash to be I'd. \"  \n5. \"There were loads allocated and LGV 12 was going to a bay. LGV 12 dropped off pallet SA176881 in bay BS4537-04 around 1:40pm on 6-2-2013. The LGV was moving a single pallet. Again this pallet only had information in E-80 and not Red Prairie.\"  \n6. \"SA177097 dropped off by LGV 27 in bay BS4517-01 at 6:10 pm on 6-2-2013  \n  \nSA176882 dropped off by LGV 19 in bay BS4521-02 at 6:15 pm on 6-2-2013\""
);

// $tests[] = array(
// 	'I' => "QUERY|POSTS|16208",
// 	'O' => ""
// );

?>