<?php
namespace EWallet\Repository;

$wallets = [
    0 => ['client' => 'AITA', 'telephone' => '775898985', 'code' => 1234, 'solde' => 0],
    1 => ['client' => 'Fatou', 'telephone' => '782345678', 'code' => 5678, 'solde' => 100000]
];
$transactions = [
    0 => ['montant' => 1000, 'type' => 'depot', 'frais' => 0, 'indexWallet' => 0],
    1 => ['montant' => 5000, 'type' => 'retrait', 'frais' => 200, 'indexWallet' => 0]
];
function trouverWalletParTelephone(string $telephone): int {
    $result = array_filter($GLOBALS['wallets'], fn($w) => $w['telephone'] === $telephone);
    return count($result) > 0 ? array_key_first($result) : -1;
}

function trouverWalletParCode(int $code): int {
    $result = array_filter($GLOBALS['wallets'], fn($w) => $w['code'] === $code);
    return count($result) > 0 ? array_key_first($result) : -1;
}

function ajouterWallet(array $wallet): void {
    $GLOBALS['wallets'][] = $wallet;
}

function mettreAJourSolde(int $indexWallet, float $nouveauSolde): void {
    $GLOBALS['wallets'][$indexWallet]['solde'] = $nouveauSolde;
}

function ajouterTransaction(array $transaction): void {
    $GLOBALS['transactions'][] = $transaction;
}

function listerTransactions(int $indexWallet = -1): array {
    if ($indexWallet === -1) {
        return $GLOBALS['transactions'];
    }
    return array_values(array_filter(
        $GLOBALS['transactions'],
        fn($t) => $t['indexWallet'] === $indexWallet
    ));
}