<?php
namespace EWallet\Controller;

use function EWallet\Services\traiterCreationWallet;
use function EWallet\Services\traiterDepot;
use function EWallet\Services\traiterRetrait;
use function EWallet\Services\traiterListeTransactions;

function routerChoix(string $choix): void {
    switch ($choix) {
        case '1':
            $client    = readline("Votre nom complet : ");
            $telephone = readline("Votre numéro de téléphone : ");
            $solde     = (float)readline("Votre solde initial : ");
            $code      = (int)readline("Votre code secret : ");

            $wallet = [
                'client'    => $client,
                'telephone' => $telephone,
                'solde'     => $solde,
                'code'      => $code
            ];
            $resultat = traiterCreationWallet($wallet);

            if (!$resultat['succes']) {
                echo "\n✗ Erreur : " . $resultat['message'] . "\n";
            } else {
                echo "\n✓ Wallet créé avec succès !\n";
                echo "Titulaire : " . $client . "\n";
                echo "Téléphone : " . $telephone . "\n";
                echo "Solde     : " . $solde . " CFA\n";
            }
            break;

        case '2':
            $telephone = readline("Numéro de téléphone du wallet : ");
            $montant   = (float)readline("Montant à déposer : ");
            $resultat  = traiterDepot($telephone, $montant);

            if (!$resultat['succes']) {
                echo "\n✗ Erreur : " . $resultat['message'] . "\n";
            } else {
                echo "\n✓ Dépôt effectué avec succès !\n";
                echo "Titulaire     : " . $resultat['data']['client'] . "\n";
                echo "Montant       : " . $resultat['data']['montant'] . " CFA\n";
                echo "Nouveau solde : " . $resultat['data']['nouveauSolde'] . " CFA\n";
            }
            break;

        case '3':
            $telephone = readline("Numéro de téléphone du wallet : ");
            $montant   = (float)readline("Montant à retirer : ");
            $resultat  = traiterRetrait($telephone, $montant);

            if (!$resultat['succes']) {
                echo "\n✗ Erreur : " . $resultat['message'] . "\n";
            } else {
                echo "\n✓ Retrait effectué avec succès !\n";
                echo "Titulaire     : " . $resultat['data']['client'] . "\n";
                echo "Montant       : " . $resultat['data']['montant'] . " CFA\n";
                echo "Frais         : " . $resultat['data']['frais'] . " CFA\n";
                echo "Nouveau solde : " . $resultat['data']['nouveauSolde'] . " CFA\n";
            }
            break;

        case '4':
            echo "1 - Toutes les transactions\n";
            echo "2 - Transactions d'un wallet spécifique\n";
            $choix     = readline("Votre choix : ");
            $telephone = '';
            if ($choix === '2') {
                $telephone = readline("Numéro de téléphone du wallet : ");
            }
            $resultat = traiterListeTransactions($choix, $telephone);

            if (!$resultat['succes']) {
                echo "\n✗ Erreur : " . $resultat['message'] . "\n";
            } else if (count($resultat['liste']) === 0) {
                echo "\nAucune transaction trouvée.\n";
            } else {
                echo "\n--- Historique des Transactions ---\n";
                for ($i = 0; $i < count($resultat['liste']); $i++) {
                    $t = $resultat['liste'][$i];
                    $w = $GLOBALS['wallets'][$t['indexWallet']];
                    echo "\n[" . ($i + 1) . "] Titulaire : " . $w['client'] . "\n";
                    echo "    Type      : " . $t['type'] . "\n";
                    echo "    Montant   : " . $t['montant'] . " CFA\n";
                    echo "    Frais     : " . $t['frais'] . " CFA\n";
                }
            }
            break;

        case '0':
            echo "\nAu revoir !\n";
            break;

        default:
            echo "\nChoix invalide, veuillez réessayer.\n";
            break;
    }
}