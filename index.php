<?php
require 'repository.php';
require 'validator.php';
require 'services.php';
require 'controller.php';

use function EWallet\Controller\routerChoix;

function afficherMenu(): void {
    echo "\n** Menu Distributeur **\n";
    echo "1 - Créer Wallet\n";
    echo "2 - Faire Dépôt\n";
    echo "3 - Faire Retrait\n";
    echo "4 - Lister les Transactions\n";
    echo "0 - Quitter\n";
}

do {
    afficherMenu();
    $choix = readline("Votre choix : ");
    routerChoix($choix);
} while ($choix !== '0');