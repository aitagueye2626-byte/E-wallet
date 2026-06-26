<?php
namespace EWallet\Validator;

use function EWallet\Repository\trouverWalletParTelephone;
use function EWallet\Repository\trouverWalletParCode;

function validerTelephoneUnique(string $telephone): bool {
    return trouverWalletParTelephone($telephone) === -1;
}

function validerCodeUnique(int $code): bool {
    return trouverWalletParCode($code) === -1;
}

function validerSoldeInitial(float $solde): bool {
    return $solde >= 0;
}

function validerChampObligatoire(string $valeur): bool {
    return trim($valeur) !== '';
}

function validerMontantPositif(float $montant): bool {
    return $montant > 0;
}

function validerSoldeDisponible(int $indexWallet, float $montant, float $frais): bool {
    return $GLOBALS['wallets'][$indexWallet]['solde'] >= ($montant + $frais);
}

function validerWalletExiste(int $index): bool {
    return $index !== -1;
}