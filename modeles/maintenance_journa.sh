#!/bin/bash

cd /var/www/html/nephaliste
php modeles/maintenance_journa.php



old_IFS=$IFS
IFS=$'\n'
date=`date -R`

#On s'occupe d'abord des négatifs
negatifs=`mysql -ucoopeman -pcoope nephaliste <<< $'SET NAMES \'UTF8\';
SELECT nom,email,solde
FROM comptes
WHERE solde < -10 and email != \' \' and ouvert = 1'`

premiereLigne=true

for line in $negatifs
do

if [ $premiereLigne = true ]
then
  premiereLigne=false
  continue
fi

  nom=`cut -f 1 <<< $line`
  email=`cut -f 2 <<< $line`
  solde=`cut -f 3 <<< $line`

  message="Subject:GROSSE Dette envers la =?UTF-8?B?Q29vcMOp?=
MIME-Version: 1.0
To: $email
Date: $date
Content-Type: text/plain;charset=utf-8

Bonjour $nom,
Je suis au regret de t'informer que ton compte Coopé est GRAVEMENT en négatif : ton solde est de $solde.
Cette situation ne peut VRAIMENT pas durer. Si tu ne rembourses pas TRES rapidement la Coopé, je me verrai dans l'obligation d'encaisser ton chèque de caution de 25 euros et de t'interdire les découverts (s'il y en a un. Si t'en as pas, bin soit t'es Coopéman, soit t'as profité du système des années précédentes, et c'est pas cool !).
Merci de régulariser rapidement ta situation.

La Coopé,
Sans frénésie, mais avec [un système de gestion très] classe [et un peu en colère]."

  msmtp "$email" <<< "$message"

done
