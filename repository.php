<?php
// =============================================
// DONNÉES GLOBALES
// =============================================

$wallets = [
    0 => ['client' => 'AITA', 'telephone' => '775898985', 'code' => 1234, 'solde' => 0],
    1 => ['client' => 'Fatou', 'telephone' => '782345678', 'code' => 5678, 'solde' => 100000]
];

$transactions = [
    0 => ['montant' => 1000, 'type' => 'depot', 'frais' => 0, 'indexWallet' => 0],
    1 => ['montant' => 5000, 'type' => 'retrait', 'frais' => 200, 'indexWallet' => 0]
];

// =============================================
// FONCTIONS D'ACCÈS AUX WALLETS
// =============================================

function trouverWalletParTelephone(string $telephone): int {
    global $wallets;
    $result = array_filter($wallets, fn($w) => $w['telephone'] === $telephone);
    return count($result) > 0 ? array_key_first($result) : -1;
}

function trouverWalletParCode(int $code): int {
    global $wallets;
    $result = array_filter($wallets, fn($w) => $w['code'] === $code);
    return count($result) > 0 ? array_key_first($result) : -1;
}

function ajouterWallet(array $wallet): void {
    global $wallets;
    array_push($wallets, $wallet);
}

function mettreAJourSolde(int $indexWallet, float $nouveauSolde): void {
    global $wallets;
    $wallets[$indexWallet]['solde'] = $nouveauSolde;
}

// =============================================
// FONCTIONS D'ACCÈS AUX TRANSACTIONS
// =============================================

function ajouterTransaction(array $transaction): void {
    global $transactions;
    array_push($transactions, $transaction);
}

function listerTransactions(int $indexWallet = -1): array {
    global $transactions;
    if ($indexWallet === -1) {
        return $transactions;
    }
    return array_values(array_filter(
        $transactions,
        fn($t) => $t['indexWallet'] === $indexWallet
    ));
}