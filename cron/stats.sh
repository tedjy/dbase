#!/bin/bash
#Derniere modification le : 02/05/2022
#Par : Laurent Asselin

#Creation d'un tableau associatif pour lier les entreprises avec les services RADIUS
declare -A ENTREPRISE
CLIENT=""
SERVICE=""

if ! grep -q publisher.sh /etc/crontab ; then #Pas de cluster détectée, on est donc sur des logs radius "standard".

while read LINE
do
        if echo "$LINE" | grep -q "^client " >/dev/null 2>&1 ; then
                SERVICE=$(echo "$LINE" | awk '{print $2}')
        fi
        if echo "$LINE" | grep -q "virtual_server " >/dev/null 2>&1 ; then
                CLIENT=$(echo "$LINE" | awk '{print $3}' | sed s/_mfa//)
                if [ -n "$SERVICE" ] && [ -n "$CLIENT" ]; then
                        ENTREPRISE["$SERVICE"]="$CLIENT"
                fi
        fi
done < /etc/freeradius/clients.conf

CORP=""
LOGIN=""
OTP_FIREWALL=""
LAST_CONNECTED=""
SERVICE=""

Date_time=""
Vm_name=""
Society=""
Username=""
Firewall=""
Login=""
Result=""

declare -A ALREADY

        #Lecture des logs RADIUS par le debut, et enregistrement des informations de connexions
        tac /var/www/cron/radius.log | while read LINE ;
        do
                set -- $LINE;
                #Récupération de l'entreprise en fonction du service
                SERVICE=${14}
                CORP=${ENTREPRISE[$SERVICE]}

                #Récupération du login
                LOGIN=${11}
                LOGIN=$(echo "${LOGIN,,}")
                LOGIN=$(echo "${LOGIN//[[\]]}")

                #Récupération de la Date
   

                Date_time="$1 $2 $3 $4 $5"
                Date_time=$(date -d"$Date_time" "+%Y-%m-%d %H:%M:%S")

               
                 Vm_name="$7 $8 $9 $10"
                 Vm_name=$(awk '{print $7 $8 $9 $10}')

                 Login="$9 $10 $11 $12"
                 Login=$(awk '{print $15" "$16}')

                 Firewal="$11 $12"
                 Firewal=$(awk '{print $11" "$12}')

               
                 php -q /var/www/cron/test.php $Date_time $LOGIN $CORP $Vm_name $Society $LAST_CONNECTED $SERVICE $Firewal $Login $Result
                ALREADY["$CORP$LOGIN"]="OK"

        

done < /var/www/cron/radius.log

fi