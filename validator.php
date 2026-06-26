<?php
// =============================================
// FONCTIONS DE VALIDATION
// =============================================

function validerTelephoneUnique(string $telephone): bool {
    $index = trouverWalletParTelephone($telephone);
    return $index === -1;
}

function validerCodeUnique(int $code): bool {
    $index = trouverWalletParCode($code);
    return $index === -1;
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
    global $wallets;
    return $wallets[$indexWallet]['solde'] >= ($montant + $frais);
}

function validerWalletExiste(int $index): bool {
    return $index !== -1;
}