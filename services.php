<?php
// =============================================
// FONCTIONNALITÉ 1 : CRÉER UN WALLET
// =============================================

function traiterCreationWallet(array $wallet): array {
    $resultat = ['succes' => false, 'message' => ''];

    if (!validerChampObligatoire($wallet['client'])) {
        $resultat['message'] = "Le champ 'nom' est obligatoire.";
        return $resultat;
    }
    if (!validerChampObligatoire($wallet['telephone'])) {
        $resultat['message'] = "Le champ 'téléphone' est obligatoire.";
        return $resultat;
    }
    if (!validerTelephoneUnique($wallet['telephone'])) {
        $resultat['message'] = "Ce numéro de téléphone est déjà utilisé.";
        return $resultat;
    }
    if (!validerSoldeInitial($wallet['solde'])) {
        $resultat['message'] = "Le solde initial doit être positif ou nul.";
        return $resultat;
    }
    if (!validerCodeUnique($wallet['code'])) {
        $resultat['message'] = "Ce code secret est déjà utilisé.";
        return $resultat;
    }

    ajouterWallet($wallet);
    $resultat['succes'] = true;
    return $resultat;
}

// =============================================
// FONCTIONNALITÉ 2 : FAIRE UN DÉPÔT
// =============================================

function traiterDepot(string $telephone, float $montant): array {
    $resultat = ['succes' => false, 'message' => '', 'data' => []];

    $indexWallet = trouverWalletParTelephone($telephone);
    if (!validerWalletExiste($indexWallet)) {
        $resultat['message'] = "Aucun wallet trouvé avec ce numéro.";
        return $resultat;
    }
    if (!validerMontantPositif($montant)) {
        $resultat['message'] = "Le montant doit être strictement positif.";
        return $resultat;
    }

    global $wallets;
    $nouveauSolde = $wallets[$indexWallet]['solde'] + $montant;
    mettreAJourSolde($indexWallet, $nouveauSolde);

    ajouterTransaction([
        'montant'     => $montant,
        'type'        => 'depot',
        'frais'       => 0,
        'indexWallet' => $indexWallet
    ]);

    $resultat['succes'] = true;
    $resultat['data']   = [
        'client'       => $wallets[$indexWallet]['client'],
        'montant'      => $montant,
        'nouveauSolde' => $nouveauSolde
    ];
    return $resultat;
}

// =============================================
// FONCTIONNALITÉ 3 : FAIRE UN RETRAIT
// =============================================

function calculerFrais(float $montant): float {
    return match(true) {
        $montant <= 10000  => 200,
        $montant <= 100000 => 500,
        default            => min($montant * 0.01, 5000)
    };
}

function traiterRetrait(string $telephone, float $montant): array {
    $resultat = ['succes' => false, 'message' => '', 'data' => []];

    $indexWallet = trouverWalletParTelephone($telephone);
    if (!validerWalletExiste($indexWallet)) {
        $resultat['message'] = "Aucun wallet trouvé avec ce numéro.";
        return $resultat;
    }
    if (!validerMontantPositif($montant)) {
        $resultat['message'] = "Le montant doit être strictement positif.";
        return $resultat;
    }

    $frais = calculerFrais($montant);

    if (!validerSoldeDisponible($indexWallet, $montant, $frais)) {
        $resultat['message'] = "Solde insuffisant pour couvrir le montant et les frais.";
        return $resultat;
    }

    global $wallets;
    $nouveauSolde = $wallets[$indexWallet]['solde'] - $montant - $frais;
    mettreAJourSolde($indexWallet, $nouveauSolde);

    ajouterTransaction([
        'montant'     => $montant,
        'type'        => 'retrait',
        'frais'       => $frais,
        'indexWallet' => $indexWallet
    ]);

    $resultat['succes'] = true;
    $resultat['data']   = [
        'client'       => $wallets[$indexWallet]['client'],
        'montant'      => $montant,
        'frais'        => $frais,
        'nouveauSolde' => $nouveauSolde
    ];
    return $resultat;
}

// =============================================
// FONCTIONNALITÉ 4 : LISTER LES TRANSACTIONS
// =============================================

function traiterListeTransactions(string $choix, string $telephone): array {
    $resultat = ['succes' => false, 'message' => '', 'liste' => []];

    if ($choix === '1') {
        $resultat['succes'] = true;
        $resultat['liste']  = listerTransactions();
    } else if ($choix === '2') {
        $indexWallet = trouverWalletParTelephone($telephone);
        if (!validerWalletExiste($indexWallet)) {
            $resultat['message'] = "Aucun wallet trouvé avec ce numéro.";
            return $resultat;
        }
        $resultat['succes'] = true;
        $resultat['liste']  = listerTransactions($indexWallet);
    } else {
        $resultat['message'] = "Choix invalide.";
    }
    return $resultat;
}