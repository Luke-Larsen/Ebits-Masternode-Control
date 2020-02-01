# Ebits Masternode Control

A simple web based command and control server for ebit masternodes
## Requirements
- A vps to host your masternodes
- A webserver to run this code on

## Instulation
- Install you Masternode on your vps you can find out more at https://www.ebitscrypto.com/Tutorials/MakeAMasternode.html
- Once you have installed your masternode ssh into it and go to the ebits.conf file.
    - ``` nano ~/.EBITS/ebits.conf ``` on most instulations
- You will need to change the line that says;
    - ``` rpcallowip=127.0.0.1 ``` to ``` rpcallowip=0.0.0.0/0 ``` or the webserver ip for more security
        -  If you use 0.0.0.0/0 any ip can connect into your box
        - Also important to node some instulations have a port of 99901 change this to a lower number or you will get errors
- Now upload the files included in this github to your webserver
- Edit the index.php file with your Masternode information
- Congrats your done!
