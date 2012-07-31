#!/bin/bash

old_IFS=$IFS
IFS=$'\n'
date=`date -R`

#On s'occupe d'abord des négatifs
negatifs=`mysql -ucoopeman -pcoope nephaliste <<< $'SET NAMES \'UTF8\';
SELECT nom,email,solde
FROM comptes
WHERE solde < 0 and email != \' \' and ouvert = 1'`

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

  message="Subject:Dette envers la =?UTF-8?B?Q29vcMOp?=
To: $email
Date: $date
Content-Type: text/plain;charset=utf-8

Bonjour $nom,
Je suis au regret de t'informer que ton compte Coopé est en négatif : ton solde est de $solde.
Cette situation ne peut pas durer. Si tu ne rembourses pas rapidement la Coopé, je me verrai dans l'obligation d'encaisser ton chèque de caution de 25 euros et de t'interdire les découverts (s'il y en a un. Si t'en as pas, bin soit t'es Coopéman, soit t'as profité du système des années précédentes, et c'est pas cool !).
Merci de régulariser rapidement ta situation.

La Coopé,
Sans frénésie, mais avec [un système de gestion très] classe."

  msmtp "$email" <<< "$message"

done




#A présent, les chèques de caution
temps=`date +%s`
let "jour=60*60*24"
let "annee=jour*365"
let "debut=temps-annee*11/12"
let "fin=temps-annee"

caution=`mysql -ucoopeman -pcoope nephaliste <<< $"SELECT nom,email,caution
	FROM comptes
	WHERE caution is not null and caution != 0 and caution < $debut and caution > $fin"`

premiereLigne=true

for line in $caution
do

if [ $premiereLigne = true ]
then
  premiereLigne=false
  continue
fi

  nom=`cut -f 1 <<< $line`
  email=`cut -f 2 <<< $line`
  caution=`cut -f 3 <<< $line`
  let "reste=($caution+$annee-$temps)/$jour"

  message="Subject:Expiration du =?UTF-8?B?Y2jDg8KocXVlIGRlIGNhdXRpb24=?=
To: $email
Date: $date
Content-Type: text/plain;charset=utf-8

Bonjour $nom,
Ton chèque de caution, qui te permet d'avoir un découvert allant jusqu'à 25 euros à la Coopé, expire dans $reste jour(s)
Je t'invite donc à le renouveler, sans quoi les découverts te seront interdits (oh noes!! — enfin jusqu'à ce que tu en fasses un autre).

La Coopé,
sans frénésie, mais avec [un système de gestion très] classe."

  msmtp "$email" <<< "$message"

done



IFS=$old_IFS
